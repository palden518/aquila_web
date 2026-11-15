<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Carbon;

/**
 * Reads the restaurant's opening hours from Settings, in one place,
 * so the booking form and the booking checks always agree.
 */
class OpeningHours
{
    public const DAYS = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    /** Opening time as "HH:MM", e.g. "07:00". */
    public static function openingTime(): string
    {
        return Setting::get('reservation_opening_time', '07:00');
    }

    /** Closing time as "HH:MM", e.g. "15:00". */
    public static function closingTime(): string
    {
        return Setting::get('reservation_closing_time', '15:00');
    }

    /** How long each booking holds a table, in minutes. */
    public static function tableMinutes(): int
    {
        return (int) Setting::get('reservation_table_minutes', '120');
    }

    /**
     * The latest time a table can be booked: closing time minus table time.
     * E.g. closing 3:00 PM with 2-hour tables = last booking 1:00 PM.
     */
    public static function lastBookingTime(): string
    {
        return Carbon::createFromFormat('H:i', self::closingTime())
            ->subMinutes(self::tableMinutes())
            ->format('H:i');
    }

    /** Days the restaurant is closed, e.g. ['saturday', 'sunday']. */
    public static function closedDays(): array
    {
        $saved = Setting::get('reservation_closed_days', 'saturday,sunday');

        return array_values(array_filter(explode(',', $saved)));
    }

    public static function isClosedOn(Carbon $date): bool
    {
        return in_array(strtolower($date->format('l')), self::closedDays(), true);
    }

    /** Turns "13:00" into "1:00 PM". */
    public static function nice(string $time): string
    {
        return Carbon::createFromFormat('H:i', $time)->format('g:i A');
    }

    /**
     * A short sentence for customers, e.g.
     * "We take bookings Monday to Friday, 7:00 AM to 1:00 PM."
     */
    public static function summary(): string
    {
        $openDays = array_values(array_diff_key(self::DAYS, array_flip(self::closedDays())));

        if (count($openDays) === 0) {
            return 'We are not taking table bookings at the moment.';
        }

        if (count($openDays) === 7) {
            $days = 'every day';
        } elseif (self::areNextToEachOther($openDays)) {
            $days = count($openDays) === 1 ? $openDays[0] : $openDays[0] . ' to ' . end($openDays);
        } else {
            $days = implode(', ', array_slice($openDays, 0, -1)) . ' and ' . end($openDays);
        }

        return 'We take bookings ' . $days . ', ' . self::nice(self::openingTime()) . ' to ' . self::nice(self::lastBookingTime()) . '.';
    }

    private static function areNextToEachOther(array $openDays): bool
    {
        $positions = array_map(fn ($day) => array_search($day, array_values(self::DAYS), true), $openDays);

        return end($positions) - $positions[0] === count($positions) - 1;
    }
}
