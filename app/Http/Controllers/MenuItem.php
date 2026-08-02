<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
        'is_weekly_special',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_weekly_special' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * Formatted price, e.g. "$28".
     */
    public function getDisplayPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price, 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeWeeklySpecial($query)
    {
        return $query->where('is_weekly_special', true);
    }
}
