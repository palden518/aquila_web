<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Services\Cart;
use App\Services\StripeCheckout;
use App\Support\Ordering;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected Cart $cart)
    {
    }

    public function index()
    {
        return view('cart', [
            'lines' => $this->cart->lines(),
            'subtotal' => $this->cart->subtotal(),
            'orderingEnabled' => Ordering::enabled(),
            'cardEnabled' => app(StripeCheckout::class)->cardEnabled(),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_item_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:' . Cart::MAX_PER_LINE],
        ]);

        if (! Ordering::enabled()) {
            return back()->with('cart_error', 'Online ordering is paused right now. Please call us to order.');
        }

        $item = MenuItem::available()->find($data['menu_item_id']);

        if (! $item) {
            return back()->with('cart_error', 'Sorry, that item is no longer available.');
        }

        $this->cart->add($item, (int) ($data['quantity'] ?? 1));

        return redirect()
            ->to(url()->previous() . '#item-' . $item->id)
            ->with('cart_status', "Added {$item->name} to your cart.");
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:' . Cart::MAX_PER_LINE],
        ]);

        $this->cart->setQuantity($menuItem->id, (int) $data['quantity']);

        return redirect()->route('cart')->with('cart_status', 'Cart updated.');
    }

    public function remove(MenuItem $menuItem): RedirectResponse
    {
        $this->cart->remove($menuItem->id);

        return redirect()->route('cart')->with('cart_status', "Removed {$menuItem->name} from your cart.");
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return redirect()->route('cart')->with('cart_status', 'Your cart is now empty.');
    }
}
