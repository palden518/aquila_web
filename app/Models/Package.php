<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subtitle',
        'slug',
        'description',
        'price',
        'inclusions',
        'image',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * "Price on enquiry" when no price is set, matching the Specials page copy.
     */
    public function getDisplayPriceAttribute(): string
    {
        return is_null($this->price) ? 'Price on enquiry' : '$' . number_format((float) $this->price, 0);
    }

    /**
     * The `inclusions` textarea (one item per line) as a clean array of
     * feature bullets for the Specials page package cards.
     */
    public function getFeaturesAttribute(): array
    {
        if (! $this->inclusions) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $this->inclusions))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
