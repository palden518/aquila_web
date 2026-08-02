<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $weeklySpecial = MenuItem::available()
            ->weeklySpecial()
            ->latest('updated_at')
            ->first();

        // Falls back to the original hard-coded copy if nothing is set yet,
        // so the page never breaks even on a fresh install.
        $content = [
            'phone' => Setting::get('site_phone', '044 792 5110'),
            'email' => Setting::get('site_email', 'hello@aquila.net.au'),
            'hero_heading' => Setting::get('hero_heading', 'Fine dining and catering for <em>business and leisure</em>'),
            'hero_copy' => Setting::get('hero_copy', "The Aquila is Brisbane's trusted destination for Italian and Australian cuisine — serving regulars, office professionals, and business meetings, seven days a week."),
            'about_lede' => Setting::get('about_lede', 'From business lunches to evening dining, The Aquila offers a refined yet welcoming space for our regulars, office professionals, and corporate guests.'),
        ];

        return view('home', compact('weeklySpecial', 'content'));
    }
}
