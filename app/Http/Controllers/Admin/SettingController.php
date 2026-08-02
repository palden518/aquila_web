<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * The editable homepage/menu content keys, grouped for the form.
     */
    protected array $fields = [
        'Contact details' => [
            'site_phone' => 'Phone number',
            'site_email' => 'Email address',
            'admin_notification_email' => 'Admin alert email (receives new catering enquiries)',
        ],
        'Homepage hero' => [
            'hero_heading' => 'Hero heading (HTML allowed, e.g. <em>italic</em>)',
            'hero_copy' => 'Hero paragraph',
            'about_lede' => '"Why Aquila" intro paragraph',
        ],
        'Menu — featured dish' => [
            'featured_dish_title' => 'Featured dish title',
            'featured_dish_description' => 'Featured dish description',
            'featured_dish_image' => 'Featured dish image URL',
        ],
    ];

    public function edit()
    {
        $values = [];
        foreach ($this->fields as $group) {
            foreach (array_keys($group) as $key) {
                $values[$key] = Setting::get($key, '');
            }
        }

        return view('admin.settings.edit', [
            'fields' => $this->fields,
            'values' => $values,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $allKeys = [];
        foreach ($this->fields as $group) {
            $allKeys = array_merge($allKeys, array_keys($group));
        }

        $rules = [];
        foreach ($allKeys as $key) {
            $rules[$key] = ['nullable', 'string', 'max:5000'];
        }
        $rules['site_email'] = ['nullable', 'email', 'max:255'];
        $rules['admin_notification_email'] = ['nullable', 'email', 'max:255'];

        $data = $request->validate($rules);

        Setting::setMany($data);

        return redirect()->route('admin.settings.edit')->with('status', 'Site content updated.');
    }
}
