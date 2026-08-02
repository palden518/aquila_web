<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::withCount('items')->orderBy('sort_order')->get();

        return view('admin.menu-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.menu-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        MenuCategory::create($data);

        return redirect()->route('admin.menu-categories.index')->with('status', 'Category created.');
    }

    public function edit(MenuCategory $menuCategory)
    {
        return view('admin.menu-categories.edit', ['category' => $menuCategory]);
    }

    public function update(Request $request, MenuCategory $menuCategory): RedirectResponse
    {
        $data = $this->validated($request, $menuCategory->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $menuCategory->update($data);

        return redirect()->route('admin.menu-categories.index')->with('status', 'Category updated.');
    }

    public function destroy(MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->delete();

        return redirect()->route('admin.menu-categories.index')->with('status', 'Category deleted.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', 'unique:menu_categories,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
