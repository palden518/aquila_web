@extends('admin.layouts.app')

@section('title', 'Website content')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @foreach ($fields as $groupLabel => $groupFields)
                <h2 class="h6 text-uppercase text-muted mt-3 mb-3">{{ $groupLabel }}</h2>

                @foreach ($groupFields as $key => $label)
                    <div class="mb-3">
                        <label class="form-label">{{ $label }}</label>
                        @if (in_array($key, ['hero_copy', 'about_lede', 'featured_dish_description']))
                            <textarea name="{{ $key }}" class="form-control" rows="3">{{ old($key, $values[$key]) }}</textarea>
                        @elseif (in_array($key, ['reservation_opening_time', 'reservation_closing_time']))
                            <input type="time" name="{{ $key }}" class="form-control" style="max-width: 12rem" value="{{ old($key, $values[$key]) }}">
                        @elseif ($key === 'reservation_closed_days')
                            <div>
                                @foreach ($days as $dayKey => $dayName)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="reservation_closed_days[]" id="closed_{{ $dayKey }}" value="{{ $dayKey }}"
                                            @checked(in_array($dayKey, old('reservation_closed_days', $values['reservation_closed_days'])))>
                                        <label class="form-check-label" for="closed_{{ $dayKey }}">{{ $dayName }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <input type="text" name="{{ $key }}" class="form-control" value="{{ old($key, $values[$key]) }}">
                        @endif
                    </div>
                @endforeach
            @endforeach

            <button type="submit" class="btn btn-admin-gold">Save changes</button>
        </form>
    </div>
@endsection
