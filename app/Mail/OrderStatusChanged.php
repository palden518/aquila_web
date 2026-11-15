<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Sent to the customer when staff mark their order as ready or cancelled. */
class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function build()
    {
        $this->order->loadMissing('payments');

        $subject = $this->order->status === 'ready'
            ? 'Your Aquila order is ready for pickup'
            : 'Update on your Aquila order ' . $this->order->order_number;

        return $this->subject($subject)->markdown('emails.order-status');
    }
}
