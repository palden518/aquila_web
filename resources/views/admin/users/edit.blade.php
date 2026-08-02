@extends('admin.layouts.app')

@section('title', 'Edit user')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('admin.users._form')
        </form>
    </div>
@endsection
