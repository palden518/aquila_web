<?php

namespace App\Mail;

use App\Models\CateringEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CateringEnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CateringEnquiry $enquiry)
    {
    }

    public function build()
    {
        return $this->subject('New catering enquiry — ' . $this->enquiry->name)
            ->view('emails.catering-enquiry');
    }
}
