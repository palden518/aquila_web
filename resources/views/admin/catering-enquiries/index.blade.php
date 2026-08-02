@extends('admin.layouts.app')

@section('title', 'Catering enquiries')

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
                    <th>Event date</th>
                    <th>Guests</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->email }}<br><span class="text-muted small">{{ $enquiry->phone }}</span></td>
                        <td>{{ $enquiry->event_date->format('d M Y') }}</td>
                        <td>{{ $enquiry->guest_count }}</td>
                        <td>{{ $enquiry->event_type ? ucfirst(str_replace('_', ' ', $enquiry->event_type)) : '—' }}</td>
                        <td><span class="badge badge-status-{{ $enquiry->status }}">{{ ucfirst($enquiry->status) }}</span></td>
                        <td>{{ $enquiry->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.catering-enquiries.show', $enquiry) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted">No enquiries found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $enquiries->links() }}
    </div>
@endsection
