@php $user ??= null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone (optional)</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ $user ? 'New password (leave blank to keep current)' : 'Password' }}</label>
        <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }} minlength="8">
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-admin-gold">Save user</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
