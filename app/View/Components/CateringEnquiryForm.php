<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CateringEnquiryForm extends Component
{
    public function __construct(
        public string $heading = 'Send a Catering Inquiry',
        public ?string $subheading = null,
        public ?string $eventType = null,
        public string $theme = 'light',
    ) {
    }

    public function render()
    {
        return view('components.catering-enquiry-form');
    }
}
