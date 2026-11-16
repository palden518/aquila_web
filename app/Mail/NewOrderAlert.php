<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Alert email sent to the restaurant when a new order comes in. */
class NewOrderAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function build()
    {
        return $this->subject('New online order ' . $this->order->order_number . ' — ' . $this->order->customer_name)
            ->markdown('emails.order-alert');
    }
}
