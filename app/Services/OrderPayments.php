<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * All changes to a card payment's state go through here, so the browser
 * return page and Stripe's webhook can both call it safely. Stripe may send
 * the same webhook more than once; marking an order paid is idempotent.
 */
class OrderPayments
{
    public function __construct(protected OrderNotifier $notifier)
    {
    }

    /** Creates the pending card payment row for a new card order (or resets it for a retry). */
    public function startCardPayment(Order $order): Payment
    {
        $payment = $order->payments()->where('method', 'card')->latest('id')->first();

        if ($payment) {
            $payment->update(['status' => 'pending', 'paid_at' => null]);

            return $payment;
        }

        return $order->payments()->create([
            'amount' => $order->total,
            'method' => 'card',
            'status' => 'pending',
        ]);
    }

    /**
     * Marks the order's card payment as paid and sends the order emails.
     * Returns true only the first time; repeat calls change nothing.
     */
    public function markPaid(Order $order, ?string $paymentIntentId): bool
    {
        $paidNow = DB::transaction(function () use ($order, $paymentIntentId) {
            $payment = $order->payments()->where('method', 'card')->lockForUpdate()->latest('id')->first();

            if (! $payment || $payment->status === 'paid') {
                return false;
            }

            $payment->update([
                'status' => 'paid',
                'transaction_ref' => $paymentIntentId ?: $payment->transaction_ref,
                'paid_at' => now(),
            ]);

            // A payment that lands after the order was auto-cancelled (expired
            // session racing a late payment) must not be lost: revive the order.
            if ($order->status === 'cancelled') {
                Log::warning("Order {$order->order_number} was cancelled but payment arrived; reopening it.");
                $order->update(['status' => 'pending']);
            }

            return true;
        });

        if ($paidNow) {
            $order->refresh()->load('items', 'addons', 'payments');
            $this->notifier->placed($order);
        }

        return $paidNow;
    }

    /** A card attempt failed or its Stripe session expired. Only acts on still-pending payments. */
    public function markFailed(Order $order, bool $cancelOrder = false): void
    {
        DB::transaction(function () use ($order, $cancelOrder) {
            $payment = $order->payments()->where('method', 'card')->lockForUpdate()->latest('id')->first();

            if (! $payment || $payment->status !== 'pending') {
                return;
            }

            $payment->update(['status' => 'failed']);

            if ($cancelOrder && $order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
            }
        });
    }
}
