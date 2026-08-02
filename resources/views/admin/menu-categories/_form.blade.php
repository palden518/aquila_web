@php $category ??= null; @endphp

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Slug (used as the page anchor, e.g. "breakfast")</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" placeholder="Leave blank to auto-generate">
</div>

<div class="mb-3">
    <label class="form-label">Description / eyebrow text</label>
    <input type="text" name="description" class="form-control" value="{{ old('description', $category->description ?? '') }}" placeholder="e.g. Morning favourites">
</div>

<div class="mb-3">
    <label class="form-label">Sort order</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
</div>

<button type="submit" class="btn btn-admin-gold">Save category</button>
<a href="{{ route('admin.menu-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
