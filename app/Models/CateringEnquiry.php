<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'event_date',
        'guest_count',
        'event_type',
        'package_id',
        'message',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    const STATUSES = ['new', 'contacted', 'confirmed', 'declined', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * AQL-35: a confirmed enquiry is a booked event.
     */
    public function isBooked(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * AQL-35/AQL-34: is another event already confirmed for this date?
     */
    public function dateAlreadyBooked(): bool
    {
        
        return static::where('status', 'confirmed')
            ->whereDate('event_date', $this->event_date)
            ->where('id', '!=', $this->id)
            ->exists();
    }
}