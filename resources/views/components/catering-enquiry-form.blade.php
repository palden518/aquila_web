
@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $cateringErrors = $errors->getBag('catering');
@endphp

<div class="{{ $theme === 'dark' ? 'inquiry-panel p-4' : '' }}">
    <h3 class="h4 mb-3">{{ $heading }}</h3>

    @if ($subheading)
        <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} mb-3">
            {{ $subheading }}
        </p>
    @endif

    @if (session('enquiry_success'))
        <div class="alert alert-success" role="status">
            {{ session('enquiry_success') }}
        </div>
    @endif

    @if ($cateringErrors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($cateringErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('events.enquiry.store') }}">
        @csrf

        <div class="mb-3">
            <label for="catering-name" class="{{ $labelClass }}">Name</label>

            <input
                type="text"
                id="catering-name"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name', 'catering') is-invalid @enderror"
                placeholder="Your name"
                required
                @error('name', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-name-error"
                @enderror
            >

            @error('name', 'catering')
                <div id="catering-name-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-email" class="{{ $labelClass }}">Email</label>

            <input
                type="email"
                id="catering-email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email', 'catering') is-invalid @enderror"
                placeholder="you@example.com"
                required
                @error('email', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-email-error"
                @enderror
            >

            @error('email', 'catering')
                <div id="catering-email-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-phone" class="{{ $labelClass }}">Phone</label>

            <input
                type="tel"
                id="catering-phone"
                name="phone"
                value="{{ old('phone') }}"
                class="form-control @error('phone', 'catering') is-invalid @enderror"
                placeholder="Your phone number"
                required
                @error('phone', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-phone-error"
                @enderror
            >

            @error('phone', 'catering')
                <div id="catering-phone-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-date" class="{{ $labelClass }}">Date</label>

            <input
                type="date"
                id="catering-date"
                name="event_date"
                value="{{ old('event_date') }}"
                class="form-control @error('event_date', 'catering') is-invalid @enderror"
                required
                @error('event_date', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-date-error"
                @enderror
            >

            @error('event_date', 'catering')
                <div id="catering-date-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-guest-count" class="{{ $labelClass }}">Guest count</label>

            <input
                type="number"
                id="catering-guest-count"
                name="guest_count"
                value="{{ old('guest_count') }}"
                class="form-control @error('guest_count', 'catering') is-invalid @enderror"
                placeholder="Approximate guests"
                min="1"
                required
                @error('guest_count', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-guest-count-error"
                @enderror
            >

            @error('guest_count', 'catering')
                <div id="catering-guest-count-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-event-type" class="{{ $labelClass }}">Event type</label>

            @php
                $selectedEventType = old('event_type', $eventType);
            @endphp

            <select
                id="catering-event-type"
                name="event_type"
                class="form-select @error('event_type', 'catering') is-invalid @enderror"
                @error('event_type', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-event-type-error"
                @enderror
            >
                <option value="" {{ ! $selectedEventType ? 'selected' : '' }}>
                    Choose an option
                </option>

                <option value="corporate" {{ $selectedEventType === 'corporate' ? 'selected' : '' }}>
                    Corporate
                </option>

                <option value="wedding" {{ $selectedEventType === 'wedding' ? 'selected' : '' }}>
                    Wedding
                </option>

                <option value="private_party" {{ $selectedEventType === 'private_party' ? 'selected' : '' }}>
                    Private party
                </option>

                <option value="off_site" {{ $selectedEventType === 'off_site' ? 'selected' : '' }}>
                    Off-site catering
                </option>
            </select>

            @error('event_type', 'catering')
                <div id="catering-event-type-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="catering-message" class="{{ $labelClass }}">Message</label>

            <textarea
                id="catering-message"
                name="message"
                class="form-control @error('message', 'catering') is-invalid @enderror"
                rows="3"
                placeholder="Tell us about your event"
                @error('message', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-message-error"
                @enderror
            >{{ old('message') }}</textarea>

            @error('message', 'catering')
                <div id="catering-message-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-aquila-gold w-100">
            Submit inquiry
        </button>
    </form>
</div>
