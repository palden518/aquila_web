@extends('admin.layouts.app')

@section('title', 'Menu items')

@section('content')
    <div class="admin-card mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Item name">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('admin.menu-items.create') }}" class="btn btn-admin-gold w-100">Add item</a>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Available</th>
                    <th>Weekly special</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->display_price }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.menu-items.toggle', $item) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="field" value="is_available">
                                <button type="submit" class="btn btn-sm {{ $item->is_available ? 'btn-success' : 'btn-outline-secondary' }}">
                                    {{ $item->is_available ? 'Yes' : 'No' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.menu-items.toggle', $item) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="field" value="is_weekly_special">
                                <button type="submit" class="btn btn-sm {{ $item->is_weekly_special ? 'btn-admin-gold' : 'btn-outline-secondary' }}">
                                    {{ $item->is_weekly_special ? 'Featured' : 'Set as special' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.menu-items.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this menu item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No menu items found.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
@endsection
