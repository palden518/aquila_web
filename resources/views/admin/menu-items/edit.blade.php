@extends('admin.layouts.app')

@section('title', 'Edit menu item')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.menu-items.update', $item) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.menu-items._form')
        </form>
    </div>
@endsection
