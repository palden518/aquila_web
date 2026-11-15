<?php

namespace App\View\Components;

use App\Models\Package;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class CateringEnquiryForm extends Component
{
    /** Active packages for the EP2 package dropdown. */
    public Collection $packages;

    public function __construct(
        public string $heading = 'Send a Catering Inquiry',
        public ?string $subheading = null,
        public ?string $eventType = null,
        public string $theme = 'light',
        public ?int $packageId = null, // optional pre-selected package
    ) {
        $this->packages = Package::active()->orderBy('price')->get();
    }

    public function render()
    {
        return view('components.catering-enquiry-form');
    }
}