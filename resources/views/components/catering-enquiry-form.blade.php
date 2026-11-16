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
            <label class="{{ $labelClass }}">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Your name"
                required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="{{ $labelClass }}">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                placeholder="you@example.com" required>
        </div>

        {{-- Australian phone number --}}
        <div class="mb-3">
            <label class="{{ $labelClass }}">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                placeholder="Your phone number" required>
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
            <label class="{{ $labelClass }}">Guest count</label>
            <input type="number" name="guest_count" value="{{ old('guest_count') }}" class="form-control"
                placeholder="Approximate guests" min="1" required>
        </div>

        {{-- Event type --}}
        <div class="mb-3">
            <label class="{{ $labelClass }}">Event type</label>
            @php $selectedEventType = old('event_type', $eventType); @endphp
            <select class="form-select" name="event_type">
                <option value="" {{ !$selectedEventType ? 'selected' : '' }}>Choose an option</option>
                <option value="corporate" {{ $selectedEventType === 'corporate' ? 'selected' : '' }}>Corporate</option>
                <option value="wedding" {{ $selectedEventType === 'wedding' ? 'selected' : '' }}>Wedding</option>
                <option value="private_party" {{ $selectedEventType === 'private_party' ? 'selected' : '' }}>Private party
                </option>
                <option value="off_site" {{ $selectedEventType === 'off_site' ? 'selected' : '' }}>Off-site catering
                </option>
            </select>

            @error('event_type', 'catering')
                <div id="catering-event-type-error" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- EP2: package selection. Optional; packages come from admin-managed Packages. --}}
        @if ($packages->isNotEmpty())
            @php $selectedPackage = (string) old('package_id', request('package', $packageId)); @endphp
            <div class="mb-3">
                <label for="package_id" class="{{ $labelClass }}">Package</label>
                <select class="form-select" name="package_id" id="package_id">
                    <option value="" {{ $selectedPackage === '' ? 'selected' : '' }}>Not sure yet</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}" {{ $selectedPackage === (string) $package->id ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mb-3">
            <label class="{{ $labelClass }}">Message</label>
            <textarea class="form-control" name="message" rows="3"
                placeholder="Tell us about your event">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn btn-aquila-gold w-100">
            Submit inquiry
        </button>
    </form>
</div>