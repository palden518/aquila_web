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

    /**
     * AQL-35: confirming an enquiry turns it into a booked event.
     * - Blocks confirming a second event on an already-booked date.
     * - Records confirmed_at when it becomes a booking.
     * - Keeps confirmed_at when the event is completed (conversion history);
     *   clears it if the booking is declined or moved back.
     */
    public function updateStatus(Request $request, CateringEnquiry $cateringEnquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', CateringEnquiry::STATUSES)],
        ]);

        $newStatus = $data['status'];

        if ($newStatus === 'confirmed' && ! $cateringEnquiry->isBooked()) {
            if ($cateringEnquiry->dateAlreadyBooked()) {
                return back()->withErrors([
                    'status' => 'Another event is already confirmed for '
                        . $cateringEnquiry->event_date->format('d M Y')
                        . '. Decline or reschedule it before confirming this one.',
                ]);
            }

            $cateringEnquiry->confirmed_at = now();
        }

        if (! in_array($newStatus, ['confirmed', 'completed'], true)) {
            $cateringEnquiry->confirmed_at = null;
        }

        $cateringEnquiry->status = $newStatus;
        $cateringEnquiry->save();

        $message = $cateringEnquiry->isBooked() ? 'Enquiry confirmed as a booked event.' : 'Enquiry status updated.';

        return back()->with('status', $message);
    }
}