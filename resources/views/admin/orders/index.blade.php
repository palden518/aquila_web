@extends('admin.layouts.app')

@section('title', 'Online orders')

@section('content')
    <div class="admin-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Payment</label>
                <select name="payment" class="form-select">
                    <option value="">Ready to prepare (default)</option>
                    <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Paid by card</option>
                    <option value="pickup" {{ request('payment') === 'pickup' ? 'selected' : '' }}>Pay on pickup</option>
                    <option value="awaiting" {{ request('payment') === 'awaiting' ? 'selected' : '' }}>Awaiting card payment</option>
                    <option value="failed" {{ request('payment') === 'failed' ? 'selected' : '' }}>Card payment failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Pickup date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Number, name, email">
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
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Pickup</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td><code>{{ $order->order_number }}</code></td>
                        <td>{{ $order->customer_name }}<br><span class="text-muted small">{{ $order->customer_phone }}</span></td>
                        <td>{{ $order->pickup_time?->format('D j M, g:i A') ?? '—' }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td><span class="badge {{ ['paid' => 'bg-success', 'pay_on_pickup' => 'bg-secondary', 'awaiting' => 'bg-warning text-dark', 'failed' => 'bg-danger', 'refunded' => 'bg-dark'][$order->paymentState()] }}">{{ $order->paymentLabel() }}</span></td>
                        <td>
                            <span class="badge badge-status-{{ ['pending' => 'new', 'confirmed' => 'contacted', 'preparing' => 'contacted', 'ready' => 'confirmed', 'completed' => 'completed', 'cancelled' => 'declined'][$order->status] ?? 'completed' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
@endsection
