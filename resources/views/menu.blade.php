<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aquila Menu</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Cagliostro&display=swap"
        rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Existing project CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>

<body class="aquila-menu-page">

    {{-- Navbar --}}
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
                    <img src="https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900&q=80"
                        alt="Breakfast food" class="img-fluid menu-hero-image">
                </div>

                <div class="col-md-4">
                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?w=900&q=80" alt="Lunch food"
                        class="img-fluid menu-hero-image">
                </div>

                <div class="col-md-4">
                    <img src="https://images.unsplash.com/photo-1579751626657-72bc17010498?w=900&q=80" alt="Pizza"
                        class="img-fluid menu-hero-image">
                </div>

            </div>

        </div>
    </header>

    {{-- Category navigation --}}
    <section class="menu-category-bar py-4 sticky-lg-top">
        <div class="container">

            <div class="d-flex flex-wrap justify-content-center gap-2">

                <a href="#breakfast" class="btn menu-category-button">
                    Breakfast
                </a>

                <a href="#lunch" class="btn menu-category-button">
                    Lunch
                </a>

                <a href="#mains" class="btn menu-category-button">
                    Mains
                </a>

                <a href="#salads" class="btn menu-category-button">
                    Salads
                </a>

                <a href="#pizza" class="btn menu-category-button">
                    Pizza
                </a>

                <a href="#sharing" class="btn menu-category-button">
                    To Share
                </a>

            </div>

        </div>
    </section>

    <main>

        {{-- Breakfast --}}
        <section id="breakfast" class="menu-section py-5">
            <div class="container">

                <div class="row align-items-end mb-4">
                    <div class="col-md-8">

                        <span class="menu-eyebrow">
                            Morning favourites
                        </span>

                        <h2 class="menu-section-title">
                            Breakfast
                        </h2>

                    </div>

                    <div class="col-md-4 text-md-end">
                        <p class="menu-section-note mb-0">
                            Available until 11:30 am
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    @php
                        $breakfastItems = [
                            [
                                'name' => 'Eggs Your Way',
                                'price' => '$18',
                                'description' => 'Two eggs served with toasted sourdough and butter.'
                            ],
                            [
                                'name' => 'Avocado Toast',
                                'price' => '$19',
                                'description' => 'Smashed avocado, tomato, herbs and toasted sourdough.'
                            ],
                            [
                                'name' => 'Breakfast Toastie',
                                'price' => '$16',
                                'description' => 'Egg, cheese and your choice of bacon or vegetables.'
                            ],
                            [
                                'name' => 'Big Breakfast',
                                'price' => '$25',
                                'description' => 'Eggs, bacon, sausage, mushrooms, tomato and toast.'
                            ],
                            [
                                'name' => 'Pancakes',
                                'price' => '$17',
                                'description' => 'Pancakes with seasonal fruit, cream and maple syrup.'
                            ],
                            [
                                'name' => 'Granola Bowl',
                                'price' => '$15',
                                'description' => 'Granola, yoghurt, fresh fruit and honey.'
                            ],
                        ];
                    @endphp

                    @foreach ($breakfastItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

        {{-- Lunch --}}
        <section id="lunch" class="menu-section py-5">
            <div class="container">

                <div class="row align-items-end mb-4">
                    <div class="col-md-8">

                        <span class="menu-eyebrow">
                            Midday dining
                        </span>

                        <h2 class="menu-section-title">
                            Lunch Specials
                        </h2>

                    </div>

                    <div class="col-md-4 text-md-end">
                        <p class="menu-section-note mb-0">
                            Served daily
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    @php
                        $lunchItems = [
                            [
                                'name' => 'Aquila Beef Burger',
                                'price' => '$24',
                                'description' => 'Beef patty, lettuce, tomato, cheese, onion and chips.'
                            ],
                            [
                                'name' => 'Salt and Pepper Squid',
                                'price' => '$23',
                                'description' => 'Crispy squid with salad, lemon and dipping sauce.'
                            ],
                            [
                                'name' => 'Chicken Parmigiana',
                                'price' => '$26',
                                'description' => 'Crumbed chicken, tomato sauce, cheese, chips and salad.'
                            ],
                            [
                                'name' => 'Fish and Chips',
                                'price' => '$25',
                                'description' => 'Battered fish, chips, garden salad and tartare sauce.'
                            ],
                            [
                                'name' => 'Steak Sandwich',
                                'price' => '$27',
                                'description' => 'Grilled steak, onion, lettuce, tomato and chips.'
                            ],
                            [
                                'name' => 'Chicken Wrap',
                                'price' => '$21',
                                'description' => 'Grilled chicken, salad, cheese and house dressing.'
                            ],
                        ];
                    @endphp

                    @foreach ($lunchItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

        {{-- Mains --}}
        <section id="mains" class="menu-section py-5">
            <div class="container">

                <span class="menu-eyebrow">
                    Evening favourites
                </span>

                <h2 class="menu-section-title mb-4">
                    Mains
                </h2>

                <div class="card featured-menu-card border-0 mb-4">

                    <div class="row g-0 align-items-center">

                        <div class="col-lg-8">
                            <div class="card-body p-4 p-lg-5">

                                <span class="featured-label">
                                    Chef's feature
                                </span>

                                <h3 class="featured-menu-title mt-2">
                                    Home Dinner
                                </h3>

                                <p class="mb-0">
                                    Ask our team about today's featured meal,
                                    prepared using fresh seasonal ingredients.
                                </p>

                            </div>
                        </div>

                        <div class="col-lg-4">

                            <img src="https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80"
                                alt="Featured dinner" class="featured-menu-image">

                        </div>

                    </div>

                </div>

                <div class="row g-3">

                    @php
                        $mainItems = [
                            [
                                'name' => 'Grilled Chicken',
                                'price' => '$28',
                                'description' => 'Grilled chicken, vegetables, potato and house sauce.'
                            ],
                            [
                                'name' => 'Grilled Salmon',
                                'price' => '$32',
                                'description' => 'Salmon fillet, seasonal vegetables and lemon butter.'
                            ],
                            [
                                'name' => 'Pasta of the Day',
                                'price' => '$26',
                                'description' => 'Fresh pasta prepared with the chef’s daily sauce.'
                            ],
                            [
                                'name' => 'Lamb Shoulder',
                                'price' => '$34',
                                'description' => 'Slow cooked lamb with vegetables and red wine sauce.'
                            ],
                        ];
                    @endphp

                    @foreach ($mainItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

        {{-- Salads --}}
        <section id="salads" class="menu-section py-5">
            <div class="container">

                <span class="menu-eyebrow">
                    Fresh and light
                </span>

                <h2 class="menu-section-title mb-4">
                    Salads
                </h2>

                <div class="row g-3">

                    @php
                        $saladItems = [
                            [
                                'name' => 'Chicken Caesar Salad',
                                'price' => '$22',
                                'description' => 'Chicken, lettuce, parmesan, croutons and Caesar dressing.'
                            ],
                            [
                                'name' => 'Mediterranean Salad',
                                'price' => '$20',
                                'description' => 'Tomato, cucumber, olives, feta and fresh herbs.'
                            ],
                            [
                                'name' => 'Pumpkin and Quinoa Salad',
                                'price' => '$21',
                                'description' => 'Roasted pumpkin, quinoa, leaves, feta and seeds.'
                            ],
                            [
                                'name' => 'Garden Salad',
                                'price' => '$16',
                                'description' => 'Mixed leaves, cucumber, tomato, onion and dressing.'
                            ],
                        ];
                    @endphp

                    @foreach ($saladItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

        {{-- Pizza --}}
        <section id="pizza" class="menu-section py-5">
            <div class="container">

                <span class="menu-eyebrow">
                    Stone-baked favourites
                </span>

                <h2 class="menu-section-title mb-4">
                    Pizza
                </h2>

                <div class="row g-3">

                    @php
                        $pizzaItems = [
                            [
                                'name' => 'Margherita',
                                'price' => '$21',
                                'description' => 'Tomato, mozzarella and fresh basil.'
                            ],
                            [
                                'name' => 'Hawaiian',
                                'price' => '$23',
                                'description' => 'Tomato, mozzarella, ham and pineapple.'
                            ],
                            [
                                'name' => 'Chicken and Bacon',
                                'price' => '$25',
                                'description' => 'Chicken, bacon, onion, mozzarella and barbecue sauce.'
                            ],
                            [
                                'name' => 'Vegetarian',
                                'price' => '$23',
                                'description' => 'Mushroom, capsicum, onion, olives and mozzarella.'
                            ],
                        ];
                    @endphp

                    @foreach ($pizzaItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

        {{-- Sharing --}}
        <section id="sharing" class="menu-section py-5">
            <div class="container">

                <span class="menu-eyebrow">
                    For the table
                </span>

                <h2 class="menu-section-title mb-4">
                    To Share
                </h2>

                <div class="row g-3">

                    @php
                        $sharingItems = [
                            [
                                'name' => 'Garlic Bread',
                                'price' => '$10',
                                'description' => 'Toasted bread with garlic butter and herbs.'
                            ],
                            [
                                'name' => 'Seasoned Wedges',
                                'price' => '$12',
                                'description' => 'Crispy potato wedges served with sour cream.'
                            ],
                            [
                                'name' => 'Antipasto Board',
                                'price' => '$28',
                                'description' => 'Selection of meats, cheeses, olives and bread.'
                            ],
                            [
                                'name' => 'Calamari Plate',
                                'price' => '$24',
                                'description' => 'Crispy calamari with lemon and house dipping sauce.'
                            ],
                        ];
                    @endphp

                    @foreach ($sharingItems as $item)

                        <div class="col-lg-6">
                            <article class="card menu-item-card h-100 border-0">

                                <div class="card-body p-4">

                                    <div class="d-flex justify-content-between gap-3">

                                        <h3 class="menu-item-title mb-2">
                                            {{ $item['name'] }}
                                        </h3>

                                        <span class="menu-item-price">
                                            {{ $item['price'] }}
                                        </span>

                                    </div>

                                    <p class="menu-item-description mb-0">
                                        {{ $item['description'] }}
                                    </p>

                                </div>

                            </article>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>

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

                    <a href="{{ route('home') }}" class="btn btn-aquila-gold">
                        Return home
                    </a>

                </div>

            </div>

        </div>
    </footer>

    {{-- Bootstrap JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>