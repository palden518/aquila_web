<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquila Events & Catering</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Cagliostro&display=swap"
        rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Project CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
</head>

<body class="event-page">

    <nav class="navbar navbar-expand-lg aquila-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.webp') }}" alt="The Aquila logo">
                AQUILA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#aquilaNav"
                aria-controls="aquilaNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon-custom">
                    <span></span><span></span><span></span>
                </span>
            </button>

            <div class="collapse navbar-collapse" id="aquilaNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}"
                            href="{{ route('menu') }}">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('specials') }}">Specials &amp; packages</a>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}"
                            href="{{ route('events') }}">Events &amp; catering</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <a href="{{ route('booking.preview') }}" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="{{ route('booking.preview') }}" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>

    <header class="event-hero text-white">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-xl-7 text-center">
                    <p class="eyebrow text-gold mb-3">Gather at altitude</p>
                    <h1 class="display-3 fw-bold mb-4">Gather at altitude,<br>on Eagle Street.</h1>
                    <p class="lead mb-4">Private events and catering in a stunning Brisbane venue with refined service,
                        flexible packages, and chef-led menus.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="#packages" class="btn btn-aquila-gold btn-lg">Browse packages</a>
                        <a href="#inquiry" class="btn btn-aquila-outline btn-lg text-white">Send a Catering Inquiry</a>
                    </div>
                </div>
            </div>
            <div class="row text-center text-white mt-5">
                <div class="col-md-4">
                    <div class="hero-stat">
                        <span class="stat-number">200</span>
                        <p class="mb-0">Standing guests</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="hero-stat">
                        <span class="stat-number">100</span>
                        <p class="mb-0">Seated guests</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="hero-stat">
                        <span class="stat-number">7–12</span>
                        <p class="mb-0">Event hours</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="event-gold-section py-5">
            <div class="container">
                <div class="row align-items-center gy-5">
                    <div class="col-lg-6">
                        <span class="eyebrow text-dark">One room, every occasion.</span>
                        <h2 class="section-title dark">One room, every occasion.</h2>
                        <p class="section-text dark">Aquila sits on busy Eagle Street in the heart of the Brisbane CBD —
                            a flexible private venue for celebrations, product launches and corporate events. The space
                            is styled for a polished experience and can be customised to suit your guest list.</p>
                        <p class="section-text dark">With premium catering, a dedicated event manager and thoughtfully
                            designed packages, Aquila delivers an elegant event that feels effortless.</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="splash-grid">
                            <div class="splash-card splash-card-large"
                                style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80');">
                            </div>
                            <div class="splash-card splash-card-small"
                                style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80');">
                            </div>
                            <div class="splash-card splash-card-small"
                                style="background-image: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=900&q=80');">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="event-dark-section py-5" id="packages">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="eyebrow text-gold">Fully inclusive</span>
                    <h2 class="section-title">Everything's already on the table.</h2>
                    <p class="section-text">Premium service, venue styling, menus and beverage packages are built into
                        our event offerings so you can book confidently.</p>
                </div>

                <div class="row g-4 row-cols-1 row-cols-md-4">
                    <div class="col">
                        <div class="feature-card">
                            <div class="feature-icon">🍾</div>
                            <h3 class="h5">Private bar</h3>
                            <p>Extended beverage service available with cocktails, wine and spirits.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="feature-card">
                            <div class="feature-icon">🍽️</div>
                            <h3 class="h5">Indoor & outdoor seating</h3>
                            <p>Flexible floor plans and lounge seating for every guest count.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="feature-card">
                            <div class="feature-icon">🎤</div>
                            <h3 class="h5">AV & fine sound</h3>
                            <p>Trusted audio, lighting and presentation technology for polished events.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="feature-card">
                            <div class="feature-icon">🪑</div>
                            <h3 class="h5">Custom venue styling</h3>
                            <p>Decor options, seating plans and mood lighting tailored for your event.</p>
                        </div>
                    </div>
                </div>

                <div class="row stats-row text-center text-white mt-5">
                    <div class="col-md-4">
                        <p class="small text-uppercase mb-1">Venue capacity</p>
                        <strong>200 guests</strong>
                    </div>
                    <div class="col-md-4">
                        <p class="small text-uppercase mb-1">Guest comfort</p>
                        <strong>100 seated</strong>
                    </div>
                    <div class="col-md-4">
                        <p class="small text-uppercase mb-1">Service hours</p>
                        <strong>7am–12am</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="event-gold-section py-5" id="menu-samples">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="eyebrow text-dark">Menus designed personally by our head chef.</span>
                    <h2 class="section-title dark">Menus designed personally by our head chef.</h2>
                    <p class="section-text dark">Aquila delivers seasonal menus with chef favourites, plated courses and
                        grazing style dining for every event.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="menu-card">
                            <div class="menu-card-image"
                                style="background-image: url('https://images.unsplash.com/photo-1521305916504-4a1121188589?auto=format&fit=crop&w=900&q=80');">
                            </div>
                            <div class="card-body">
                                <h3 class="h5">Breakfast & Brunch</h3>
                                <p>Fresh pastries, breakfast stations and full coffee service for morning events.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="menu-card">
                            <div class="menu-card-image"
                                style="background-image: url('https://images.unsplash.com/photo-1549576490-b0b4831ef60a?auto=format&fit=crop&w=900&q=80');">
                            </div>
                            <div class="card-body">
                                <h3 class="h5">Lunch & Dinner</h3>
                                <p>Seasonal mains, share plates and plated courses served with elegant presentation.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="menu-card">
                            <div class="menu-card-image"
                                style="background-image: url('https://images.unsplash.com/photo-1498654896293-37aacf113fd9?auto=format&fit=crop&w=900&q=80');">
                            </div>
                            <div class="card-body">
                                <h3 class="h5">Celebration Set</h3>
                                <p>Curated tasting menus, dessert platters and beverage pairing for milestone occasions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="event-dark-section py-5" id="inquiry">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <span class="eyebrow text-gold">Aquila's kitchen, wherever your event is.</span>
                        <h2 class="section-title">Aquila's kitchen, wherever your event is.</h2>
                        <p class="section-text">Corporate lunches, private functions and city events all receive our
                            full service. Our catering team can serve onsite or deliver premium food to your chosen
                            location.</p>
                        <ul class="list-unstyled event-checklist mt-4">
                            <li><strong>Corporate or office catering</strong> with premium pantry, beverage and service
                                options.</li>
                            <li><strong>Private celebrations</strong> with tailored menus, staff and table styling.</li>
                            <li><strong>Venue hire or off-site delivery</strong> for flexible event planning.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="inquiry-panel p-4">
                            <h3 class="h4 mb-3">Send a Catering Inquiry</h3>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label text-white">Name</label>
                                    <input type="text" class="form-control" placeholder="Your name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Email</label>
                                    <input type="email" class="form-control" placeholder="you@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Date</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Guest count</label>
                                    <input type="number" class="form-control" placeholder="Approximate guests">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Event type</label>
                                    <select class="form-select">
                                        <option selected>Choose an option</option>
                                        <option>Corporate</option>
                                        <option>Wedding</option>
                                        <option>Private party</option>
                                        <option>Off-site catering</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-aquila-gold w-100">Submit inquiry</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="event-gold-section py-5" id="fine-print">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-7">
                        <span class="eyebrow text-dark">The fine print, plainly put.</span>
                        <h2 class="section-title dark">The fine print, plainly put.</h2>
                        <p class="section-text dark">All packages are built on a minimum event spend and include
                            professional event staff, venue styling, and menu design. Final pricing is confirmed after
                            guest count and menu selection.</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="price-panel p-4">
                            <span class="eyebrow text-dark">From</span>
                            <h3 class="display-6 mb-2">$4,000</h3>
                            <p class="mb-0">Minimum event and catering investment for full-service booking.</p>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4 mt-4 text-dark">
                    <div class="col">
                        <h4 class="h6">Venue</h4>
                        <p class="small">Private hire on Eagle Street with flexible seating and refined finishes.</p>
                    </div>
                    <div class="col">
                        <h4 class="h6">Catering</h4>
                        <p class="small">Seasonal food menus for breakfast, lunch, dinner and grazing receptions.</p>
                    </div>
                    <div class="col">
                        <h4 class="h6">Conditions</h4>
                        <p class="small">Pricing varies with guest numbers, service hours and menu choices.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="event-footer py-5 text-white">
        <div class="container text-center">
            <p class="mb-2">Aquila • Events & Catering</p>
            <p class="small mb-0">A private event venue and catering service for memorable occasions in Brisbane.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>