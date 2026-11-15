<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Sent to the customer straight after they submit the reservation form,
 * to confirm we have received their request.
 */
class ReservationRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation)
    {
    }

    public function build()
    {
        return $this->subject('We have received your table request at The Aquila')
            ->replyTo(Setting::get('site_email', 'hello@aquila.net.au'))
            ->markdown('emails.reservation-request-received', [
                'date' => $this->reservation->reservation_date->format('l j F Y'),
                'time' => Carbon::parse($this->reservation->reservation_time)->format('g:i A'),
                'guests' => $this->reservation->party_size . ' ' . ($this->reservation->party_size === 1 ? 'guest' : 'guests'),
                'phone' => Setting::get('site_phone', '044 792 5110'),
                'address' => 'G/82 Eagle St, Brisbane City QLD 4000',
            ]);
    }
}
