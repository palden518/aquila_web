@php $cartCount = app(\App\Services\Cart::class)->count(); @endphp

<a class="nav-link d-inline-flex align-items-center gap-2 {{ request()->routeIs('cart', 'checkout') ? 'active' : '' }}"
   href="{{ route('cart') }}"
   aria-label="Cart, {{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }}">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <span>Cart</span>
    @if ($cartCount > 0)
        <span class="badge rounded-pill" style="background:#b6892f;color:#14161a;">{{ $cartCount }}</span>
    @endif
</a>
