<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart — The Aquila</title>

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
            <span class="eyebrow">Online ordering</span>
            <h1 class="section-heading mb-4">Your <span class="text-gold-italic">cart</span></h1>

            @unless ($orderingEnabled)
                <div class="alert alert-warning">
                    Online ordering is paused right now. Please call us to place an order.
                </div>
            @endunless

            @if ($lines->isEmpty())
                <div class="feature-card" style="text-align:center;">
                    <h3>Your cart is empty</h3>
                    <p>Browse the menu and add something you like.</p>
                    <a href="{{ route('menu') }}" class="btn btn-aquila-gold">View the menu</a>
                </div>
            @else
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col" class="text-end">Price</th>
                                        <th scope="col" style="width:190px;">Quantity</th>
                                        <th scope="col" class="text-end">Total</th>
                                        <th scope="col"><span class="visually-hidden">Remove</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lines as $line)
                                        <tr>
                                            <td>
                                                <strong>{{ $line['item']->name }}</strong>
                                                @if ($line['item']->description)
                                                    <br><span class="small text-muted">{{ $line['item']->description }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">${{ number_format($line['unit_price'], 2) }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('cart.update', $line['item']) }}" class="d-flex gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label class="visually-hidden" for="qty-{{ $line['item']->id }}">Quantity for {{ $line['item']->name }}</label>
                                                    <input type="number" id="qty-{{ $line['item']->id }}" name="quantity" value="{{ $line['quantity'] }}" min="0" max="{{ \App\Services\Cart::MAX_PER_LINE }}" class="form-control form-control-sm" style="width:80px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                                </form>
                                            </td>
                                            <td class="text-end">${{ number_format($line['line_total'], 2) }}</td>
                                            <td class="text-end">
                                                <form method="POST" action="{{ route('cart.remove', $line['item']) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger" aria-label="Remove {{ $line['item']->name }}">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Remove everything from your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-muted p-0">Empty cart</button>
                        </form>
                    </div>

                    <div class="col-lg-4">
                        <div class="feature-card">
                            <h3>Order summary</h3>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items</span>
                                <span>{{ $lines->sum('quantity') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                <span>Total</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <p class="small text-muted">Pickup only. {{ $cardEnabled ? 'Pay by card now or when you collect your order.' : 'Pay when you collect your order.' }}</p>

                            @if ($orderingEnabled)
                                <a href="{{ route('checkout') }}" class="btn btn-aquila-gold w-100 mb-2">Continue to checkout</a>
                            @endif
                            <a href="{{ route('menu') }}" class="btn btn-aquila-outline w-100">Keep browsing</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
