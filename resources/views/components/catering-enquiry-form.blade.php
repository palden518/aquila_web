@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $cateringErrors = $errors->getBag('catering');

    // AQL-62: Only restore input after a Catering validation failure.
    $cateringOld = $cateringErrors->any();
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

    <form method="POST"
          action="{{ route('events.enquiry.store') }}"
          data-catering-form>
        @csrf

        {{-- AQL-68: Honeypot spam protection --}}
        <div
            class="position-absolute"
            style="left: -9999px;"
            aria-hidden="true"
        >
            <label for="catering-website">Website</label>
            <input
                type="text"
                id="catering-website"
                name="website"
                value=""
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        {{-- Name --}}
        <div class="mb-3">
            <label for="catering-name" class="{{ $labelClass }}">
                Name
            </label>

            <input
                type="text"
                id="catering-name"
                name="name"
                value="{{ $cateringOld ? old('name') : '' }}"
                class="form-control @error('name', 'catering') is-invalid @enderror"
                placeholder="Your name"
                maxlength="255"
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

        {{-- Email --}}
        <div class="mb-3">
            <label for="catering-email" class="{{ $labelClass }}">
                Email
            </label>

            <input
                type="email"
                id="catering-email"
                name="email"
                value="{{ $cateringOld ? old('email') : '' }}"
                class="form-control @error('email', 'catering') is-invalid @enderror"
                placeholder="you@example.com"
                maxlength="255"
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

        {{-- Australian phone number --}}
        <div class="mb-3">
            <label for="catering-phone" class="{{ $labelClass }}">
                Phone
            </label>

            <input
                type="tel"
                id="catering-phone"
                name="phone"
                value="{{ $cateringOld ? old('phone') : '' }}"
                class="form-control @error('phone', 'catering') is-invalid @enderror"
                placeholder="Your phone number"
                maxlength="50"
                pattern="(?:04|\+61 ?4)(?: ?[0-9]){8}|(?:0[2378]|\+61 ?[2378])(?: ?[0-9]){8}"
                title="Enter a valid Australian phone number, such as 0412 345 678 or +61 412 345 678."
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

        {{-- Event date --}}
        <div class="mb-3">
            <label for="catering-date" class="{{ $labelClass }}">
                Date
            </label>

            <input
                type="date"
                id="catering-date"
                name="event_date"
                value="{{ $cateringOld ? old('event_date') : '' }}"
                min="{{ now('Australia/Brisbane')->toDateString() }}"
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

        {{-- Guest count --}}
        <div class="mb-3">
            <label for="catering-guest-count" class="{{ $labelClass }}">
                Guest count
            </label>

            <input
                type="number"
                id="catering-guest-count"
                name="guest_count"
                value="{{ $cateringOld ? old('guest_count') : '' }}"
                class="form-control @error('guest_count', 'catering') is-invalid @enderror"
                placeholder="Approximate guests"
                min="1"
                max="1000"
                step="1"
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

        {{-- Event type --}}
        <div class="mb-3">
            <label for="catering-event-type" class="{{ $labelClass }}">
                Event type
            </label>

            @php
                // AQL-62: Preserve the selection only for Catering errors.
                $selectedEventType = $cateringOld
                    ? old('event_type', $eventType)
                    : $eventType;
            @endphp

            <select
                id="catering-event-type"
                name="event_type"
                class="form-select @error('event_type', 'catering') is-invalid @enderror"
                required
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

        {{-- Message --}}
        <div class="mb-3">
            <label for="catering-message" class="{{ $labelClass }}">
                Message
            </label>

            <textarea
                id="catering-message"
                name="message"
                class="form-control @error('message', 'catering') is-invalid @enderror"
                rows="3"
                maxlength="5000"
                placeholder="Tell us about your event"
                @error('message', 'catering')
                    aria-invalid="true"
                    aria-describedby="catering-message-error"
                @enderror
            >{{ $cateringOld ? old('message') : '' }}</textarea>

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
