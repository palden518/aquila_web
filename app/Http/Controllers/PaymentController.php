<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart;
use App\Services\OrderPayments;
use App\Services\StripeCheckout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected StripeCheckout $stripe,
        protected OrderPayments $payments,
        protected Cart $cart,
    ) {
    }

    /**
     * Where Stripe sends the customer after they pay. Instead of trusting the
     * redirect, we ask Stripe directly whether the session was paid and for the
     * right amount. The webhook does the same independently, so whichever
     * arrives first wins and the other is a no-op.
     */
    public function return(Request $request, Order $order): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');

        if (! str_starts_with($sessionId, 'cs_') || ! $this->stripe->isConfigured()) {
            return redirect()->route('orders.confirmation', ['order' => $order->order_number]);
        }

        try {
            $session = $this->stripe->retrieveSession($sessionId);
        } catch (\Throwable $e) {
            Log::error("Could not verify Stripe session for {$order->order_number}: " . $e->getMessage());

            return redirect()->route('orders.confirmation', ['order' => $order->order_number])
                ->with('payment_notice', "We're confirming your payment. This page will update shortly, and we'll email you once it's received.");
        }

        if (! $this->stripe->sessionMatchesOrder($session, $order)) {
            Log::warning("Stripe session {$sessionId} does not match order {$order->order_number}.");
            abort(404);
        }

        if ($session->payment_status === 'paid') {
            $this->payments->markPaid($order, $this->stripe->paymentIntentId($session));
            $this->cart->clear();

            return redirect()->route('orders.confirmation', ['order' => $order->order_number])
                ->with('payment_success', 'Payment received — thank you!');
        }

        return redirect()->route('orders.confirmation', ['order' => $order->order_number])
            ->with('payment_notice', "Your payment is still being processed. We'll email you as soon as it's confirmed.");
    }

    /** Lets a customer try again if they closed the Stripe page without paying. */
    public function retry(Order $order): RedirectResponse
    {
        $order->load('payments');

        if (! $this->stripe->cardEnabled()
            || $order->status === 'cancelled'
            || ! in_array($order->paymentState(), ['awaiting', 'failed'], true)) {
            return redirect()->route('orders.confirmation', ['order' => $order->order_number]);
        }

        try {
            $this->payments->startCardPayment($order);
            $session = $this->stripe->createSession($order);

            if (empty($session->url)) {
                throw new \RuntimeException('Stripe returned no checkout URL.');
            }
        } catch (\Throwable $e) {
            Log::error("Could not restart Stripe payment for {$order->order_number}: " . $e->getMessage());

            return redirect()->route('orders.confirmation', ['order' => $order->order_number])
                ->with('payment_error', "We couldn't reach the card payment page. Please try again in a moment.");
        }

        return redirect()->away($session->url, 303);
    }
}
