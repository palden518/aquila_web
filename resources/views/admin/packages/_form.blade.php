@php $package ??= null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $package->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $package->slug ?? '') }}" placeholder="Leave blank to auto-generate">
    </div>

    <div class="col-md-6">
        <label class="form-label">Price ($, minimum spend)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $package->price ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image URL (optional)</label>
        <input type="url" name="image" class="form-control" value="{{ old('image', $package->image ?? '') }}" placeholder="https://...">
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $package->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Inclusions (one per line)</label>
        <textarea name="inclusions" class="form-control" rows="4" placeholder="Venue hire (7 hours)&#10;Dedicated event manager&#10;Seasonal plated menu">{{ old('inclusions', $package->inclusions ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active (shown on the public Events &amp; Catering page)</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-admin-gold">Save package</button>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
