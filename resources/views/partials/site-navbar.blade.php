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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('ordermenu') ? 'active' : '' }}" href="{{ route('ordermenu') }}">Order online</a></li>
                    <li class="nav-item"><x-cart-link /></li>
                </ul>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-none d-lg-inline-block">Book a table</a>
                <a href="{{ route('contact') }}#reserve" class="btn btn-aquila-gold d-lg-none">Book a table</a>
            </div>
        </div>
    </nav>
