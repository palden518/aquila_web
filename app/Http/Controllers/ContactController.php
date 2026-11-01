<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\NotificationLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $content = [
            'phone' => Setting::get('site_phone', '044 792 5110'),
            'email' => Setting::get('site_email', 'hello@aquila.net.au'),
            'address' => 'G/82 Eagle St, Brisbane City QLD 4000',
        ];

        return view('contact', compact('content'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^(?:(?:04|\+61[ -]?4)(?:[ -]?[0-9]){8}|(?:0[2378]|\+61[ -]?[2378])(?:[ -]?[0-9]){8})$/',],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);
        $validated['status'] = 'new';

        $contactMessage = ContactMessage::create($validated);

        $this->notifyAdmin($contactMessage);

        return redirect()
            ->route('contact')
            ->with('contact_success', "Thanks — we've received your message and will get back to you shortly.");
    }

    protected function notifyAdmin(ContactMessage $contactMessage): void
    {
        $adminEmail = Setting::get('admin_notification_email', config('mail.admin_address', 'hello@aquila.net.au'));
        $status = 'sent';

        try {
            Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error('Failed to send contact message admin alert: ' . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => ContactMessage::class,
            'notifiable_id' => $contactMessage->id,
            'channel' => 'email',
            'recipient' => $adminEmail,
            'message' => "New contact message from {$contactMessage->name} ({$contactMessage->email}).",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
