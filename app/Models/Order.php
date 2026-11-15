<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'type',
        'status',
        'subtotal',
        'total',
        'notes',
        'pickup_time',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'pickup_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function addons()
    {
        return $this->hasMany(OrderAddon::class);
    }

    /** Sum of the add-ons on this order. Needs `addons` loaded to avoid an extra query. */
    public function getAddonsTotalAttribute(): float
    {
        return round((float) $this->addons->sum('subtotal'), 2);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /** The card (Stripe) payment for this order, if it has one. Needs `payments` loaded. */
    public function cardPayment(): ?Payment
    {
        return $this->payments->where('method', 'card')->sortByDesc('id')->first();
    }

    /**
     * pay_on_pickup | awaiting | paid | failed | refunded
     * Orders without a card payment row are pay-on-pickup orders.
     */
    public function paymentState(): string
    {
        $payment = $this->cardPayment();

        if (! $payment) {
            return 'pay_on_pickup';
        }

        return match ($payment->status) {
            'paid' => 'paid',
            'refunded' => 'refunded',
            'failed' => 'failed',
            default => 'awaiting',
        };
    }

    public function paymentLabel(): string
    {
        return [
            'pay_on_pickup' => 'Pay on pickup',
            'awaiting' => 'Awaiting card payment',
            'paid' => 'Paid by card',
            'failed' => 'Card payment failed',
            'refunded' => 'Refunded',
        ][$this->paymentState()];
    }

    /**
     * Orders the kitchen should act on: everything except card orders that
     * haven't been paid (still on Stripe's page, abandoned, or failed).
     */
    public function scopeKitchenVisible($query)
    {
        return $query->whereDoesntHave('payments', function ($q) {
            $q->where('method', 'card')->whereIn('status', ['pending', 'failed']);
        });
    }

    public function getItemCountAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    /**
     * Generates an unguessable, human-friendly order number, e.g. AQ-7K3M9PQX.
     * The number doubles as the key for the public confirmation page, so it
     * is random rather than sequential.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'AQ-' . strtoupper(\Illuminate\Support\Str::random(8));
            $number = str_replace(['0', 'O', '1', 'I'], ['7', 'K', '9', 'M'], $number);
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
