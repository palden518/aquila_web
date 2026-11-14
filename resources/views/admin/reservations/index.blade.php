@extends('admin.layouts.app')

@section('title', 'Table reservations')

@section('content')
    <div class="admin-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                    <th>Date</th>
                    <th>Time</th>
                    <th>Party</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->name }}</td>
                        <td>{{ $reservation->email }}<br><span class="text-muted small">{{ $reservation->phone }}</span></td>
                        <td>{{ $reservation->reservation_date->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</td>
                        <td>{{ $reservation->party_size }}</td>
                        <td><span class="badge badge-status-{{ $reservation->status === 'cancelled' ? 'declined' : $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">No reservations found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $reservations->links() }}
    </div>
@endsection
