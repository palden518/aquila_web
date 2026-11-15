@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="d-inline-block mb-3">&larr; Back to all orders</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-card">
                <h2 class="h5">Items</h2>
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-end">Unit price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($order->addons as $addon)
                            <tr>
                                <td>{{ $addon->addon_name }} <span class="badge bg-secondary">Add-on</span></td>
                                <td class="text-end">${{ number_format($addon->unit_price, 2) }}</td>
                                <td class="text-center">{{ $addon->quantity }}</td>
                                <td class="text-end">${{ number_format($addon->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">${{ number_format($order->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="admin-card">
                <h2 class="h5">Customer</h2>
                <table class="table table-borderless mb-0">
                    <tr><th class="w-25">Name</th><td>{{ $order->customer_name }}</td></tr>
                    <tr><th>Email</th><td><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></td></tr>
                    <tr><th>Phone</th><td><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></td></tr>
                    <tr><th>Pickup</th><td>{{ $order->pickup_time?->format('l j F Y, g:i A') ?? '—' }}</td></tr>
                    <tr><th>Notes</th><td style="white-space: pre-line;">{{ $order->notes ?: '—' }}</td></tr>
                    <tr><th>Placed</th><td>{{ $order->created_at->format('d M Y, g:ia') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <h2 class="h5">Status</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-admin-gold w-100">Update status</button>
                </form>
                <p class="small text-muted mt-3 mb-0">
                    Marking an order <strong>Ready</strong> or <strong>Cancelled</strong> emails the customer automatically.
                </p>
            </div>

            <div class="admin-card">
                <h2 class="h5">Payment</h2>
                @php $card = $order->cardPayment(); @endphp
                <p class="mb-2"><strong>{{ $order->paymentLabel() }}</strong></p>
                @if ($card)
                    <p class="small text-muted mb-1">Amount: ${{ number_format($card->amount, 2) }}</p>
                    @if ($card->paid_at)
                        <p class="small text-muted mb-1">Paid: {{ $card->paid_at->format('d M Y, g:ia') }}</p>
                    @endif
                    @if ($card->transaction_ref)
                        <p class="small text-muted mb-2">Stripe reference: <code>{{ $card->transaction_ref }}</code></p>
                    @endif
                    @if ($order->paymentState() === 'paid')
                        <div class="alert alert-warning small mb-0">
                            Cancelling a paid order does <strong>not</strong> refund the customer automatically. Refund it from the Stripe dashboard.
                        </div>
                    @elseif ($order->paymentState() === 'awaiting')
                        <p class="small text-muted mb-0">The customer hasn't finished paying yet. Don't prepare this order until it shows as paid.</p>
                    @endif
                @else
                    <p class="small text-muted mb-0">The customer will pay when they collect.</p>
                @endif
            </div>

            <div class="admin-card">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="window.print()">Print order ticket</button>
            </div>
        </div>
    </div>
@endsection
