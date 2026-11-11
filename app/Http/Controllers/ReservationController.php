<?php

namespace App\Http\Controllers;

use App\Mail\ReservationReceived;
use App\Models\NotificationLog;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $timezone = 'Australia/Brisbane';

        $validator = Validator::make($request->all(), [
            'website' => ['prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:50',
                'regex:/\A(?:(?:04|\+61 ?4)(?: ?[0-9]){8}|(?:0[2378]|\+61 ?[2378])(?: ?[0-9]){8})\z/',
            ],
            'reservation_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . Carbon::now($timezone)->toDateString(),
            ],
            'reservation_time' => [
                'required',
                'date_format:H:i',
            ],
            'party_size' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        // AQL-65: Validate the combined reservation date and time.
        $validator->after(function ($validator) use ($request, $timezone) {
            // Do not run this check if the date or time is already invalid.
            if (
                $validator->errors()->has('reservation_date') ||
                $validator->errors()->has('reservation_time')
            ) {
                return;
            }

            $date = $request->input('reservation_date');
            $time = $request->input('reservation_time');

            try {
                $reservationDateTime = Carbon::createFromFormat(
                    '!Y-m-d H:i',
                    $date . ' ' . $time,
                    $timezone
                );

                // Reject invalid date/time combinations.
                if (
                    ! $reservationDateTime ||
                    $reservationDateTime->format('Y-m-d H:i') !==
                        $date . ' ' . $time
                ) {
                    $validator->errors()->add(
                        'reservation_time',
                        'Please enter a valid reservation date and time.'
                    );

                    return;
                }

                // Reject reservations in the past or at the current time.
                if (
                    $reservationDateTime->lessThanOrEqualTo(
                        Carbon::now($timezone)
                    )
                ) {
                    $validator->errors()->add(
                        'reservation_time',
                        'Please choose a future reservation date and time.'
                    );
                }
            } catch (\Throwable $e) {
                $validator->errors()->add(
                    'reservation_time',
                    'Please enter a valid reservation date and time.'
                );
            }
        });

        // Preserve the named error bag used by the Reservation form.
        $validated = $validator->validateWithBag('reservation');

        $validated['status'] = 'new';

        $reservation = Reservation::create($validated);

        $this->notifyAdmin($reservation);

        return back()
            ->with(
                'reservation_success',
                "Thanks, {$reservation->name} — your table request has been received. We'll confirm by email or phone shortly."
            )
            ->withFragment('reserve');
    }

    protected function notifyAdmin(Reservation $reservation): void
    {
        $adminEmail = Setting::get(
            'admin_notification_email',
            config('mail.admin_address', 'hello@aquila.net.au')
        );

        $status = 'sent';

        try {
            Mail::to($adminEmail)->send(
                new ReservationReceived($reservation)
            );
        } catch (\Throwable $e) {
            $status = 'failed';

            Log::error(
                'Failed to send reservation admin alert: ' .
                $e->getMessage()
            );
        }

        NotificationLog::create([
            'notifiable_type' => Reservation::class,
            'notifiable_id' => $reservation->id,
            'channel' => 'email',
            'recipient' => $adminEmail,
            'message' =>
                "New reservation from {$reservation->name} for " .
                "{$reservation->reservation_date->format('d M Y')}, " .
                "party of {$reservation->party_size}.",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
