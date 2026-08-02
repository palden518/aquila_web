<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specials & Packages — The Aquila</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Cagliostro&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Page styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    {{-- ============================= NAVBAR ============================= --}}
    <nav class="navbar navbar-expand-lg aquila-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.webp') }}" alt="The Aquila logo">
                AQUILA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#aquilaNav" aria-controls="aquilaNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon-custom">
                    <span></span><span></span><span></span>
                </span>
            </button>

            <div class="collapse navbar-collapse" id="aquilaNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">Menu</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('specials') ? 'active' : '' }}" href="{{ route('specials') }}">Specials &amp; packages</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}" href="{{ route('events') }}">Events &amp; catering</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                </ul>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>

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
                        <span class="aq-photo-icon" aria-hidden="true">&#9634;</span>
                        <span>Photo space — weekly dish or private dining setup</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ============================= WEEKLY SPECIAL ============================= --}}
    @if ($weeklySpecial)
        <section class="section-padded pt-0">
            <div class="container">
                <span class="eyebrow">This week</span>
                <h2 class="section-heading mb-4">Weekly <span class="text-gold-italic">special</span></h2>

                <div class="special-card">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="special-image"
                                 style="background-image:url('{{ $weeklySpecial->image ?: 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=900&q=80' }}');"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="special-body">
                                <span class="badge-week">This week only</span>
                                <h3>{{ $weeklySpecial->name }}</h3>
                                <p>{{ $weeklySpecial->description }}</p>
                                <div class="special-price">{{ $weeklySpecial->display_price }} <span>per serve</span></div>

                                <a href="{{ route('menu') }}" class="aq-link-arrow">View full menu &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================= PACKAGES ============================= --}}
    <section class="section-padded aq-section-cream" id="packages">
        <div class="container">
            <span class="eyebrow">For groups and businesses</span>
            <h2 class="section-heading">Our <span class="text-gold-italic">packages</span></h2>
            <p class="section-lede">
                Packages designed for office gatherings, celebrations, and corporate functions.
                Pricing available on enquiry.
            </p>

            <div class="row g-4 mt-2">
                @forelse ($packages as $package)
                    <div class="col-lg-4 col-md-6">
                        <div class="aq-package-card {{ $package->is_popular ? 'is-popular' : '' }}">
                            @if ($package->is_popular)
                                <span class="aq-ribbon">Most popular</span>
                            @endif

                            <h3>{{ $package->name }}</h3>
                            @if ($package->subtitle)
                                <p class="aq-package-sub">{{ $package->subtitle }}</p>
                            @endif

                            @if ($package->features)
                                <ul class="aq-package-list">
                                    @foreach ($package->features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <p class="aq-package-price">{{ $package->display_price }}</p>

                            <a href="#enquiry" class="btn aq-btn-package {{ $package->is_popular ? 'is-filled' : '' }}">
                                Enquire about this package
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No packages available right now — please check back soon.</p>
                @endforelse
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
                    <x-catering-enquiry-form theme="dark" />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= FOOTER ============================= --}}
    <footer class="aquila-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h4>AQUILA</h4>
                    <p>Fine dining, takeaway, and catering in Brisbane City — Italian and Australian cuisine, open 7 days.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12Z"/></svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2.7 0 3.1 0 4.1.1 1 .1 1.7.2 2.3.5.6.2 1.1.6 1.6 1.1.5.5.8.9 1.1 1.6.3.6.4 1.3.5 2.3.1 1 .1 1.4.1 4.1s0 3.1-.1 4.1c-.1 1-.2 1.7-.5 2.3-.2.6-.6 1.1-1.1 1.6-.5.5-.9.8-1.6 1.1-.6.3-1.3.4-2.3.5-1 .1-1.4.1-4.1.1s-3.1 0-4.1-.1c-1-.1-1.7-.2-2.3-.5-.6-.2-1.1-.6-1.6-1.1-.5-.5-.8-.9-1.1-1.6-.3-.6-.4-1.3-.5-2.3C2 15.1 2 14.7 2 12s0-3.1.1-4.1c.1-1 .2-1.7.5-2.3.2-.6.6-1.1 1.1-1.6.5-.5.9-.8 1.6-1.1.6-.3 1.3-.4 2.3-.5C8.9 2 9.3 2 12 2Zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8.2A3.2 3.2 0 1 1 12 8.8a3.2 3.2 0 0 1 0 6.4ZM17.4 6.6a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-6">
                    <div class="footer-col-title">Explore</div>
                    <ul>
                        <li><a href="{{ route('menu') }}">Menu</a></li>
                        <li><a href="{{ route('specials') }}">Specials and packages</a></li>
                        <li><a href="{{ route('events') }}">Events and catering</a></li>
                        <li><a href="{{ route('home') }}#about">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <div class="footer-col-title">Visit us</div>
                    <ul>
                        <li>Brisbane City, Queensland</li>
                        <li>Open 7 days</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-col-title">Legal</div>
                    <ul>
                        <li><a href="#">Privacy policy</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Allergen information</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-md-between">
                <span>{{ date('Y') }} The Aquila. All rights reserved.</span>
                <span class="text-md-end">Brisbane City, Queensland, Australia</span>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
