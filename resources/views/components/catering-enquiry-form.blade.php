{{--
    SHARED COMPONENT — Catering & event enquiry form
    ------------------------------------------------
    Agreed as ONE shared component across:
      - Specials & packages   (specials.blade.php)
      - Events & catering     (Mahindra)
      - Contact               (Palden)

    Usage:
        <x-catering-enquiry-form />
        <x-catering-enquiry-form heading="Send a catering enquiry" :event-type="'Catering — office'" />

    Do NOT copy-paste this markup into another page. Include the component so a
    field change here updates every page at once — that is the whole point.
--}}

@props([
    'heading'   => 'Catering and event enquiry',
    'subheading'=> 'Office lunches, corporate functions, and catering delivery.',
    'eventType' => null,
])

<div class="aq-form-card">
    <h3>{{ $heading }}</h3>
    <p class="aq-form-sub">{{ $subheading }}</p>

    @if (session('enquiry_success'))
        <div class="aq-alert aq-alert-success" role="status">
            <span aria-hidden="true">&#10003;</span>
            <span>{{ session('enquiry_success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('enquiries.store') }}" novalidate>
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_name">Full name</label>
                    <input type="text" id="enq_name" name="name" placeholder="Your name"
                           value="{{ old('name') }}"
                           class="@error('name') is-invalid @enderror">
                    @error('name')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_email">Email address</label>
                    <input type="email" id="enq_email" name="email" placeholder="you@email.com"
                           value="{{ old('email') }}"
                           class="@error('email') is-invalid @enderror">
                    @error('email')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_phone">Phone number</label>
                    <input type="tel" id="enq_phone" name="phone" placeholder="04xx xxx xxx"
                           value="{{ old('phone') }}"
                           class="@error('phone') is-invalid @enderror">
                    @error('phone')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_type">Event type</label>
                    @php
                        $types = [
                            'Office lunch package',
                            'Corporate event package',
                            'Catering package',
                            'Catering — office',
                            'Other',
                        ];
                        $selectedType = old('event_type', $eventType);
                    @endphp
                    <select id="enq_type" name="event_type" class="@error('event_type') is-invalid @enderror">
                        @foreach ($types as $type)
                            <option value="{{ $type }}" @selected($selectedType === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('event_type')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_date">Event date</label>
                    <input type="date" id="enq_date" name="event_date"
                           value="{{ old('event_date') }}"
                           class="@error('event_date') is-invalid @enderror">
                    @error('event_date')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="aq-field">
                    <label for="enq_guests">Guest count</label>
                    <input type="number" id="enq_guests" name="guests" min="1" max="200" placeholder="e.g. 15"
                           value="{{ old('guests') }}"
                           class="@error('guests') is-invalid @enderror">
                    @error('guests')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-12">
                <div class="aq-field">
                    <label for="enq_message">Message</label>
                    <textarea id="enq_message" name="message" rows="3"
                              placeholder="Tell us about your event, expected guest numbers, and date…"
                              class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message')<span class="aq-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-aquila-gold w-100 mt-2">Send an enquiry</button>
    </form>
</div>
