<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Aquila — Fine Dining & Catering in Brisbane City</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Cagliostro&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS (remove this line if Bootstrap is already compiled into your app bundle) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Page styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <x-cart-flash />

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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    <li class="nav-item"><x-cart-link /></li>
                </ul>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>

    {{-- ============================= HERO ============================= --}}
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-5">
                    <span class="eyebrow">Brisbane City — Est. 20+ years</span>
                    <h1 class="hero-heading">{!! $content['hero_heading'] !!}</h1>
                    <p class="hero-copy">{{ $content['hero_copy'] }}</p>
                    <div class="hero-actions">
                        <a href="{{ route('menu') }}" class="btn btn-aquila-gold">View our menu</a>
                        <a href="#reserve" class="btn btn-aquila-outline">Make a reservation</a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="hero-gallery-main">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&q=80" alt="The Aquila dining room">
                    </div>
                    <div class="row hero-gallery-thumbs g-3">
                        <div class="col-4">
                            <img src="https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=400&q=80" alt="Pasta dish at The Aquila">
                        </div>
                        <div class="col-4">
                            <img src="https://images.unsplash.com/photo-1481931098730-318b6f776db0?w=400&q=80" alt="Fresh bread and antipasto">
                        </div>
                        <div class="col-4">
                            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&q=80" alt="The Aquila private table">
                        </div>
                    </div>
                    <p class="hero-gallery-caption">The Aquila — Brisbane City</p>
                </div>
            </div>
        </div>
    </header>

    {{-- ============================= INFO STRIP ============================= --}}
    <div class="info-strip">
        <div class="container">
            <div class="row gy-2">
                <div class="col-md-3 col-6">
                    <div class="info-item"><span>Open 7 days — dining, bar and events</span></div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-item"><span class="dot">•</span><span>Brisbane City</span></div>
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

    {{-- ============================= WHY AQUILA ============================= --}}
    <section class="section-padded" id="about">
        <div class="container">
            <span class="eyebrow">Why Aquila</span>
            <h2 class="section-heading">Trusted by Brisbane for <span class="text-gold-italic">two decades</span></h2>
            <p class="section-lede">{{ $content['about_lede'] }}</p>

            <div class="row g-4 mt-2">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card" style="text-align:center;">
                        <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 2v6a2 2 0 0 0 2 2v12"></path>
                                <path d="M7 2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2"></path>
                                <path d="M17 2a5 5 0 0 0-5 5v15"></path>
                                <path d="M17 2v8"></path>
                            </svg>
                        </div>
                        <h3>Italian and Australian</h3>
                        <p>A unique menu blending Italian classics with modern Australian dining.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card" style="text-align:center;">
                        <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <h3>Business friendly</h3>
                        <p>A trusted spot for meetings, client lunches, and the weekday office crowd.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card" style="text-align:center;">
                        <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <path d="M16 2v4"></path>
                                <path d="M8 2v4"></path>
                                <path d="M3 10h18"></path>
                                <path d="M8 14h.01"></path>
                                <path d="M12 14h.01"></path>
                                <path d="M16 14h.01"></path>
                                <path d="M8 18h.01"></path>
                                <path d="M12 18h.01"></path>
                            </svg>
                        </div>
                        <h3>Corporate events</h3>
                        <p>Functions and corporate events tailored to your business needs.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card" style="text-align:center;">
                        <div class="feature-icon" style="display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3>Catering</h3>
                        <p>Catering for offices and functions, with confirmation by SMS and email.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= WEEKLY SPECIAL ============================= --}}
    @if ($weeklySpecial)
        <section class="section-padded pt-0" id="specials">
            <div class="container">
                <span class="eyebrow">This week</span>
                <h2 class="section-heading mb-4">Weekly <span class="text-gold-italic">special</span></h2>

                <div class="special-card">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="special-image" style="background-image:url('{{ $weeklySpecial->image ?: 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=900&q=80' }}');"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="special-body">
                                <span class="badge-week">This week only</span>
                                <h3>{{ $weeklySpecial->name }}</h3>
                                <p>{{ $weeklySpecial->description }}</p>
                                <div class="special-price">{{ $weeklySpecial->display_price }} <span>per serve</span></div>
                                <form method="POST" action="{{ route('cart.add') }}" class="mt-3 mb-2">
                                    @csrf
                                    <input type="hidden" name="menu_item_id" value="{{ $weeklySpecial->id }}">
                                    <button type="submit" class="btn btn-aquila-gold">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================= A SPACE FOR EVERY OCCASION ============================= --}}
    <section class="section-padded" id="events">
        <div class="container">
            <h2 class="section-heading mb-4">A space for <span class="text-gold-italic">every occasion</span></h2>

            <div class="occasion-grid">
                <div class="occasion-tile tile-dining" style="background-image:url('https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?w=900&q=80');">
                    <span class="tile-label">Dining room</span>
                </div>
                <div class="occasion-tile tile-bar" style="background-image:url('https://images.unsplash.com/photo-1470337458703-46ad1756a187?w=700&q=80');">
                    <span class="tile-label">Bar service</span>
                </div>
                <div class="occasion-tile tile-kitchen" style="background-image:url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=700&q=80');">
                    <span class="tile-label">Kitchen</span>
                </div>
                <div class="occasion-tile tile-functions" style="background-image:url('https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=700&q=80');">
                    <span class="tile-label">Functions</span>
                </div>
                <div class="occasion-tile tile-private" style="background-image:url('https://images.unsplash.com/photo-1544148103-0773bf10d330?w=700&q=80');">
                    <span class="tile-label">Private dining</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= TESTIMONIALS ============================= --}}
    <section class="testimonial-section">
        <div class="container">
            <span class="eyebrow on-dark">What our guests say</span>
            <h2 class="section-heading mb-5">Loved by Brisbane's <span class="text-gold-italic">business community</span></h2>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <p>Our go-to for client lunches. The team always look after us, and the menu never disappoints.</p>
                        <p class="attribution">Local solicitor</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <p>Catering for our office event was seamless — the confirmation and updates by SMS made it easy to plan.</p>
                        <p class="attribution">Corporate client</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <p>Twenty years and still the best Italian and Australian fusion in the city. A true Brisbane institution.</p>
                        <p class="attribution">Long-time regular</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= PLAN YOUR VISIT / CTA ============================= --}}
    <section class="cta-section" id="reserve">
        <div class="container">
            <span class="eyebrow">— Plan your visit —</span>
            <h2 class="cta-heading">Reserve your table or enquire about <span class="text-gold-italic">catering</span></h2>
            <p class="cta-copy">Open 7 days for dining, bar service, and events. Reach out for reservations, catering, or corporate functions.</p>
            <a href="#contact" class="btn btn-aquila-gold">Get in touch</a>
        </div>
    </section>

    {{-- ============================= FOOTER ============================= --}}
    <footer class="aquila-footer" id="contact">
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
                        <li><a href="#events">Events and catering</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <div class="footer-col-title">Visit us</div>
                    <ul>
                        <li>Brisbane City, Queensland</li>
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

    {{-- Bootstrap JS bundle (remove if already included in your app layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
