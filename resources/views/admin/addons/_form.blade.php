@php $addon ??= null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $addon->name ?? '') }}" placeholder="e.g. Extra sauce" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Price ($)</label>
        <input type="number" step="0.01" min="0" max="999.99" name="price" class="form-control" value="{{ old('price', $addon->price ?? '') }}" required>
        <div class="form-text">Enter 0 for a free add-on.</div>
    </div>

    <div class="col-md-3">
        <label class="form-label">Sort order</label>
        <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $addon->sort_order ?? 0) }}">
    </div>

    <div class="col-12">
        <label class="form-label">Short description (optional)</label>
        <input type="text" name="description" class="form-control" value="{{ old('description', $addon->description ?? '') }}" maxlength="255" placeholder="e.g. Aioli, chilli or BBQ">
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $addon->is_available ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_available">Offered at checkout</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-admin-gold">Save add-on</button>
    <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
