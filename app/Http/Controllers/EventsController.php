<?php

namespace App\Http\Controllers;

use App\Mail\CateringEnquiryReceived;
use App\Models\CateringEnquiry;
use App\Models\NotificationLog;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class EventsController extends Controller
{
    public function index()
    {
        $packages = Package::active()->orderBy('price')->get();

        return view('events', compact('packages'));
    }

    public function storeEnquiry(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('catering', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:1000'],
            'event_type' => ['nullable', 'in:corporate,wedding,private_party,off_site'],
            // EP2: optional package choice. Must be a package that is currently active,
            // so a guest can't submit a hidden/retired package by editing the form.
            'package_id' => ['nullable', 'integer', Rule::in(Package::active()->pluck('id')->all())],
            'message' => ['nullable', 'string', 'max:5000'],
        ], [
            'package_id.in' => 'Please choose one of the packages listed, or "Not sure yet".',
        ]);

        if ($request->user()) {
            $validated['user_id'] = $request->user()->id;
        }
        $validated['status'] = 'new';

        $enquiry = CateringEnquiry::create($validated);

        $this->notifyAdmin($enquiry);

        return back()
            ->with('enquiry_success', 'Thanks — your enquiry has been received. Our events team will be in touch shortly.');
    }

    protected function notifyAdmin(CateringEnquiry $enquiry): void
    {
        $adminEmail = Setting::get('admin_notification_email', config('mail.admin_address', 'hello@aquila.net.au'));

        $status = 'sent';

        try {
            Mail::to($adminEmail)->send(new CateringEnquiryReceived($enquiry));
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error('Failed to send catering enquiry admin alert: ' . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => CateringEnquiry::class,
            'notifiable_id' => $enquiry->id,
            'channel' => 'email',
            'recipient' => $adminEmail,
            'message' => "New catering enquiry from {$enquiry->name} ({$enquiry->email}) for {$enquiry->event_date->format('d M Y')}, {$enquiry->guest_count} guests.",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}