@php $package ??= null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $package->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Subtitle (optional)</label>
        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $package->subtitle ?? '') }}" placeholder="e.g. Ideal for team lunches and meetings">
    </div>

    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $package->slug ?? '') }}" placeholder="Leave blank to auto-generate">
    </div>

    <div class="col-md-3">
        <label class="form-label">Price ($, optional)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $package->price ?? '') }}" placeholder="Leave blank for &quot;Price on enquiry&quot;">
    </div>

    <div class="col-md-3">
        <label class="form-label">Sort order</label>
        <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $package->sort_order ?? 0) }}">
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
        <label class="form-label">Features / inclusions (one per line)</label>
        <textarea name="inclusions" class="form-control" rows="5" placeholder="Set menu with two main course options&#10;Shared antipasto starter&#10;Tea and coffee included">{{ old('inclusions', $package->inclusions ?? '') }}</textarea>
        <div class="form-text">Shown as a bulleted feature list on the Specials &amp; Packages page.</div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active (shown on the public Specials &amp; Packages page)</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $package->is_popular ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_popular">Mark as "Most popular"</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-admin-gold">Save package</button>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
