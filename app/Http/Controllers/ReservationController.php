<?php

namespace App\Http\Controllers;

use App\Mail\ReservationReceived;
use App\Models\NotificationLog;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('reservation', [
            'website' => ['prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:50',
                'regex:/\A(?:(?:04|\+61 ?4)(?: ?[0-9]){8}|(?:0[2378]|\+61 ?[2378])(?: ?[0-9]){8})\z/',
            ],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'party_size' => ['required', 'integer', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['status'] = 'new';

        $reservation = Reservation::create($validated);

        $this->notifyAdmin($reservation);

        return back()
            ->with('reservation_success', "Thanks, {$reservation->name} — your table request has been received. We'll confirm by email or phone shortly.")
            ->withFragment('reserve');
    }

    protected function notifyAdmin(Reservation $reservation): void
    {
        $adminEmail = Setting::get('admin_notification_email', config('mail.admin_address', 'hello@aquila.net.au'));
        $status = 'sent';

        try {
            Mail::to($adminEmail)->send(new ReservationReceived($reservation));
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error('Failed to send reservation admin alert: ' . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => Reservation::class,
            'notifiable_id' => $reservation->id,
            'channel' => 'email',
            'recipient' => $adminEmail,
            'message' => "New reservation from {$reservation->name} for {$reservation->reservation_date->format('d M Y')}, party of {$reservation->party_size}.",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
