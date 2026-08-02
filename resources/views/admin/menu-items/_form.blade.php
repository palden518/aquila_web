@php $item ??= null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="menu_category_id" class="form-select" required>
            <option value="">Choose a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('menu_category_id', $item->menu_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}" placeholder="Leave blank to auto-generate">
    </div>

    <div class="col-md-6">
        <label class="form-label">Price ($)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $item->price ?? '') }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $item->description ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image URL (optional)</label>
        <input type="url" name="image" class="form-control" value="{{ old('image', $item->image ?? '') }}" placeholder="https://...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Or upload an image</label>
        <input type="file" name="image_upload" class="form-control" accept="image/*">
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $item->is_available ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_available">Available on the public menu</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_weekly_special" id="is_weekly_special" value="1" {{ old('is_weekly_special', $item->is_weekly_special ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_weekly_special">Feature as this week's special (homepage)</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-admin-gold">Save item</button>
    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
