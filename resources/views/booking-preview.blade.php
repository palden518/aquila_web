@extends('layouts.aquila')

@section('title', 'Book a table — The Aquila')

@section('content')

    {{--
        PREVIEW HARNESS — not the real Contact page.

        "Book a table" is a SECTION of Palden's Contact page. This route exists only so the
        reservation component can be demoed and tested before the Contact page is merged.

        When Contact is ready, Palden drops these two lines into contact.blade.php:
            <x-reservation-form />
            <x-catering-enquiry-form />
        …and this file can be deleted along with its route.
    --}}

    <section class="section-padded aq-section-indigo">
        <div class="container">
            <span class="eyebrow on-dark">Contact and reservations</span>
            <h2 class="section-heading aq-heading-on-dark mb-2">
                Book a <span class="text-gold-italic">table</span>
            </h2>
            <p class="aq-copy-on-dark mb-4">
                Reservations, catering enquiries, and general contact — we're here seven days a week.
            </p>

            <div class="aq-preview-note">
                Preview only — these components belong on the Contact page.
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <x-reservation-form />
                </div>
                <div class="col-lg-6">
                    <x-catering-enquiry-form
                        subheading="Office lunches, corporate functions, and catering delivery."
                        event-type="Catering — office" />
                </div>
            </div>
        </div>
    </section>

@endsection
