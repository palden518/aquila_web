<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Confirmation email sent to the customer after they place an order. */
class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function build()
    {
        return $this->subject('Your Aquila order ' . $this->order->order_number)
            ->markdown('emails.order-placed');
    }
}
