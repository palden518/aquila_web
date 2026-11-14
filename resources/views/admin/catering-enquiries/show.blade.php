@extends('admin.layouts.app')

@section('title', 'Enquiry — ' . $enquiry->name)

@section('content')
    <a href="{{ route('admin.catering-enquiries.index') }}" class="d-inline-block mb-3">&larr; Back to all enquiries</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-card">
                <h2 class="h5">Enquiry details</h2>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="w-25">Name</th>
                        <td>{{ $enquiry->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></td>
                    </tr>
                    <tr>
                        <th>Event date</th>
                        <td>{{ $enquiry->event_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Guest count</th>
                        <td>{{ $enquiry->guest_count }}</td>
                    </tr>
                    <tr>
                        <th>Event type</th>
                        <td>{{ $enquiry->event_type ? ucfirst(str_replace('_', ' ', $enquiry->event_type)) : '—' }}</td>
                    </tr>
                    {{-- EP2: package chosen on the enquiry form (optional) --}}
                    <tr>
                        <th>Package</th>
                        <td>{{ $enquiry->package?->name ?? 'Not sure yet' }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{{ $enquiry->message ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Received</th>
                        <td>{{ $enquiry->created_at->format('d M Y, g:ia') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <h2 class="h5">Status</h2>
                <p><span class="badge badge-status-{{ $enquiry->status }}">{{ ucfirst($enquiry->status) }}</span></p>

                <form method="POST" action="{{ route('admin.catering-enquiries.status', $enquiry) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $enquiry->status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-admin-gold w-100">Update status</button>
                </form>
            </div>
        </div>
    </div>
@endsection