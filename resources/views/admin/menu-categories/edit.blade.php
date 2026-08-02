@extends('admin.layouts.app')

@section('title', 'Edit category')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.menu-categories.update', $category) }}">
            @csrf
            @method('PUT')
            @include('admin.menu-categories._form')
        </form>
    </div>
@endsection
