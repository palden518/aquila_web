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
            'website' => ['prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50',
            'regex:/\A(?:(?:04|\+61 ?4)(?: ?[0-9]){8}|(?:0[2378]|\+61 ?[2378])(?: ?[0-9]){8})\z/',],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:1000'],
            'event_type' => ['required', 'in:corporate,wedding,private_party,off_site'],
            'message' => ['nullable', 'string', 'max:5000'],
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
