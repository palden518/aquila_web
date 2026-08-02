<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Aquila Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <span class="admin-brand-mark">AQUILA</span>
                <span class="admin-brand-sub">Admin</span>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

                @can('manage menu')
                    <div class="admin-nav-group">Menu</div>
                    <a href="{{ route('admin.menu-categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.menu-categories.*') ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('admin.menu-items.index') }}" class="admin-nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">Menu items</a>
                @endcan

                @can('manage packages')
                    <div class="admin-nav-group">Events</div>
                    <a href="{{ route('admin.packages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">Packages</a>
                @endcan

                @can('manage catering')
                    <a href="{{ route('admin.catering-enquiries.index') }}" class="admin-nav-link {{ request()->routeIs('admin.catering-enquiries.*') ? 'active' : '' }}">Catering enquiries</a>
                    <a href="{{ route('admin.reservations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">Table reservations</a>
                    <a href="{{ route('admin.contact-messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">Contact messages</a>
                @endcan

                @can('manage settings')
                    <div class="admin-nav-group">Site</div>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Website content</a>
                @endcan

                @can('manage users')
                    <div class="admin-nav-group">Access</div>
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Admin users</a>
                @endcan
            </nav>

            <a href="{{ url('/') }}" class="admin-view-site" target="_blank" rel="noopener">View live site ↗</a>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <h1 class="admin-page-title">@yield('title', 'Dashboard')</h1>
                <div class="admin-user-menu">
                    <span class="admin-user-name">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Log out</button>
                    </form>
                </div>
            </header>

            <main class="admin-content">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
