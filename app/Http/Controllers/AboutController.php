<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $contentApproved = Setting::get('about_content_approved') === 'true';
        $approvedSetting = static function (string $key, string $fallback) use ($contentApproved): string {
            return $contentApproved
                ? (Setting::get($key, $fallback) ?? $fallback)
                : $fallback;
        };

        $stats = [];
        if ($contentApproved) {
            for ($index = 1; $index <= 3; $index++) {
                $value = Setting::get("about_stat_{$index}_value");
                $label = Setting::get("about_stat_{$index}_label");

                if ($value !== null && trim($value) !== '' && $label !== null && trim($label) !== '') {
                    $stats[] = [
                        'value' => $value,
                        'label' => $label,
                    ];
                }
            }
        }

        $content = [
            'eyebrow' => $approvedSetting('about_eyebrow', 'Our story'),
            'heading' => $approvedSetting('about_heading', 'About The Aquila'),
            'intro' => $approvedSetting(
                'about_intro',
                'From business lunches to evening dining, The Aquila offers a refined yet welcoming space for our regulars, office professionals, and corporate guests.'
            ),
            'image' => 'images/about page/dining.webp',
            'image_alt' => 'Restaurant dining area with tables and large windows',
            'story_heading' => $approvedSetting('about_story_heading', 'Our story'),
            'story_paragraph_1' => $approvedSetting(
                'about_story_paragraph_1',
                'The Aquila opened its doors in Brisbane City with a simple idea: bring Italian technique and Australian produce together on one menu, served in a room that feels equally right for a client lunch or a celebration dinner.'
            ),
            'story_paragraph_2' => $approvedSetting('about_story_paragraph_2', ''),
            'story_image' => 'images/about page/outdoor.webp',
            'story_image_alt' => 'Dining area with tables beside large windows and hanging plants',
            'stats' => $stats,
        ];

        return view('about', compact('content'));
    }
}
