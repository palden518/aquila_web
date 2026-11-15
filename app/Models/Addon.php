<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    public const MAX_QUANTITY = 10;

    protected $fillable = ['name', 'description', 'price', 'is_available', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function getDisplayPriceAttribute(): string
    {
        return (float) $this->price > 0 ? '$' . number_format((float) $this->price, 2) : 'Free';
    }
}
