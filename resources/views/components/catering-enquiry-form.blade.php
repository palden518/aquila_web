@php
    $labelClass = 'form-label' . ($theme === 'dark' ? ' text-white' : '');
    $cateringErrors = $errors->getBag('catering');
@endphp

<div class="{{ $theme === 'dark' ? 'inquiry-panel p-4' : '' }}">
    <h3 class="h4 mb-3 {{ $theme === 'dark' ? '' : '' }}">{{ $heading }}</h3>

    @if ($subheading)
        <p class="{{ $theme === 'dark' ? 'text-white-50' : 'text-muted' }} mb-3">{{ $subheading }}</p>
    @endif

    @if (session('enquiry_success'))
        <div class="alert alert-success">{{ session('enquiry_success') }}</div>
    @endif

    @if ($cateringErrors->any())
        <div class="alert alert-danger">
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
            <label class="{{ $labelClass }}">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Your name"
                required>
        </div>
        <div class="mb-3">
            <label class="{{ $labelClass }}">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                placeholder="you@example.com" required>
        </div>
        <div class="mb-3">
            <label class="{{ $labelClass }}">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                placeholder="Your phone number" required>
        </div>
        <div class="mb-3">
            <label class="{{ $labelClass }}">Date</label>
            <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="{{ $labelClass }}">Guest count</label>
            <input type="number" name="guest_count" value="{{ old('guest_count') }}" class="form-control"
                placeholder="Approximate guests" min="1" required>
        </div>
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
        <button type="submit" class="btn btn-aquila-gold w-100">Submit inquiry</button>
    </form>
</div>