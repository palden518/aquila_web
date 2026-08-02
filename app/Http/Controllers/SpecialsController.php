<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Package;

class SpecialsController extends Controller
{
    public function index()
    {
        $weeklySpecial = MenuItem::available()
            ->weeklySpecial()
            ->latest('updated_at')
            ->first();

        $packages = Package::active()
            ->orderByDesc('is_popular')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('specials', compact('weeklySpecial', 'packages'));
    }
}
