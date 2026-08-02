<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CateringEnquiryController extends Controller
{
    public function index(Request $request)
    {
        $enquiries = CateringEnquiry::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.catering-enquiries.index', [
            'enquiries' => $enquiries,
            'statuses' => CateringEnquiry::STATUSES,
        ]);
    }

    public function show(CateringEnquiry $cateringEnquiry)
    {
        return view('admin.catering-enquiries.show', [
            'enquiry' => $cateringEnquiry,
            'statuses' => CateringEnquiry::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, CateringEnquiry $cateringEnquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', CateringEnquiry::STATUSES)],
        ]);

        $cateringEnquiry->update($data);

        return back()->with('status', 'Enquiry status updated.');
    }
}
