<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('name');
    }

    public function availableItems()
    {
        return $this->hasMany(MenuItem::class)->where('is_available', true)->orderBy('name');
    }
}
