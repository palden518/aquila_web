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

    <form method="POST" action="{{ route('reservations.store') }}" data-reservation-form>
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="{{ $labelClass }}">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Your name" required>
            </div>
            <div class="col-md-6">
                <label class="{{ $labelClass }}">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="col-md-6">
                <label class="{{ $labelClass }}">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Your phone number" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="{{ $labelClass }}">Date</label>
                <input type="date" name="reservation_date" value="{{ old('reservation_date') }}" class="form-control" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="{{ $labelClass }}">Time</label>
                <input type="time" name="reservation_time" value="{{ old('reservation_time') }}" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="{{ $labelClass }}">Party size</label>
                <input type="number" name="party_size" value="{{ old('party_size') }}" class="form-control" placeholder="Number of guests" min="1" required>
            </div>
            <div class="col-12">
                <label class="{{ $labelClass }}">Special requests (optional)</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Dietary requirements, special occasion, seating preference...">{{ old('notes') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-aquila-gold w-100 mt-4">Request a table</button>
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