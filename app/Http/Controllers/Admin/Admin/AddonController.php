<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Addon::create($data);

        return redirect()->route('admin.addons.index')->with('status', 'Add-on created.');
    }

    public function edit(Addon $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $addon->update($data);

        return redirect()->route('admin.addons.index')->with('status', 'Add-on updated.');
    }

    /** Safe to delete: past orders keep their own copy of the name and price. */
    public function destroy(Addon $addon): RedirectResponse
    {
        $addon->delete();

        return redirect()->route('admin.addons.index')->with('status', 'Add-on deleted. Past orders are not affected.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
