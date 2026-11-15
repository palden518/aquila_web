@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $reservationErrors = $errors->getBag('reservation');
    $maxPartySize = (int) \App\Models\Setting::get('reservation_max_guests', '40');
    $openingTime = \App\Support\OpeningHours::openingTime();
    $lastBookingTime = \App\Support\OpeningHours::lastBookingTime();
@endphp

<div class="{{ $theme === 'dark' ? 'inquiry-panel p-4' : '' }}">
    <h3 class="h4 mb-3">{{ $heading }}</h3>

    @if ($subheading)
        <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} mb-3">
            {{ $subheading }}
        </p>
    @endif

    <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} small mb-3">{{ \App\Support\OpeningHours::summary() }}</p>

    @if (session('reservation_success'))
        <div class="alert alert-success" role="status">
            {{ session('reservation_success') }}
        </div>
    @endif

    @if ($reservationErrors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($reservationErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.store') }}" data-reservation-form>
        @csrf

        {{-- AQL-68: Honeypot spam protection --}}
        <div
            class="position-absolute"
            style="left: -9999px;"
            aria-hidden="true"
        >
            <label for="reservation-website">Website</label>
            <input
                type="text"
                id="reservation-website"
                name="website"
                value=""
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        <div class="row g-3">

            {{-- Name --}}
            <div class="col-md-6">
                <label for="reservation-name" class="{{ $labelClass }}">Name</label>

                <input
                    type="text"
                    id="reservation-name"
                    name="name"
                    value="{{ $reservationOld ? old('name') : '' }}"
                    class="form-control @error('name', 'reservation') is-invalid @enderror"
                    placeholder="Your name"
                    maxlength="255"
                    required
                    @error('name', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-name-error"
                    @enderror
                >

                @error('name', 'reservation')
                    <div id="reservation-name-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <label for="reservation-email" class="{{ $labelClass }}">Email</label>

                <input
                    type="email"
                    id="reservation-email"
                    name="email"
                    value="{{ $reservationOld ? old('email') : '' }}"
                    class="form-control @error('email', 'reservation') is-invalid @enderror"
                    placeholder="you@example.com"
                    maxlength="255"
                    required
                    @error('email', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-email-error"
                    @enderror
                >

                @error('email', 'reservation')
                    <div id="reservation-email-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Australian Phone Validation --}}
            <div class="col-md-6">
                <label for="reservation-phone" class="{{ $labelClass }}">Phone</label>

                <input
                    type="tel"
                    id="reservation-phone"
                    name="phone"
                    value="{{ $reservationOld ? old('phone') : '' }}"
                    class="form-control @error('phone', 'reservation') is-invalid @enderror"
                    placeholder="0412 345 678"
                    maxlength="50"
                    pattern="(?:04|\+61 ?4)(?: ?[0-9]){8}|(?:0[2378]|\+61 ?[2378])(?: ?[0-9]){8}"
                    title="Enter a valid Australian phone number, such as 0412 345 678 or +61 412 345 678."
                    required
                    @error('phone', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-phone-error"
                    @enderror
                >

                @error('phone', 'reservation')
                    <div id="reservation-phone-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Reservation Date --}}
            <div class="col-md-3 col-6">
                <label class="{{ $labelClass }}">Date</label>
                <input type="date" name="reservation_date" value="{{ old('reservation_date') }}" class="form-control" min="{{ now()->toDateString() }}" required>
            </div>

            {{-- Reservation Time --}}
            <div class="col-md-3 col-6">
                <label class="{{ $labelClass }}">Time</label>
                <input type="time" name="reservation_time" value="{{ old('reservation_time') }}" class="form-control" min="{{ $openingTime }}" max="{{ $lastBookingTime }}" required>
            </div>

            {{-- Party Size --}}
            <div class="col-12">
                <label class="{{ $labelClass }}">Party size</label>
                <input type="number" name="party_size" value="{{ old('party_size') }}" class="form-control" placeholder="Number of guests" min="1" max="{{ $maxPartySize }}" required>
            </div>

            {{-- Optional Notes --}}
            <div class="col-12">
                <label class="{{ $labelClass }}">Special requests (optional)</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Dietary requirements, special occasion, seating preference...">{{ old('notes') }}</textarea>
            </div>

        </div>

        <button type="submit" class="btn btn-aquila-gold w-100 mt-4">
            Request a table
        </button>
    </form>
</div>

<script>
    // Stop the customer clicking "Request a table" twice while the booking is being sent.
    document.querySelectorAll('[data-reservation-form]').forEach(function (form) {
        var button = form.querySelector('button[type="submit"]');
        var originalText = button.innerHTML;

        form.addEventListener('submit', function () {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending your request...';
        });

        // If the customer comes back with the browser's Back button, make the button usable again.
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    });
</script>
