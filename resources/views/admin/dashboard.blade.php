@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $stats['new_enquiries'] }}</div>
                <div class="admin-stat-label">New catering enquiries</div>
            </div>
        </div>
        @can('manage reservations')
            <div class="col-md-3">
                <a href="{{ route('admin.reservations.index', ['status' => 'new']) }}" class="text-decoration-none text-reset">
                    <div class="admin-stat-card">
                        <div class="admin-stat-value">{{ $stats['new_reservations'] }}</div>
                        <div class="admin-stat-label">New table reservations</div>
                    </div>
                </a>
            </div>
        @endcan
        <div class="col-md-3">
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $stats['new_reservations'] }}</div>
                <div class="admin-stat-label">New table reservations</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $stats['total_menu_items'] }}</div>
                <div class="admin-stat-label">Menu items</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $stats['active_packages'] }}</div>
                <div class="admin-stat-label">Active packages</div>
            </div>
        </div>
    </div>

    @can('manage orders')
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="admin-stat-value">{{ $stats['pending_orders'] }}</div>
                    <div class="admin-stat-label">Pending orders</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="admin-stat-value">{{ $stats['todays_pickups'] }}</div>
                    <div class="admin-stat-label">Pickups today</div>
                </div>
            </div>
        </div>
    @endcan

    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Recent catering enquiries</h2>
            @can('manage catering')
                <a href="{{ route('admin.catering-enquiries.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
            @endcan
        </div>

        @if ($recentEnquiries->isEmpty())
            <p class="text-muted mb-0">No enquiries yet.</p>
        @else
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Event date</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentEnquiries as $enquiry)
                        <tr>
                            <td>
                                @can('manage catering')
                                    <a href="{{ route('admin.catering-enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a>
                                @else
                                    {{ $enquiry->name }}
                                @endcan
                            </td>
                            <td>{{ $enquiry->event_date->format('d M Y') }}</td>
                            <td>{{ $enquiry->guest_count }}</td>
                            <td><span class="badge badge-status-{{ $enquiry->status }}">{{ ucfirst($enquiry->status) }}</span></td>
                            <td>{{ $enquiry->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
