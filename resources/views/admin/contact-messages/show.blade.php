@extends('admin.layouts.app')

@section('title', 'Message — ' . $message->name)

@section('content')
    <a href="{{ route('admin.contact-messages.index') }}" class="d-inline-block mb-3">&larr; Back to all messages</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-card">
                <h2 class="h5">Message details</h2>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="w-25">Name</th>
                        <td>{{ $message->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $message->phone ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>{{ $message->subject ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td style="white-space: pre-line;">{{ $message->message }}</td>
                    </tr>
                    <tr>
                        <th>Received</th>
                        <td>{{ $message->created_at->format('d M Y, g:ia') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <h2 class="h5">Status</h2>
                <form method="POST" action="{{ route('admin.contact-messages.status', $message) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $message->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-admin-gold w-100">Update status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
