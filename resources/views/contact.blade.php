<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — The Aquila, Brisbane City</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Cagliostro&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Page styles (same stylesheet as the homepage — no new CSS added) --}}
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
                <a href="#reserve" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="#reserve" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>

    {{-- ============================= PAGE HEADING ============================= --}}
    <section class="section-padded pb-0">
        <div class="container text-center">
            <span class="eyebrow">Get in touch</span>
            <h1 class="section-heading">Contact <span class="text-gold-italic">The Aquila</span></h1>
            <p class="section-lede mx-auto">Questions about dining, bookings, or catering? Send us a message or reach out directly — our team is here seven days a week.</p>
        </div>
    </section>

    {{-- ============================= INFO STRIP ============================= --}}
    <div class="info-strip">
        <div class="container">
            <div class="row gy-2">
                <div class="col-md-3 col-6">
                    <div class="info-item"><span>Open 7 days — dining, bar and events</span></div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-item"><span class="dot">•</span><span>{{ $content['address'] }}</span></div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-item"><span class="dot">•</span><span>{{ $content['phone'] }}</span></div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-item"><span class="dot">•</span><span>{{ $content['email'] }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= BOOK A TABLE / CATERING ENQUIRY ============================= --}}
    <section class="section-padded aq-section-indigo" id="reserve">
        <div class="container">
            <span class="eyebrow on-dark">Contact and reservations</span>
            <h2 class="section-heading aq-heading-on-dark mb-2">
                Book a <span class="text-gold-italic">table</span>
            </h2>
            <p class="aq-copy-on-dark mb-4">
                Reservations, catering enquiries, and general contact — we're here seven days a week.
            </p>

            <div class="row g-4">
                <div class="col-lg-6">
                    <x-reservation-form theme="dark" />
                </div>
                <div class="col-lg-6">
                    <x-catering-enquiry-form
                        theme="dark"
                        subheading="Office lunches, corporate functions, and catering delivery."
                        event-type="corporate" />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= CONTACT FORM + DETAILS ============================= --}}
    <section class="section-padded">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-6">
                    <span class="eyebrow">Send a message</span>
                    <h2 class="section-heading mb-4">We'd love to <span class="text-gold-italic">hear from you</span></h2>

                    @if (session('contact_success'))
                        <div class="alert alert-success">{{ session('contact_success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact-name" class="form-label">Name</label>
                                <input
                                type="text"
                                id="contact-name"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Your name"
                                maxlength="255"
                                required
                                @error('name')
                                    aria-invalid="true"
                                    aria-describedby="contact-name-error"
                                @enderror
                                >
                                @error('name')
                                    <div id="contact-name-error" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact-email" class="form-label">Email</label>
                                <input
                                type="email"
                                id="contact-email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="you@example.com"
                                maxlength="255"
                                required
                                @error('email')
                                    aria-invalid="true"
                                    aria-describedby="contact-email-error"
                                @enderror
                                >
                                @error('email')
                                    <div id="contact-email-error" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact-phone" class="form-label">Phone (optional)</label>
                                <input
                                type="tel"
                                id="contact-phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="0412 345 678"
                                maxlength="50"
                                @error('phone')
                                aria-invalid="true"
                                aria-describedby="contact-phone-error"
                                @enderror
                                >
                            @error('phone')
                                <div id="contact-phone-error" class="invalid-feedback">
                                     {{ $message }}
                                </div>
                            @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject (optional)</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="What's this about?">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea name="message" rows="5" class="form-control" placeholder="How can we help?" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-aquila-gold">Send message</button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6">
                    <span class="eyebrow">Visit us</span>
                    <h2 class="section-heading mb-4">Find us on <span class="text-gold-italic">Eagle Street</span></h2>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <h3>Address</h3>
                                <p>{{ $content['address'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"></path>
                                    </svg>
                                </div>
                                <h3>Phone</h3>
                                <p><a href="tel:{{ preg_replace('/\s+/', '', $content['phone']) }}">{{ $content['phone'] }}</a></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 6-10 7L2 6"></path>
                                    </svg>
                                </div>
                                <h3>Email</h3>
                                <p><a href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg>
                                </div>
                                <h3>Hours</h3>
                                <p>Open 7 days for dining, bar and events.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================= GOOGLE MAP — ABOVE THE FOOTER ============================= --}}
    <section class="section-padded pt-0">
        <div class="container">
            <span class="eyebrow">Our location</span>
            <h2 class="section-heading mb-4">{{ $content['address'] }}</h2>

            <div class="ratio ratio-21x9">
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode($content['address']) }}&output=embed"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="The Aquila location — {{ $content['address'] }}">
                </iframe>
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
                        <li>{{ $content['address'] }}</li>
                        <li>Open 7 days</li>
                        <li><a href="tel:{{ preg_replace('/\s+/', '', $content['phone']) }}">{{ $content['phone'] }}</a></li>
                        <li><a href="mailto:{{ $content['email'] }}">{{ $content['email'] }}</a></li>
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
