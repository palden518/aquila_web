<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $content = [
            'eyebrow' => Setting::get('about_eyebrow', 'Our story'),
            'heading' => Setting::get('about_heading', 'Two decades of <span class="text-gold-italic">Brisbane hospitality</span>'),
            'intro' => Setting::get('about_intro', "The Aquila has been a fixture of Brisbane's dining scene for over twenty years, bringing together Italian tradition and modern Australian produce for regulars, office professionals, and everyone in between."),
            'image' => Setting::get('about_image', 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80'),

            'story_heading' => Setting::get('about_story_heading', 'From a small kitchen to a Brisbane institution'),
            'story_paragraph_1' => Setting::get('about_story_paragraph_1', 'The Aquila opened its doors in Brisbane City with a simple idea: bring Italian technique and Australian produce together on one menu, served in a room that feels equally right for a client lunch or a celebration dinner.'),
            'story_paragraph_2' => Setting::get('about_story_paragraph_2', "Two decades on, that idea hasn't changed. Our kitchen still works with seasonal, local ingredients, our floor team still knows plenty of guests by name, and we still open our doors seven days a week for dining, drinks, and private events."),
            'story_image' => Setting::get('about_story_image', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&q=80'),

            'stats' => [
                ['value' => Setting::get('about_stat_1_value', '20+'), 'label' => Setting::get('about_stat_1_label', 'Years in Brisbane')],
                ['value' => Setting::get('about_stat_2_value', '15k'), 'label' => Setting::get('about_stat_2_label', 'Guests served yearly')],
                ['value' => Setting::get('about_stat_3_value', '7'), 'label' => Setting::get('about_stat_3_label', 'Days open a week')],
            ],
        ];

        return view('about', compact('content'));
    }
}
