<?php

namespace App\Services;

use App\Mail\NewOrderAlert;
use App\Mail\OrderPlaced;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the "order placed" emails. Called once an order is real: straight
 * away for pay-on-pickup orders, and only after Stripe confirms payment for
 * card orders.
 */
class OrderNotifier
{
    public function placed(Order $order): void
    {
        $order->loadMissing('items', 'addons', 'payments');

        $adminEmail = Setting::get('admin_notification_email', config('mail.admin_address', 'hello@aquila.net.au'));

        $this->send(new OrderPlaced($order), $order->customer_email, $order, "Order confirmation to customer for {$order->order_number}.");
        $this->send(new NewOrderAlert($order), $adminEmail, $order, "New order alert for {$order->order_number} ({$order->customer_name}).");
    }

    /**
     * Never blocks an order: a mail failure is logged and recorded in
     * notification_logs, but the customer still gets their confirmation page.
     */
    protected function send(Mailable $mailable, string $recipient, Order $order, string $description): void
    {
        $status = 'sent';

        try {
            Mail::to($recipient)->send($mailable);
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error("Order email failed for {$order->order_number}: " . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => Order::class,
            'notifiable_id' => $order->id,
            'channel' => 'email',
            'recipient' => $recipient,
            'message' => $description,
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
