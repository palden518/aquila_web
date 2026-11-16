<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Order;
use App\Services\Cart;
use App\Services\OrderNotifier;
use App\Services\OrderPayments;
use App\Services\StripeCheckout;
use App\Support\Ordering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        protected Cart $cart,
        protected StripeCheckout $stripe,
        protected OrderNotifier $notifier,
        protected OrderPayments $payments,
    ) {
    }

    public function show(Request $request)
    {
        if (! Ordering::enabled()) {
            return redirect()->route('cart');
        }

        if ($this->cart->isEmpty()) {
            return redirect()->route('menu')->with('cart_error', 'Your cart is empty — add something from the menu first.');
        }

        return view('checkout', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'slots' => Ordering::slots(),
            'user' => $request->user(),
            'cardEnabled' => $this->stripe->cardEnabled(),
            'addons' => Addon::available()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Ordering::enabled()) {
            return redirect()->route('cart')->with('cart_error', 'Online ordering is paused right now. Please call us to order.');
        }

        $methods = $this->stripe->cardEnabled() ? ['pickup', 'card'] : ['pickup'];

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9 +()\-]+$/'],
            'pickup_time' => ['required', 'string'],
            'payment_method' => ['required', Rule::in($methods)],
            'notes' => ['nullable', 'string', 'max:500'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['nullable', 'integer', 'min:0', 'max:' . Addon::MAX_QUANTITY],
            // Honeypot: real people never see or fill this field.
            'hp_field' => ['prohibited'],
        ], [
            'customer_phone.regex' => 'Please enter a valid phone number (digits, spaces, + ( ) and - only).',
            'payment_method.in' => 'Please choose how you would like to pay.',
        ]);

        if (! Ordering::isValidSlot($data['pickup_time'])) {
            throw ValidationException::withMessages([
                'pickup_time' => 'That pickup time is no longer available. Please choose another.',
            ]);
        }

        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('menu')->with('cart_error', 'Your cart is empty — add something from the menu first.');
        }

        $payByCard = $data['payment_method'] === 'card';
        $addonLines = $this->addonLines($data['addons'] ?? []);

        // Prices are re-read from the database inside cart->lines(), never from the form.
        $order = DB::transaction(function () use ($data, $lines, $addonLines, $request, $payByCard) {
            $subtotal = round($lines->sum('line_total'), 2); // menu items only
            $total = round($subtotal + $addonLines->sum('line_total'), 2);

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'type' => 'takeaway',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
                'pickup_time' => Carbon::createFromFormat('Y-m-d H:i', $data['pickup_time']),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'menu_item_id' => $line['item']->id,
                    // Snapshot the name and price so old orders never change if the menu does.
                    'item_name' => $line['item']->name,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'subtotal' => $line['line_total'],
                ]);
            }

            foreach ($addonLines as $line) {
                $order->addons()->create([
                    'addon_id' => $line['addon']->id,
                    // Snapshot, so editing or deleting the add-on never changes this order.
                    'addon_name' => $line['addon']->name,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'subtotal' => $line['line_total'],
                ]);
            }

            if ($payByCard) {
                $this->payments->startCardPayment($order);
            }

            return $order;
        });

        if ($payByCard) {
            return $this->sendToStripe($order);
        }

        // Pay on pickup: the order is real straight away.
        $this->cart->clear();
        $order->load('items', 'addons', 'payments');
        $this->notifier->placed($order);

        return redirect()->route('orders.confirmation', ['order' => $order->order_number]);
    }

    /**
     * Turns the submitted [addon id => quantity] map into priced lines. Prices come
     * from the database; unknown, switched-off or zero-quantity add-ons are ignored.
     *
     * @return \Illuminate\Support\Collection<int,array{addon:Addon,quantity:int,unit_price:float,line_total:float}>
     */
    protected function addonLines(array $selected)
    {
        $ids = collect(array_keys($selected))
            ->filter(fn ($key) => is_numeric($key))
            ->map(fn ($key) => (int) $key)
            ->all();

        if (empty($ids)) {
            return collect();
        }

        return Addon::available()->whereIn('id', $ids)->get()
            ->map(function (Addon $addon) use ($selected) {
                $quantity = min(Addon::MAX_QUANTITY, max(0, (int) ($selected[$addon->id] ?? 0)));
                $unitCents = (int) round(((float) $addon->price) * 100);

                return [
                    'addon' => $addon,
                    'quantity' => $quantity,
                    'unit_price' => $unitCents / 100,
                    'line_total' => ($unitCents * $quantity) / 100,
                ];
            })
            ->filter(fn ($line) => $line['quantity'] > 0)
            ->values();
    }

    /**
     * Card orders are saved first (so the amount is fixed), then the customer is sent
     * to Stripe. The cart is kept until payment succeeds, and the order emails are
     * only sent once Stripe confirms the payment.
     */
    protected function sendToStripe(Order $order): RedirectResponse
    {
        try {
            $session = $this->stripe->createSession($order);

            if (empty($session->url)) {
                throw new \RuntimeException('Stripe returned no checkout URL.');
            }
        } catch (\Throwable $e) {
            Log::error("Could not start Stripe payment for {$order->order_number}: " . $e->getMessage());

            // Don't leave an unpayable order lying around.
            $this->payments->markFailed($order, cancelOrder: true);

            return redirect()->route('checkout')
                ->withInput()
                ->withErrors(['payment_method' => "We couldn't start the card payment. Please try again, or choose to pay at pickup."]);
        }

        return redirect()->away($session->url, 303);
    }
}
