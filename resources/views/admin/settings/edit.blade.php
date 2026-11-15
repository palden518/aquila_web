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
                        @if (in_array($key, ['hero_copy', 'about_lede', 'featured_dish_description', 'about_intro', 'about_story_paragraph_1', 'about_story_paragraph_2']))
                            <textarea name="{{ $key }}" class="form-control" rows="3">{{ old($key, $values[$key]) }}</textarea>
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
