@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $reservationErrors = $errors->getBag('reservation');

    // AQL-62: Only restore input after a Reservation validation failure.
    $reservationOld = $reservationErrors->any();
@endphp

<div class="{{ $theme === 'dark' ? 'inquiry-panel p-4' : '' }}">
    <h3 class="h4 mb-3">{{ $heading }}</h3>

    @if ($subheading)
        <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} mb-3">
            {{ $subheading }}
        </p>
    @endif

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

    <form method="POST"
          action="{{ route('reservations.store') }}"
          data-reservation-form>
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
                <label for="reservation-date" class="{{ $labelClass }}">Date</label>

                <input
                    type="date"
                    id="reservation-date"
                    name="reservation_date"
                    value="{{ $reservationOld ? old('reservation_date') : '' }}"
                    min="{{ now()->toDateString() }}"
                    class="form-control @error('reservation_date', 'reservation') is-invalid @enderror"
                    required
                    @error('reservation_date', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-date-error"
                    @enderror
                >

                @error('reservation_date', 'reservation')
                    <div id="reservation-date-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Reservation Time --}}
            <div class="col-md-3 col-6">
                <label for="reservation-time" class="{{ $labelClass }}">Time</label>

                <input
                    type="time"
                    id="reservation-time"
                    name="reservation_time"
                    value="{{ $reservationOld ? old('reservation_time') : '' }}"
                    class="form-control @error('reservation_time', 'reservation') is-invalid @enderror"
                    required
                    @error('reservation_time', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-time-error"
                    @enderror
                >

                @error('reservation_time', 'reservation')
                    <div id="reservation-time-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Party Size --}}
            <div class="col-12">
                <label for="reservation-party-size" class="{{ $labelClass }}">Party size</label>

                <input
                    type="number"
                    id="reservation-party-size"
                    name="party_size"
                    value="{{ $reservationOld ? old('party_size') : '' }}"
                    class="form-control @error('party_size', 'reservation') is-invalid @enderror"
                    placeholder="Number of guests"
                    min="1"
                    max="50"
                    step="1"
                    required
                    @error('party_size', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-party-size-error"
                    @enderror
                >

                @error('party_size', 'reservation')
                    <div id="reservation-party-size-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Optional Notes --}}
            <div class="col-12">
                <label for="reservation-notes" class="{{ $labelClass }}">
                    Notes (optional)
                </label>

                <textarea
                    id="reservation-notes"
                    name="notes"
                    class="form-control @error('notes', 'reservation') is-invalid @enderror"
                    rows="3"
                    maxlength="2000"
                    placeholder="Dietary requirements, special occasion, seating preference..."
                    @error('notes', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-notes-error"
                    @enderror
                >{{ $reservationOld ? old('notes') : '' }}</textarea>

                @error('notes', 'reservation')
                    <div id="reservation-notes-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <button type="submit" class="btn btn-aquila-gold w-100 mt-4">
            Request a table
        </button>
    </form>
</div>
