<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\OpeningHours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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
        'Table reservations' => [
            'reservation_max_guests' => 'Maximum guests at one time (the restaurant\'s seating capacity)',
            'reservation_table_minutes' => 'Table time in minutes (how long each booking holds a table)',
            'reservation_opening_time' => 'Opening time',
            'reservation_closing_time' => 'Closing time (the last booking is closing time minus table time)',
            'reservation_closed_days' => 'Closed days (no bookings on these days)',
        ],
    ];

    /**
     * Values used when a setting has never been saved, so the form
     * shows what the website is actually using.
     */
    protected array $defaults = [
        'reservation_max_guests' => '40',
        'reservation_table_minutes' => '120',
        'reservation_opening_time' => '07:00',
        'reservation_closing_time' => '15:00',
        'reservation_closed_days' => 'saturday,sunday',
    ];

    public function edit()
    {
        $values = [];
        foreach ($this->fields as $group) {
            foreach (array_keys($group) as $key) {
                $values[$key] = Setting::get($key, $this->defaults[$key] ?? '');
            }
        }

        // The closed days are shown as tick boxes, so turn "saturday,sunday" into a list.
        $values['reservation_closed_days'] = OpeningHours::closedDays();

        return view('admin.settings.edit', [
            'fields' => $this->fields,
            'values' => $values,
            'days' => OpeningHours::DAYS,
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
        $rules['reservation_max_guests'] = ['required', 'integer', 'min:1', 'max:1000'];
        $rules['reservation_table_minutes'] = ['required', 'integer', 'min:15', 'max:480'];
        $rules['reservation_opening_time'] = ['required', 'date_format:H:i'];
        $rules['reservation_closing_time'] = ['required', 'date_format:H:i', 'after:reservation_opening_time'];
        $rules['reservation_closed_days'] = ['nullable', 'array'];
        $rules['reservation_closed_days.*'] = [Rule::in(array_keys(OpeningHours::DAYS))];

        $validator = validator($request->all(), $rules, [
            'reservation_closing_time.after' => 'The closing time must be later than the opening time.',
        ], [
            'reservation_max_guests' => 'maximum guests at one time',
            'reservation_table_minutes' => 'table time in minutes',
            'reservation_opening_time' => 'opening time',
            'reservation_closing_time' => 'closing time',
        ]);

        // There must be room for at least one booking between opening and closing.
        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $open = Carbon::createFromFormat('H:i', $request->input('reservation_opening_time'));
            $close = Carbon::createFromFormat('H:i', $request->input('reservation_closing_time'));

            if ($open->diffInMinutes($close) < (int) $request->input('reservation_table_minutes')) {
                $validator->errors()->add('reservation_table_minutes', 'The table time is longer than the opening hours, so no bookings could be made.');
            }
        });

        $data = $validator->validate();

        // Save the ticked closed days as one value, e.g. "saturday,sunday".
        $data['reservation_closed_days'] = implode(',', $data['reservation_closed_days'] ?? []);

        Setting::setMany($data);

        return redirect()->route('admin.settings.edit')->with('status', 'Site content updated.');
    }
}
