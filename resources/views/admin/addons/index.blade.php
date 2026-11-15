@extends('admin.layouts.app')

@section('title', 'Add-ons')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Optional extras customers can add at checkout, such as a drink, a side, extra sauce or cutlery. Use a price of 0 for a free add-on.</p>
        <a href="{{ route('admin.addons.create') }}" class="btn btn-admin-gold">Add an add-on</a>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Offered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($addons as $addon)
                    <tr>
                        <td>{{ $addon->sort_order }}</td>
                        <td>
                            {{ $addon->name }}
                            @if ($addon->description)
                                <br><span class="text-muted small">{{ $addon->description }}</span>
                            @endif
                        </td>
                        <td>{{ $addon->display_price }}</td>
                        <td>
                            <span class="badge {{ $addon->is_available ? 'bg-success' : 'bg-secondary' }}">
                                {{ $addon->is_available ? 'Offered' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.addons.edit', $addon) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this add-on? Past orders are not affected.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No add-ons yet. Customers won't see an add-ons section at checkout until you add one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
