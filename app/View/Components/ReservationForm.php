<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReservationForm extends Component
{
    public function __construct(
        public string $heading = 'Book a Table',
        public ?string $subheading = null,
        public string $theme = 'light',
    ) {
    }

    public function render()
    {
        return view('components.reservation-form');
    }
}
