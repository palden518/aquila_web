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
        'Online ordering' => [
            'orders_enabled' => 'Accept online orders? Type 1 for yes, 0 to pause ordering',
            'orders_card_enabled' => 'Offer card payment (Stripe) at checkout? Type 1 for yes, 0 to pause. Needs Stripe keys in .env',
            'orders_open_time' => 'First pickup time each day (24-hour, e.g. 07:00)',
            'orders_close_time' => 'Last pickup time each day (24-hour, e.g. 21:00)',
            'orders_lead_minutes' => 'Minimum minutes between ordering and pickup (e.g. 30)',
        ],
        'About page' => [
            'about_eyebrow' => 'Eyebrow text (small label above the heading)',
            'about_heading' => 'Heading (HTML allowed, e.g. <span class="text-gold-italic">italic gold</span>)',
            'about_intro' => 'Intro paragraph (under the heading)',
            'about_image' => 'Hero image URL',
            'about_story_heading' => 'Story section heading',
            'about_story_paragraph_1' => 'Story — first paragraph',
            'about_story_paragraph_2' => 'Story — second paragraph',
            'about_story_image' => 'Story section image URL',
            'about_stat_1_value' => 'Stat 1 — number (e.g. 20+)',
            'about_stat_1_label' => 'Stat 1 — label (e.g. Years in Brisbane)',
            'about_stat_2_value' => 'Stat 2 — number (e.g. 15k)',
            'about_stat_2_label' => 'Stat 2 — label (e.g. Guests served yearly)',
            'about_stat_3_value' => 'Stat 3 — number (e.g. 7)',
            'about_stat_3_label' => 'Stat 3 — label (e.g. Days open a week)',
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
        $rules['orders_enabled'] = ['nullable', 'in:0,1'];
        $rules['orders_card_enabled'] = ['nullable', 'in:0,1'];
        $rules['orders_open_time'] = ['nullable', 'date_format:H:i'];
        $rules['orders_close_time'] = ['nullable', 'date_format:H:i', 'after:orders_open_time'];
        $rules['orders_lead_minutes'] = ['nullable', 'integer', 'min:0', 'max:240'];

        $data = $request->validate($rules);

        Setting::setMany($data);

        return redirect()->route('admin.settings.edit')->with('status', 'Site content updated.');
    }
}
