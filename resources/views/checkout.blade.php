<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — The Aquila</title>

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
            <span class="eyebrow">Almost there</span>
            <h1 class="section-heading mb-4">Checkout</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-5">
                <div class="col-lg-7">
                    <form method="POST" action="{{ route('checkout.store') }}" novalidate>
                        @csrf

                        {{-- Honeypot: hidden from people, tempting to bots --}}
                        <div style="position:absolute;left:-9999px;" aria-hidden="true">
                            <label for="hp_field">Leave this field empty</label>
                            <input type="text" id="hp_field" name="hp_field" tabindex="-1" autocomplete="off">
                        </div>

                        <h2 class="h5 mb-3">Your details</h2>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="customer_name" class="form-label">Name</label>
                                <input type="text" id="customer_name" name="customer_name"
                                       value="{{ old('customer_name', $user->name ?? '') }}"
                                       class="form-control @error('customer_name') is-invalid @enderror" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" id="customer_email" name="customer_email"
                                       value="{{ old('customer_email', $user->email ?? '') }}"
                                       class="form-control @error('customer_email') is-invalid @enderror" required>
                                @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Phone</label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                       value="{{ old('customer_phone', $user->phone ?? '') }}"
                                       class="form-control @error('customer_phone') is-invalid @enderror" required>
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <h2 class="h5 mb-3">Pickup time</h2>
                        @if (empty($slots))
                            <div class="alert alert-warning">
                                There are no pickup times available right now. Please try again during opening hours, or call us.
                            </div>
                        @else
                            <div class="mb-4">
                                <label for="pickup_time" class="form-label">When will you collect?</label>
                                <select id="pickup_time" name="pickup_time"
                                        class="form-select @error('pickup_time') is-invalid @enderror" required>
                                    <option value="">Choose a time</option>
                                    @foreach ($slots as $dayLabel => $times)
                                        <optgroup label="{{ $dayLabel }}">
                                            @foreach ($times as $value => $label)
                                                <option value="{{ $value }}" {{ old('pickup_time') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('pickup_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif

                        @if ($addons->isNotEmpty())
                            <h2 class="h5 mb-3">Add-ons <span class="text-muted fs-6 fw-normal">(optional)</span></h2>
                            <div class="mb-4">
                                @foreach ($addons as $addon)
                                    <div class="d-flex justify-content-between align-items-center gap-3 py-2 border-bottom">
                                        <label for="addon-{{ $addon->id }}" class="flex-grow-1 mb-0">
                                            <strong>{{ $addon->name }}</strong>
                                            <span class="text-muted">&middot; {{ $addon->display_price }}</span>
                                            @if ($addon->description)
                                                <span class="d-block small text-muted">{{ $addon->description }}</span>
                                            @endif
                                        </label>
                                        <input type="number"
                                               id="addon-{{ $addon->id }}"
                                               name="addons[{{ $addon->id }}]"
                                               value="{{ old('addons.' . $addon->id, 0) }}"
                                               min="0" max="{{ \App\Models\Addon::MAX_QUANTITY }}"
                                               data-price="{{ $addon->price }}"
                                               class="form-control form-control-sm addon-qty @error('addons.' . $addon->id) is-invalid @enderror"
                                               style="width:80px;"
                                               aria-label="Quantity of {{ $addon->name }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes for the kitchen (optional)</label>
                            <textarea id="notes" name="notes" rows="3" maxlength="500"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Allergies, special requests...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <h2 class="h5 mb-3">Payment</h2>
                        @php $selectedMethod = old('payment_method', $cardEnabled ? 'card' : 'pickup'); @endphp

                        @if ($cardEnabled)
                            <div class="mb-4" role="radiogroup" aria-label="Payment method">
                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" id="pay_card" value="card" {{ $selectedMethod === 'card' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pay_card">
                                        <strong>Pay now by card</strong>
                                        <span class="d-block small text-muted">You'll be taken to Stripe's secure page to pay. We never see or store your card details.</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" id="pay_pickup" value="pickup" {{ $selectedMethod === 'pickup' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pay_pickup">
                                        <strong>Pay at pickup</strong>
                                        <span class="d-block small text-muted">Pay when you collect your order at the venue.</span>
                                    </label>
                                </div>
                                @error('payment_method')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            </div>
                        @else
                            <input type="hidden" name="payment_method" value="pickup">
                            <p class="mb-4">Pay when you collect your order at the venue.</p>
                        @endif

                        <button type="submit" id="placeOrderBtn" class="btn btn-aquila-gold" {{ empty($slots) ? 'disabled' : '' }}>
                            {{ $selectedMethod === 'card' ? 'Continue to payment' : 'Place order' }}
                        </button>
                        <a href="{{ route('cart') }}" class="btn btn-link">Back to cart</a>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="feature-card">
                        <h3>Your order</h3>
                        <ul class="list-unstyled mb-3">
                            @foreach ($lines as $line)
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span>{{ $line['quantity'] }} &times; {{ $line['item']->name }}</span>
                                    <span>${{ number_format($line['line_total'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @php
                            $initialAddons = round($addons->sum(fn ($a) => min(\App\Models\Addon::MAX_QUANTITY, max(0, (int) old('addons.' . $a->id, 0))) * (float) $a->price), 2);
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 {{ $initialAddons > 0 ? '' : 'd-none' }}" id="addonsRow">
                            <span>Add-ons</span>
                            <span id="addonsTotal">${{ number_format($initialAddons, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span id="grandTotal" data-base="{{ $subtotal }}">${{ number_format($subtotal + $initialAddons, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var inputs = document.querySelectorAll('.addon-qty');
            var row = document.getElementById('addonsRow');
            var addonsTotal = document.getElementById('addonsTotal');
            var grand = document.getElementById('grandTotal');
            if (!inputs.length || !grand) { return; }
            var base = parseFloat(grand.getAttribute('data-base')) || 0;

            function recalc() {
                var sum = 0;
                inputs.forEach(function (input) {
                    var qty = Math.max(0, Math.min(parseInt(input.max, 10) || 10, parseInt(input.value, 10) || 0));
                    sum += qty * (parseFloat(input.getAttribute('data-price')) || 0);
                });
                row.classList.toggle('d-none', !(sum > 0));
                addonsTotal.textContent = '$' + sum.toFixed(2);
                grand.textContent = '$' + (base + sum).toFixed(2);
            }

            inputs.forEach(function (input) { input.addEventListener('input', recalc); });
            recalc();
        })();
    </script>
    <script>
        (function () {
            var btn = document.getElementById('placeOrderBtn');
            var radios = document.querySelectorAll('input[name="payment_method"][type="radio"]');
            if (!btn || !radios.length) { return; }
            radios.forEach(function (r) {
                r.addEventListener('change', function () {
                    btn.textContent = r.value === 'card' ? 'Continue to payment' : 'Place order';
                });
            });
        })();
    </script>
</body>
</html>
