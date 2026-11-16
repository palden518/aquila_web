<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddon extends Model
{
    protected $fillable = ['order_id', 'addon_id', 'addon_name', 'unit_price', 'quantity', 'subtotal'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
