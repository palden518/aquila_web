@extends('admin.layouts.app')

@section('title', 'Event packages')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Shown on the Events &amp; Catering page pricing section.</p>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-admin-gold">Add package</a>
    </div>

    <div class="admin-card">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Active</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($packages as $package)
                    <tr>
                        <td>{{ $package->name }}</td>
                        <td>${{ number_format($package->price, 0) }}</td>
                        <td>
                            <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No packages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
