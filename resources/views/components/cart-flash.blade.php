@if (session('cart_status') || session('cart_error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080;" id="cartFlashWrap">
        <div class="alert {{ session('cart_error') ? 'alert-danger' : 'alert-success' }} shadow mb-0 d-flex align-items-center gap-3" role="alert">
            <span>{{ session('cart_error') ?: session('cart_status') }}</span>
            @if (session('cart_status') && ! request()->routeIs('cart', 'checkout'))
                <a href="{{ route('cart') }}" class="alert-link text-nowrap">View cart</a>
            @endif
            <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('cartFlashWrap').remove()"></button>
        </div>
    </div>
    <script>
        setTimeout(function () {
            var el = document.getElementById('cartFlashWrap');
            if (el) { el.remove(); }
        }, 6000);
    </script>
@endif
