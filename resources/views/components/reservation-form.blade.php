@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $reservationErrors = $errors->getBag('reservation');
@endphp

<div class="{{ $theme === 'dark' ? 'inquiry-panel p-4' : '' }}">
    <h3 class="h4 mb-3">{{ $heading }}</h3>

    @if ($subheading)
        <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} mb-3">{{ $subheading }}</p>
    @endif

    @if (session('reservation_success'))
        <div class="alert alert-success">{{ session('reservation_success') }}</div>
    @endif

    @if ($reservationErrors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($reservationErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="reservation-name" class="{{ $labelClass }}">Name</label>

                <input
                    type="text"
                    id="reservation-name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name', 'reservation') is-invalid @enderror"
                    placeholder="Your name"
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
            <div class="col-md-6">
                <label for="reservation-email" class="{{ $labelClass }}">Email</label>

                <input
                    type="email"
                    id="reservation-email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email', 'reservation') is-invalid @enderror"
                    placeholder="you@example.com"
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
            <div class="col-md-6">
                <label for="reservation-phone" class="{{ $labelClass }}">Phone</label>

                <input
                    type="tel"
                    id="reservation-phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="form-control @error('phone', 'reservation') is-invalid @enderror"
                    placeholder="Your phone number"
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
            <div class="col-md-3 col-6">
                <label for="reservation-date" class="{{ $labelClass }}">Date</label>

                <input
                    type="date"
                    id="reservation-date"
                    name="reservation_date"
                    value="{{ old('reservation_date') }}"
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
            <div class="col-md-3 col-6">
                <label for="reservation-time" class="{{ $labelClass }}">Time</label>

                <input
                    type="time"
                    id="reservation-time"
                    name="reservation_time"
                    value="{{ old('reservation_time') }}"
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
            <div class="col-12">
                <label for="reservation-party-size" class="{{ $labelClass }}">Party size</label>

                <input
                    type="number"
                    id="reservation-party-size"
                    name="party_size"
                    value="{{ old('party_size') }}"
                    class="form-control @error('party_size', 'reservation') is-invalid @enderror"
                    placeholder="Number of guests"
                    min="1"
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
            <div class="col-12">
                <label for="reservation-notes" class="{{ $labelClass }}">Notes (optional)</label>

                <textarea
                    id="reservation-notes"
                    name="notes"
                    class="form-control @error('notes', 'reservation') is-invalid @enderror"
                    rows="3"
                    placeholder="Dietary requirements, special occasion, seating preference..."
                    @error('notes', 'reservation')
                        aria-invalid="true"
                        aria-describedby="reservation-notes-error"
                    @enderror
                >{{ old('notes') }}</textarea>

                @error('notes', 'reservation')
                    <div id="reservation-notes-error" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-aquila-gold w-100 mt-4">Request a table</button>
    </form>
</div>
