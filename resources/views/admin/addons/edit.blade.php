@extends('admin.layouts.app')

@section('title', 'Edit add-on')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.addons.update', $addon) }}">
            @csrf
            @method('PUT')
            @include('admin.addons._form')
        </form>
    </div>
@endsection
