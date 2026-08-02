<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'reservation_date',
        'reservation_time',
        'party_size',
        'notes',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'party_size' => 'integer',
    ];

    const STATUSES = ['new', 'confirmed', 'seated', 'completed', 'cancelled'];
}
