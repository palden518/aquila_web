
@props([
    'heading'    => 'Make a reservation',
    'subheading' => 'Dine-in bookings up to 8 guests.',
])

@php
    // TODO (team): confirm real service hours. These blocks are placeholders.
    $lunchSlots  = ['12:00 pm', '12:30 pm', '1:00 pm', '1:30 pm'];
    $dinnerSlots = ['6:00 pm', '6:30 pm', '7:00 pm', '7:30 pm'];

    // TODO (team): this should come from real availability, not a hardcoded list.
    $unavailable = ['7:30 pm'];

    // TODO (team): confirm the real maximum party size before routing to packages.
    $maxPartySize = 8;
@endphp

<div class="aq-form-card">
    <h3>{{ $heading }}</h3>
    <p class="aq-form-sub">{{ $subheading }}</p>

    @if (session('reservation_success'))
        <div class="aq-alert aq-alert-success" role="status">
            <span aria-hidden="true">&#10003;</span>
            <span>{{ session('reservation_success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.store') }}" novalidate>
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <div class="aq-field">
                    <label for="res_first">First name</label>
                    <input type="text" id="res_first" name="first_name" placeholder="First name"
                           value="{{ old('first_name') }}"
                           class="@error('first_name') is-invalid @enderror">
                    @error('first_name')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="res_last">Last name</label>
                    <input type="text" id="res_last" name="last_name" placeholder="Last name"
                           value="{{ old('last_name') }}"
                           class="@error('last_name') is-invalid @enderror">
                    @error('last_name')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-12">
                <div class="aq-field">
                    <label for="res_email">Email address</label>
                    <input type="email" id="res_email" name="email" placeholder="you@email.com"
                           value="{{ old('email') }}"
                           class="@error('email') is-invalid @enderror">
                    @error('email')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-12">
                <div class="aq-field">
                    <label for="res_phone">Phone number</label>
                    <input type="tel" id="res_phone" name="phone" placeholder="04xx xxx xxx"
                           value="{{ old('phone') }}"
                           class="@error('phone') is-invalid @enderror">
                    @error('phone')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="res_date">Date</label>
                    <input type="date" id="res_date" name="date"
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('date') }}"
                           class="@error('date') is-invalid @enderror">
                    @error('date')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="res_party">Party size</label>
                    <select id="res_party" name="party_size" class="@error('party_size') is-invalid @enderror">
                        @for ($i = 1; $i <= $maxPartySize; $i++)
                            <option value="{{ $i }}" @selected((int) old('party_size', 2) === $i)>
                                {{ $i }} {{ $i === 1 ? 'guest' : 'guests' }}
                            </option>
                        @endfor
                    </select>
                    @error('party_size')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <p class="aq-party-note">
            Booking for {{ $maxPartySize }} or more?
            <a href="{{ route('specials') }}">See our packages &rarr;</a>
        </p>

        {{-- Time slots: real radio inputs so the choice actually submits --}}
        <fieldset class="aq-timeslots">
            <legend class="aq-sr-only">Select a time</legend>

            <p class="aq-service-label">Lunch service</p>
            <div class="aq-time-grid">
                @foreach ($lunchSlots as $slot)
                    @php $isOff = in_array($slot, $unavailable, true); @endphp
                    <label class="aq-time-slot {{ $isOff ? 'is-disabled' : '' }}">
                        <input type="radio" name="time" value="{{ $slot }}"
                               @checked(old('time') === $slot)
                               @disabled($isOff)>
                        <span>{{ $slot }}</span>
                    </label>
                @endforeach
            </div>

            <p class="aq-service-label">Dinner service</p>
            <div class="aq-time-grid">
                @foreach ($dinnerSlots as $slot)
                    @php $isOff = in_array($slot, $unavailable, true); @endphp
                    <label class="aq-time-slot {{ $isOff ? 'is-disabled' : '' }}">
                        <input type="radio" name="time" value="{{ $slot }}"
                               @checked(old('time') === $slot)
                               @disabled($isOff)>
                        <span>{{ $slot }}</span>
                    </label>
                @endforeach
            </div>

            <p class="aq-legend"><span class="aq-legend-dot"></span> Fully booked</p>

            @error('time')<span class="aq-field-error d-block mb-2">{{ $message }}</span>@enderror
        </fieldset>

        <div class="aq-field">
            <label for="res_requests">Special requests</label>
            <textarea id="res_requests" name="requests" rows="2"
                      placeholder="Dietary requirements, business meeting setup, celebrations…"
                      class="@error('requests') is-invalid @enderror">{{ old('requests') }}</textarea>
            @error('requests')<span class="aq-field-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-aquila-gold w-100 mt-2">Confirm reservation</button>
    </form>
</div>