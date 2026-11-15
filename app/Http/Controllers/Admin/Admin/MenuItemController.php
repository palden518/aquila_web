<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $items = MenuItem::with('category')
            ->when($request->filled('category'), fn ($q) => $q->where('menu_category_id', $request->integer('category')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->string('search') . '%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = MenuCategory::orderBy('sort_order')->get();

        return view('admin.menu-items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = MenuCategory::orderBy('sort_order')->get();

        return view('admin.menu-items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_available'] = $request->boolean('is_available');
        $data['is_weekly_special'] = $request->boolean('is_weekly_special');

        if ($request->hasFile('image_upload')) {
            $data['image'] = $request->file('image_upload')->store('menu-items', 'public');
        }

        MenuItem::create($data);

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item created.');
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = MenuCategory::orderBy('sort_order')->get();

        return view('admin.menu-items.edit', ['item' => $menuItem, 'categories' => $categories]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $this->validated($request, $menuItem->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_available'] = $request->boolean('is_available');
        $data['is_weekly_special'] = $request->boolean('is_weekly_special');

        if ($request->hasFile('image_upload')) {
            $data['image'] = $request->file('image_upload')->store('menu-items', 'public');
        }

        $menuItem->update($data);

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        // order_items.menu_item_id is RESTRICT on delete, so an item that has
        // been ordered can't be removed without breaking order history.
        if (OrderItem::where('menu_item_id', $menuItem->id)->exists()) {
            return redirect()->route('admin.menu-items.index')->with(
                'error',
                "\"{$menuItem->name}\" has been ordered before, so it can't be deleted. Switch off \"Available\" instead to hide it from the menu."
            );
        }

        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item deleted.');
    }

    /**
     * Quick toggle from the index table (available / weekly special) without a full edit form.
     */
    public function toggle(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $field = $request->validate(['field' => ['required', 'in:is_available,is_weekly_special']])['field'];

        $menuItem->update([$field => ! $menuItem->{$field}]);

        return back()->with('status', 'Updated.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'menu_category_id' => ['required', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', 'unique:menu_items,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'image' => ['nullable', 'url', 'max:255'],
            'image_upload' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
