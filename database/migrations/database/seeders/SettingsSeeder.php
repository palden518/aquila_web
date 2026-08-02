<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setMany([
            'site_phone' => '044 792 5110',
            'site_email' => 'hello@aquila.net.au',
            'admin_notification_email' => 'hello@aquila.net.au',
            'hero_heading' => 'Fine dining and catering for <em>business and leisure</em>',
            'hero_copy' => "The Aquila is Brisbane's trusted destination for Italian and Australian cuisine — serving regulars, office professionals, and business meetings, seven days a week.",
            'about_lede' => 'From business lunches to evening dining, The Aquila offers a refined yet welcoming space for our regulars, office professionals, and corporate guests.',
            'featured_dish_title' => 'Home Dinner',
            'featured_dish_description' => "Ask our team about today's featured meal, prepared using fresh seasonal ingredients.",
            'featured_dish_image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80',
        ]);
    }
}
