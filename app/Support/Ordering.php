<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Carbon;

/**
 * Online-ordering rules, all editable from Admin > Website content:
 *  - orders_enabled       "1" or "0" (switch ordering off without a deploy)
 *  - orders_open_time     first pickup time of the day, HH:MM
 *  - orders_close_time    last pickup time of the day, HH:MM
 *  - orders_lead_minutes  minimum minutes between ordering and pickup
 *
 * Times use the app timezone, so set APP_TIMEZONE=Australia/Brisbane.
 */
class Ordering
{
    public const SLOT_MINUTES = 15;
    public const DAYS_AHEAD = 2; // today and tomorrow

    public static function enabled(): bool
    {
        return Setting::get('orders_enabled', '1') !== '0';
    }

    public static function openTime(): string
    {
        return self::validTime(Setting::get('orders_open_time'), '07:00');
    }

    public static function closeTime(): string
    {
        return self::validTime(Setting::get('orders_close_time'), '21:00');
    }

    public static function leadMinutes(): int
    {
        $value = Setting::get('orders_lead_minutes');

        return is_numeric($value) ? max(0, min(240, (int) $value)) : 30;
    }

    /**
     * Pickup slots grouped by day: [ 'Today' => ['2026-11-02 12:30' => '12:30 PM', ...], ... ]
     *
     * @return array<string,array<string,string>>
     */
    public static function slots(?Carbon $now = null): array
    {
        $now = $now ?: Carbon::now();
        $earliest = $now->copy()->addMinutes(self::leadMinutes());

        $groups = [];

        for ($offset = 0; $offset < self::DAYS_AHEAD; $offset++) {
            $day = $now->copy()->startOfDay()->addDays($offset);
            $open = $day->copy()->setTimeFromTimeString(self::openTime());
            $close = $day->copy()->setTimeFromTimeString(self::closeTime());

            if ($close->lte($open)) {
                continue;
            }

            $start = $open->copy();
            if ($earliest->gt($start)) {
                $start = self::roundUp($earliest);
            }

            $times = [];
            for ($t = $start; $t->lte($close); $t = $t->copy()->addMinutes(self::SLOT_MINUTES)) {
                $times[$t->format('Y-m-d H:i')] = $t->format('g:i A');
            }

            if ($times) {
                $label = $offset === 0 ? 'Today' : ($offset === 1 ? 'Tomorrow' : $day->format('l'));
                $groups[$label . ' — ' . $day->format('D j M')] = $times;
            }
        }

        return $groups;
    }

    public static function isValidSlot(?string $value): bool
    {
        if (! $value) {
            return false;
        }

        foreach (self::slots() as $times) {
            if (array_key_exists($value, $times)) {
                return true;
            }
        }

        return false;
    }

    protected static function roundUp(Carbon $time): Carbon
    {
        $time = $time->copy();
        if ($time->second > 0) {
            $time->addMinute();
        }
        $time->second(0);
        $remainder = $time->minute % self::SLOT_MINUTES;

        return $remainder === 0 ? $time : $time->addMinutes(self::SLOT_MINUTES - $remainder);
    }

    protected static function validTime(?string $value, string $fallback): string
    {
        return $value && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value) ? $value : $fallback;
    }
}
