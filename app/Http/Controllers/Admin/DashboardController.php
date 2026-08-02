<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringEnquiry;
use App\Models\MenuItem;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'new_enquiries' => CateringEnquiry::where('status', 'new')->count(),
            'total_menu_items' => MenuItem::count(),
            'unavailable_items' => MenuItem::where('is_available', false)->count(),
            'active_packages' => Package::active()->count(),
        ];

        $recentEnquiries = CateringEnquiry::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEnquiries'));
    }
}
