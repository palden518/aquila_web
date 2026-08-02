@extends('layouts.aquila')

@section('title', 'Specials & Packages — The Aquila')

@section('content')

    {{-- ============================= HERO ============================= --}}
    <header class="section-padded aq-hero-specials">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <span class="eyebrow">Specials and packages</span>
                    <h1 class="section-heading aq-hero-heading">
                        Weekly specials and <span class="text-gold-italic">packages</span>
                    </h1>
                    <p class="section-lede">
                        Rotating dishes and curated packages for groups, meetings, and celebrations.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="aq-photo-placeholder">
                        {{-- TODO (team): replace with a real photo once shots are supplied --}}
                        <span class="aq-photo-icon" aria-hidden="true">&#9634;</span>
                        <span>Photo space — weekly dish or private dining setup</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ============================= WEEKLY SPECIAL ============================= --}}
    <section class="section-padded pt-0">
        <div class="container">
            <span class="eyebrow">This week</span>
            <h2 class="section-heading mb-4">Weekly <span class="text-gold-italic">special</span></h2>

            <div class="special-card">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="special-image"
                             style="background-image:url('https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=900&q=80');"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="special-body">
                            <span class="badge-week">This week only</span>
                            <h3>Chef's seasonal risotto</h3>
                            <p>
                                Updated weekly to showcase the best seasonal produce. Ask our team about
                                this week's selection — available dine-in and takeaway.
                            </p>
                            <div class="special-price">$28 <span>per serve</span></div>

                            {{-- Fix: this card previously dead-ended with no next action --}}
                            <a href="#" class="aq-link-arrow">View full menu &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= PACKAGES ============================= --}}
    <section class="section-padded aq-section-cream" id="packages">
        <div class="container">
            <span class="eyebrow">For groups and businesses</span>
            <h2 class="section-heading">Our <span class="text-gold-italic">packages</span></h2>
            <p class="section-lede">
                Packages designed for office gatherings, celebrations, and corporate functions.
                Pricing available on enquiry.
            </p>

            @php
                $packages = [
                    [
                        'name'     => 'Office lunch package',
                        'sub'      => 'Ideal for team lunches and meetings',
                        'popular'  => false,
                        'features' => [
                            'Set menu with two main course options',
                            'Shared antipasto starter',
                            'Tea and coffee included',
                            'Minimum group size applies',
                        ],
                    ],
                    [
                        'name'     => 'Corporate event package',
                        'sub'      => 'For functions and celebrations',
                        'popular'  => true,
                        'features' => [
                            'Private dining area',
                            'Choice of set menu or canapés',
                            'Bar service available',
                            'Dedicated event coordination',
                        ],
                    ],
                    [
                        'name'     => 'Catering package',
                        'sub'      => 'Delivered or collected for your event',
                        'popular'  => false,
                        'features' => [
                            'Selection of platters and shared dishes',
                            'Suitable for offices and private events',
                            'SMS and email confirmation on booking',
                            'Notification once your request is accepted',
                        ],
                    ],
                ];
            @endphp

            <div class="row g-4 mt-2">
                @foreach ($packages as $package)
                    <div class="col-lg-4 col-md-6">
                        <div class="aq-package-card {{ $package['popular'] ? 'is-popular' : '' }}">
                            @if ($package['popular'])
                                <span class="aq-ribbon">Most popular</span>
                            @endif

                            <h3>{{ $package['name'] }}</h3>
                            <p class="aq-package-sub">{{ $package['sub'] }}</p>

                            <ul class="aq-package-list">
                                @foreach ($package['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>

                            <p class="aq-package-price">Price on enquiry</p>

                            {{-- Fix: every card now has a CTA. All three route to the shared form below. --}}
                            <a href="#enquiry" class="btn aq-btn-package {{ $package['popular'] ? 'is-filled' : '' }}">
                                Enquire about this package
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= SHARED ENQUIRY FORM ============================= --}}
    <section class="section-padded aq-section-indigo" id="enquiry">
        <div class="container">
            <span class="eyebrow on-dark">Send an enquiry</span>
            <h2 class="section-heading aq-heading-on-dark mb-4">
                Tell us about your <span class="text-gold-italic">event</span>
            </h2>

            <div class="row">
                <div class="col-lg-8">
                    {{-- Shared component — same form on Events & catering and Contact --}}
                    <x-catering-enquiry-form />
                </div>
            </div>
        </div>
    </section>

@endsection
