<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderPayments;
use App\Services\StripeCheckout;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Receives Stripe's server-to-server events. This is the source of truth for
 * whether an order has been paid: it works even if the customer closes their
 * browser right after paying.
 */
class StripeWebhookController extends Controller
{
    public function __construct(
        protected StripeCheckout $stripe,
        protected OrderPayments $payments,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $event = $this->stripe->constructWebhookEvent(
                $request->getContent(),
                $request->header('Stripe-Signature')
            );
        } catch (\Throwable $e) {
            // Bad signature or malformed body: not from Stripe, or the webhook secret is wrong.
            Log::warning('Stripe webhook rejected: ' . $e->getMessage());

            return response('Invalid webhook', 400);
        }

        $session = $event->data->object ?? null;

        if (! $session || empty($session->client_reference_id)) {
            return response('Ignored', 200);
        }

        $order = Order::where('order_number', $session->client_reference_id)->first();

        if (! $order) {
            Log::warning("Stripe webhook for unknown order {$session->client_reference_id}.");

            return response('Ignored', 200);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
            case 'checkout.session.async_payment_succeeded':
                if ($session->payment_status !== 'paid') {
                    break; // e.g. a delayed payment method that hasn't cleared yet
                }

                if (! $this->stripe->sessionMatchesOrder($session, $order)) {
                    Log::error("Stripe amount/currency mismatch for order {$order->order_number}; not marking paid.");
                    break;
                }

                $this->payments->markPaid($order, $this->stripe->paymentIntentId($session));
                break;

            case 'checkout.session.async_payment_failed':
                $this->payments->markFailed($order);
                break;

            case 'checkout.session.expired':
                $this->payments->markFailed($order, cancelOrder: true);
                break;
        }

        return response('OK', 200);
    }
}
