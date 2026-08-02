@extends('admin.layouts.app')

@section('title', 'Add category')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.menu-categories.store') }}">
            @csrf
            @include('admin.menu-categories._form')
        </form>
    </div>
@endsection
