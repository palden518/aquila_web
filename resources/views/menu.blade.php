<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Aquila Menu</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Cagliostro&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Existing project CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>

<body class="aquila-menu-page">
    @php
        $categoryImages = [
            'breakfast' => 'breakfast.webp',
            'lunch' => 'lunch.webp',
            'mains' => 'mains.webp',
            'salads' => 'salads.webp',
            'pizza' => 'pizza.webp',
            'sharing' => 'sharing.webp',
        ];
    @endphp

    {{-- Navbar --}}
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
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                </ul>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>


    {{-- Hero --}}
    <header class="menu-hero py-5">
        <div class="container py-lg-5">

            <div class="row justify-content-center text-center">
                <div class="col-lg-8">

                    <span class="menu-eyebrow">
                        The Aquila Brisbane
                    </span>

                    <h1 class="display-2 fw-bold mb-3">
                        Aquila Menu
                    </h1>

                    <p class="lead menu-hero-description">
                        Italian and Australian cuisine prepared with
                        fresh ingredients for breakfast, lunch and dinner.
                    </p>

                </div>
            </div>

            <div class="row g-4 mt-4">

                <div class="col-md-4">
                    <img
                        src="https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900&q=80"
                        alt="Breakfast food"
                        class="img-fluid menu-hero-image"
                    >
                </div>

                <div class="col-md-4">
                    <img
                        src="https://images.unsplash.com/photo-1547592180-85f173990554?w=900&q=80"
                        alt="Lunch food"
                        class="img-fluid menu-hero-image"
                    >
                </div>

                <div class="col-md-4">
                    <img
                        src="https://images.unsplash.com/photo-1579751626657-72bc17010498?w=900&q=80"
                        alt="Pizza"
                        class="img-fluid menu-hero-image"
                    >
                </div>

            </div>

        </div>
    </header>

    {{-- Category navigation --}}
    <section class="menu-category-bar py-4 sticky-lg-top">
        <div class="container">

            <div class="d-flex flex-wrap justify-content-center gap-2">

                @foreach ($categories as $category)
                    <a
                        href="#{{ $category->slug }}"
                        class="btn menu-category-button"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach

            </div>

        </div>
    </section>

    <main>

        @foreach ($categories as $category)

            <section
                id="{{ $category->slug }}"
                class="menu-section py-5"
            >
                <div class="container">

                    <div class="row align-items-end mb-4">
                        <div class="col-md-8">

                            <span class="menu-eyebrow">
                                {{ $category->description ?: 'Aquila favourites' }}
                            </span>

                            <h2 class="menu-section-title">
                                {{ $category->name }}
                            </h2>

                        </div>
                    </div>

                    @php
                        $categoryImage = $categoryImages[$category->slug] ?? null;
                        $categoryImagePath = $categoryImage
                            ? 'images/menu-categories/' . $categoryImage
                            : null;
                    @endphp
                    @if ($categoryImagePath && is_file(public_path($categoryImagePath)))
                        <img
                            src="{{ asset($categoryImagePath) }}"
                            alt="{{ $category->name }} dishes at Aquila"
                            class="menu-category-image"
                        >
                    @endif

                    {{-- Chef's feature card, shown once on the Mains section --}}
                    @if ($category->slug === 'mains')
                        <div class="card featured-menu-card border-0 mb-4">
                            <div class="row g-0 align-items-center">
                                <div class="{{ $featured['image'] ? 'col-lg-8' : 'col-12' }}">
                                    <div class="card-body p-4 p-lg-5">
                                        <span class="featured-label">
                                            Chef's feature
                                        </span>
                                        <h3 class="featured-menu-title mt-2">
                                            {{ $featured['title'] }}
                                        </h3>
                                        <p class="mb-0">
                                            {{ $featured['description'] }}
                                        </p>
                                    </div>
                                </div>
                                @if ($featured['image'])
                                <div class="col-lg-4">
                                    <img
                                        src="{{ $featured['image'] }}"
                                        alt="Featured dish: {{ $featured['title'] }}"
                                        class="featured-menu-image"
                                    >
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">

                        @forelse ($category->availableItems as $item)

                            <div class="col-lg-6">
                                <article class="card menu-item-card h-100 border-0">

                                    <div class="card-body p-4">

                                        <div class="d-flex justify-content-between gap-3">

                                            <h3 class="menu-item-title mb-2">
                                                {{ $item->name }}
                                            </h3>

                                            <span class="menu-item-price">
                                                {{ $item->display_price }}
                                            </span>

                                        </div>

                                        <p class="menu-item-description mb-0">
                                            {{ $item->description }}
                                        </p>

                                    </div>

                                </article>
                            </div>

                        @empty
                            <p class="text-muted">Nothing listed in this category yet.</p>
                        @endforelse

                    </div>

                </div>
            </section>

        @endforeach

    </main>

    {{-- Footer --}}
    <footer class="aquila-footer py-5">
        <div class="container">

            <div class="row align-items-center gy-3">

                <div class="col-md-6">
                    <h4 class="mb-2">AQUILA</h4>

                    <p class="mb-0">
                        Italian and Australian dining in Brisbane City.
                    </p>
                </div>

                <div class="col-md-6 text-md-end">

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-aquila-gold"
                    >
                        Return home
                    </a>

                </div>

            </div>

        </div>
    </footer>

    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>
