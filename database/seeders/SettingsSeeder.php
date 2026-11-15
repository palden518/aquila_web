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
            'orders_enabled' => '1',
            'orders_card_enabled' => '1',
            'orders_open_time' => '07:00',
            'orders_close_time' => '21:00',
            'orders_lead_minutes' => '30',
            'about_eyebrow' => 'Our story',
            'about_heading' => 'Two decades of <span class="text-gold-italic">Brisbane hospitality</span>',
            'about_intro' => "The Aquila has been a fixture of Brisbane's dining scene for over twenty years, bringing together Italian tradition and modern Australian produce for regulars, office professionals, and everyone in between.",
            'about_image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80',
            'about_story_heading' => 'From a small kitchen to a Brisbane institution',
            'about_story_paragraph_1' => 'The Aquila opened its doors in Brisbane City with a simple idea: bring Italian technique and Australian produce together on one menu, served in a room that feels equally right for a client lunch or a celebration dinner.',
            'about_story_paragraph_2' => "Two decades on, that idea hasn't changed. Our kitchen still works with seasonal, local ingredients, our floor team still knows plenty of guests by name, and we still open our doors seven days a week for dining, drinks, and private events.",
            'about_story_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&q=80',
            'about_stat_1_value' => '20+',
            'about_stat_1_label' => 'Years in Brisbane',
            'about_stat_2_value' => '15k',
            'about_stat_2_label' => 'Guests served yearly',
            'about_stat_3_value' => '7',
            'about_stat_3_label' => 'Days open a week',
        ]);
    }
}
