<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Order {{ $order->order_number }} — The Aquila</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Cagliostro&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <x-cart-flash />

    @include('partials.site-navbar')

    <section class="section-padded">
        <div class="container">
            @php
                $state = $order->paymentState();
                $unpaid = in_array($state, ['awaiting', 'failed'], true);
                $expired = $order->status === 'cancelled' && $unpaid;
            @endphp

            @if (session('payment_success'))
                <div class="alert alert-success">{{ session('payment_success') }}</div>
            @endif
            @if (session('payment_notice'))
                <div class="alert alert-info">{{ session('payment_notice') }}</div>
            @endif
            @if (session('payment_error'))
                <div class="alert alert-danger">{{ session('payment_error') }}</div>
            @endif
            @if (request('payment') === 'cancelled' && $unpaid && ! $expired)
                <div class="alert alert-warning">Payment wasn't completed. Your order isn't confirmed until it's paid, but you can try again below.</div>
            @endif

            <span class="eyebrow">{{ $unpaid ? 'Payment needed' : 'Order received' }}</span>
            <h1 class="section-heading mb-2">Thank you, <span class="text-gold-italic">{{ $order->customer_name }}</span></h1>
            <p class="section-lede">
                Your order number is <strong>{{ $order->order_number }}</strong>.
                @if ($expired)
                    This order expired before payment was received. Please place a new order from the menu.
                @elseif ($unpaid)
                    We'll start preparing it as soon as your card payment is received.
                @else
                    A confirmation has been sent to {{ $order->customer_email }}.
                @endif
            </p>
             <a href="{{ route('menu') }}" class="btn btn-aquila-gold">Back to the menu</a>

            <div class="row g-4 mt-1">
                <div class="col-lg-7">
                    <div class="feature-card">
                        <h3>Items</h3>
                        <ul class="list-unstyled mb-3">
                            @foreach ($order->items as $item)
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>{{ $item->quantity }} &times; {{ $item->item_name }}</span>
                                    <span>${{ number_format($item->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                            @foreach ($order->addons as $addon)
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>{{ $addon->quantity }} &times; {{ $addon->addon_name }} <span class="small text-muted">(add-on)</span></span>
                                    <span>{{ (float) $addon->subtotal > 0 ? '$' . number_format($addon->subtotal, 2) : 'Free' }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                       
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="feature-card">
                        <h3>Pickup details</h3>
                        <p class="mb-2"><strong>When:</strong> {{ $order->pickup_time->format('l j F, g:i A') }}</p>
                        <p class="mb-2"><strong>Where:</strong> G/82 Eagle St, Brisbane City QLD 4000</p>
                        <p class="mb-2"><strong>Payment:</strong> {{ $order->paymentLabel() }}</p>
                        <p class="mb-2">
                            <strong>Status:</strong>
                            <span class="badge" style="background:#b6892f;color:#14161a;">{{ ucfirst($order->status) }}</span>
                        </p>
                        @if ($order->notes)
                            <p class="mb-0"><strong>Your notes:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>

                    @if ($unpaid && ! $expired && $cardEnabled)
                        <form method="POST" action="{{ route('orders.pay', ['order' => $order->order_number]) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-aquila-gold w-100">Pay ${{ number_format($order->total, 2) }} by card</button>
                        </form>
                    @endif

                    <p class="small text-muted mt-3">
                        Need to change something? Call us and quote your order number.
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
