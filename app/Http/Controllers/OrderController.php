<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeCheckout;

class OrderController extends Controller
{
    /**
     * Order confirmation page. The URL contains the random order number
     * (e.g. /order/AQ-7K3M9PQX), which acts as the guest's receipt link.
     */
    public function show(Order $order)
    {
        $order->load('items', 'addons', 'payments');

        return view('order-confirmation', [
            'order' => $order,
            'cardEnabled' => app(StripeCheckout::class)->cardEnabled(),
        ]);
    }
}
