@extends('admin.layouts.app')

@section('title', 'Reservation — ' . $reservation->name)

@section('content')
    <a href="{{ route('admin.reservations.index') }}" class="d-inline-block mb-3">&larr; Back to all reservations</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-card">
                <h2 class="h5">Reservation details</h2>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="w-25">Name</th>
                        <td>{{ $reservation->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $reservation->email }}">{{ $reservation->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><a href="tel:{{ $reservation->phone }}">{{ $reservation->phone }}</a></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $reservation->reservation_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Party size</th>
                        <td>{{ $reservation->party_size }}</td>
                    </tr>
                    <tr>
                        <th>Special requests</th>
                        <td style="white-space: pre-line;">{{ $reservation->notes ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Received</th>
                        <td>{{ $reservation->created_at->format('d M Y, g:ia') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <h2 class="h5">Status</h2>
                <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $reservation->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-admin-gold w-100">Update status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
