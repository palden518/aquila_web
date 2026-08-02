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
        'message',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
    ];

    const STATUSES = ['new', 'contacted', 'confirmed', 'declined', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
