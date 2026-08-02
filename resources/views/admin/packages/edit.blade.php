@extends('admin.layouts.app')

@section('title', 'Edit package')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}">
            @csrf
            @method('PUT')
            @include('admin.packages._form')
        </form>
    </div>
@endsection
