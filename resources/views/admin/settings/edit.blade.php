@extends('admin.layouts.app')

@section('title', 'Website content')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @foreach ($fields as $groupLabel => $groupFields)
                <h2 class="h6 text-uppercase text-muted mt-3 mb-3">
                    {{ $groupLabel }}
                </h2>

                @foreach ($groupFields as $key => $label)
                    <div class="mb-3">

                        <label for="{{ $key }}" class="form-label">
                            {{ $label }}
                        </label>

                        @php
                            $value = old($key, $values[$key] ?? '');
                            $invalid = $errors->has($key);
                        @endphp

                        @if (in_array($key, [
                            'hero_copy',
                            'about_lede',
                            'featured_dish_description',
                            'about_intro',
                            'about_story_paragraph_1',
                            'about_story_paragraph_2'
                        ]))

                            <textarea
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-control @error($key) is-invalid @enderror"
                                rows="3"
                                maxlength="5000"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >{{ $value }}</textarea>

                        @elseif (in_array($key, [
                            'site_email',
                            'admin_notification_email'
                        ]))

                            <input
                                type="email"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-control @error($key) is-invalid @enderror"
                                value="{{ $value }}"
                                maxlength="255"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >

                        @elseif (in_array($key, [
                            'orders_open_time',
                            'orders_close_time',
                            'reservation_opening_time',
                            'reservation_closing_time'
                        ]))

                            <input
                                type="time"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-control @error($key) is-invalid @enderror"
                                style="max-width: 12rem"
                                value="{{ $value }}"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >

                        @elseif (in_array($key, [
                            'orders_enabled',
                            'orders_card_enabled'
                        ]))

                            <select
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-select @error($key) is-invalid @enderror"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >
                                <option value="" @selected($value === '' || $value === null)>
                                    Not set
                                </option>
                                <option value="1" @selected((string) $value === '1')>
                                    Yes
                                </option>
                                <option value="0" @selected((string) $value === '0')>
                                    No
                                </option>
                            </select>

                        @elseif ($key === 'orders_lead_minutes')

                            <input
                                type="number"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-control @error($key) is-invalid @enderror"
                                value="{{ $value }}"
                                min="0"
                                max="240"
                                step="1"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >

                        @elseif ($key === 'reservation_closed_days')

                            <div>
                                @foreach ($days as $dayKey => $dayName)
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="reservation_closed_days[]"
                                            id="closed_{{ $dayKey }}"
                                            value="{{ $dayKey }}"
                                            @checked(in_array(
                                                $dayKey,
                                                (array) old(
                                                    'reservation_closed_days',
                                                    $values['reservation_closed_days'] ?? []
                                                )
                                            ))
                                        >

                                        <label
                                            class="form-check-label"
                                            for="closed_{{ $dayKey }}"
                                        >
                                            {{ $dayName }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @foreach ($errors->get('reservation_closed_days.*') as $messages)
                                @foreach ($messages as $message)
                                    <div class="text-danger small" role="alert">
                                        {{ $message }}
                                    </div>
                                @endforeach
                            @endforeach

                        @else

                            <input
                                type="{{ $key === 'site_phone' ? 'tel' : 'text' }}"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                class="form-control @error($key) is-invalid @enderror"
                                value="{{ $value }}"
                                maxlength="5000"
                                aria-invalid="{{ $invalid ? 'true' : 'false' }}"
                                @if($invalid) aria-describedby="{{ $key }}_error" @endif
                            >

                        @endif

                        @error($key)
                            <div
                                id="{{ $key }}_error"
                                class="invalid-feedback d-block"
                                role="alert"
                            >
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                @endforeach
            @endforeach

            <button type="submit" class="btn btn-admin-gold">
                Save changes
            </button>
        </form>
    </div>
@endsection
