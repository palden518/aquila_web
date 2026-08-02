@extends('admin.layouts.app')

@section('title', 'Menu categories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">These map to the anchor sections on the public menu page (Breakfast, Lunch, Mains, etc).</p>
        <a href="{{ route('admin.menu-categories.create') }}" class="btn btn-admin-gold">Add category</a>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Name</th>
                    <th>Slug (anchor)</th>
                    <th>Items</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->sort_order }}</td>
                        <td>{{ $category->name }}</td>
                        <td><code>#{{ $category->slug }}</code></td>
                        <td>{{ $category->items_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.menu-categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.menu-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category and all its menu items?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
