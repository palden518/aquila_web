@extends('admin.layouts.app')

@section('title', 'Add menu item')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.menu-items.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.menu-items._form')
        </form>
    </div>
@endsection
