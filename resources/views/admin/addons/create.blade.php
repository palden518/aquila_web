@extends('admin.layouts.app')

@section('title', 'Add an add-on')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.addons.store') }}">
            @csrf
            @include('admin.addons._form')
        </form>
    </div>
@endsection
