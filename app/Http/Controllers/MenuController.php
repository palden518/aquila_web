<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\Setting;

class MenuController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::query()
            ->with(['availableItems'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featured = [
            'title' => Setting::get('featured_dish_title', "Home Dinner"),
            'description' => Setting::get('featured_dish_description', "Ask our team about today's featured meal, prepared using fresh seasonal ingredients."),
            'image' => Setting::get('featured_dish_image', 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80'),
        ];

        return view('menu', compact('categories', 'featured'));
    }
}
