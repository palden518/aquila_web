@extends('admin.layouts.app')

@section('title', 'Add user')

@section('content')
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form')
        </form>
    </div>
@endsection
