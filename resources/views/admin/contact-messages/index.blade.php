@extends('admin.layouts.app')

@section('title', 'Contact messages')

@section('content')
    <div class="admin-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->subject ?: '—' }}</td>
                        <td><span class="badge badge-status-{{ $message->status === 'new' ? 'new' : ($message->status === 'replied' ? 'confirmed' : 'contacted') }}">{{ ucfirst($message->status) }}</span></td>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No messages found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $messages->links() }}
    </div>
@endsection
