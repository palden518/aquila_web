<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Sent to the restaurant's admin email when a customer submits
 * a new table reservation, so staff can confirm or cancel it.
 */
class ReservationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation)
    {
    }

    public function build()
    {
        return $this->subject('New table reservation — ' . $this->reservation->name)
            ->replyTo($this->reservation->email, $this->reservation->name)
            ->markdown('emails.reservation-received', [
                'date' => $this->reservation->reservation_date->format('l j F Y'),
                'time' => Carbon::parse($this->reservation->reservation_time)->format('g:i A'),
            ]);
    }
}
