<?php

namespace App\Http\Controllers;

use App\Mail\ReservationReceived;
use App\Mail\ReservationRequestReceived;
use App\Models\NotificationLog;
use App\Models\Reservation;
use App\Models\Setting;
use App\Support\OpeningHours;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // The largest group we take online is the restaurant's capacity from Settings.
        $maxGuests = (int) Setting::get('reservation_max_guests', '40');
        $phone = Setting::get('site_phone', '044 792 5110');

        /** @var \Illuminate\Validation\Validator $validator */

        $validator = validator($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'reservation_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'party_size' => ['required', 'integer', 'min:1', 'max:' . $maxGuests],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'reservation_date.after_or_equal' => 'Please choose today or a future date.',
            'reservation_date.date_format' => 'Please choose a valid date.',
            'reservation_time.date_format' => 'Please choose a valid time.',
            'party_size.max' => "Sorry, we can't take a group of more than {$maxGuests} online. Please call us on {$phone} and we'll do our best to help.",
        ]);

        // Once the date and time look right, check them against opening hours.
        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->hasAny(['reservation_date', 'reservation_time'])) {
                return;
            }

            if ($message = $this->openingHoursProblem($request->input('reservation_date'), $request->input('reservation_time'))) {
                $validator->errors()->add('reservation_time', $message);
            }
        });

        $validated = $validator->validateWithBag('reservation');
        $validated['status'] = 'new';

        // Only one booking for the same date is checked and saved at a time,
        // so two people submitting together cannot both take the last seats.
        try {
            $reservation = Cache::lock('reservation-capacity:' . $validated['reservation_date'], 10)
                ->block(5, function () use ($validated) {
                    if (! $this->hasRoomFor($validated)) {
                        return null;
                    }

                    return Reservation::create($validated);
                });
        } catch (LockTimeoutException $e) {
            return $this->rejectBooking('We are taking a lot of bookings right now. Please try again in a moment.');
        }

        if (! $reservation) {
            return $this->rejectBooking($this->fullyBookedMessage($validated));
        }

        $this->notifyAdmin($reservation);
        $this->notifyCustomer($reservation);

        return back()
            ->with(
                'reservation_success',
                "Thanks, {$reservation->name} — your table request has been received. We'll confirm by email or phone shortly."
            )
            ->withFragment('reserve');
    }

    /**
     * Check the booking day and time against the opening hours in Settings.
     * Returns a message for the customer, or null if the time is fine.
     */
    protected function openingHoursProblem(string $date, string $time): ?string
    {
        $day = Carbon::createFromFormat('Y-m-d', $date);
        $opening = OpeningHours::openingTime();
        $lastBooking = OpeningHours::lastBookingTime();

        if (OpeningHours::isClosedOn($day)) {
            return "Sorry, we're closed on {$day->format('l')}s. " . OpeningHours::summary();
        }

        // Times are "HH:MM" in 24-hour format, so they compare correctly as text.
        if ($time < $opening || $time > $lastBooking) {
            return 'Please choose a time between ' . OpeningHours::nice($opening) . ' and ' . OpeningHours::nice($lastBooking)
                . '. We close at ' . OpeningHours::nice(OpeningHours::closingTime()) . '.';
        }

        if ($day->isToday() && $time <= now()->format('H:i')) {
            return 'That time has already passed today. Please choose a later time or another day.';
        }

        return null;
    }

    /**
     * Check whether the restaurant has enough seats for this booking.
     *
     * Each booking holds a table for the "table time" set in Settings, starting
     * at its booking time. We look at the busiest moment while the new group
     * would be seated and make sure the total guests never go over capacity.
     * Cancelled bookings do not count towards capacity.
     */
    protected function hasRoomFor(array $booking): bool
    {
        $maxGuests = (int) Setting::get('reservation_max_guests', '40');
        $tableMinutes = (int) Setting::get('reservation_table_minutes', '120');

        // Work in minutes after midnight, e.g. 6:30 PM = 1110.
        $toMinutes = fn (string $time) => (int) Carbon::parse($time)->format('G') * 60 + (int) Carbon::parse($time)->format('i');

        $newStart = $toMinutes($booking['reservation_time']);
        $newEnd = $newStart + $tableMinutes;

        $otherBookings = Reservation::query()
            ->whereDate('reservation_date', $booking['reservation_date'])
            ->where('status', '!=', 'cancelled')
            ->get(['reservation_time', 'party_size'])
            ->map(fn (Reservation $r) => [
                'start' => $toMinutes($r->reservation_time),
                'guests' => (int) $r->party_size,
            ]);

        // The restaurant is busiest either when the new group arrives,
        // or when another group arrives while they are still seated.
        $momentsToCheck = $otherBookings
            ->pluck('start')
            ->filter(fn ($start) => $start > $newStart && $start < $newEnd)
            ->push($newStart);

        foreach ($momentsToCheck as $moment) {
            $seatedAtThisMoment = $otherBookings
                ->filter(fn ($b) => $b['start'] <= $moment && $moment < $b['start'] + $tableMinutes)
                ->sum('guests');

            if ($seatedAtThisMoment + (int) $booking['party_size'] > $maxGuests) {
                return false;
            }
        }

        return true;
    }

    protected function fullyBookedMessage(array $booking): string
    {
        $phone = Setting::get('site_phone', '044 792 5110');

        $when = Carbon::parse($booking['reservation_date'] . ' ' . $booking['reservation_time'])->format('g:i A \o\n l j F');

        return "Sorry, we're fully booked around {$when}. Please try another time, or call us on {$phone}.";
    }

    /**
     * Send the customer back to the form with a message, keeping what they typed.
     */
    protected function rejectBooking(string $message): RedirectResponse
    {
        return back()
            ->withErrors(['reservation_time' => $message], 'reservation')
            ->withInput()
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

    /**
     * Email the customer to confirm we have received their table request.
     * If sending fails, the booking is still kept and the failure is logged.
     */
    protected function notifyCustomer(Reservation $reservation): void
    {
        $status = 'sent';

        try {
            Mail::to($reservation->email)->send(new ReservationRequestReceived($reservation));
        } catch (\Throwable $e) {
            $status = 'failed';
            Log::error('Failed to send reservation confirmation to customer: ' . $e->getMessage());
        }

        NotificationLog::create([
            'notifiable_type' => Reservation::class,
            'notifiable_id' => $reservation->id,
            'channel' => 'email',
            'recipient' => $reservation->email,
            'message' => "Booking request received email to {$reservation->name} for {$reservation->reservation_date->format('d M Y')}, party of {$reservation->party_size}.",
            'status' => $status,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
