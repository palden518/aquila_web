@extends('admin.layouts.app')

@section('title', 'Add package')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            @include('admin.packages._form')
        </form>
    </div>
@endsection
