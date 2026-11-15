<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Collection;

/**
 * Session-backed shopping cart.
 *
 * Only menu item IDs and quantities are stored in the session. Names and
 * prices are always read from the database, so a guest can never change a
 * price by tampering with the session or a form, and items the admin has
 * switched off drop out of the cart automatically.
 */
class Cart
{
    public const SESSION_KEY = 'aquila.cart';
    public const MAX_PER_LINE = 20;
    public const MAX_LINES = 30;

    /** @return array<int,int> menu_item_id => quantity */
    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function add(MenuItem $item, int $quantity = 1): void
    {
        $cart = $this->raw();
        $current = $cart[$item->id] ?? 0;

        if ($current === 0 && count($cart) >= self::MAX_LINES) {
            return;
        }

        $cart[$item->id] = min(self::MAX_PER_LINE, $current + max(1, $quantity));
        session([self::SESSION_KEY => $cart]);
    }

    public function setQuantity(int $itemId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$itemId]);
        } elseif (isset($cart[$itemId])) {
            $cart[$itemId] = min(self::MAX_PER_LINE, $quantity);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $itemId): void
    {
        $this->setQuantity($itemId, 0);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /** Total number of units in the cart (for the navbar badge). */
    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    /**
     * Cart lines priced from the database. Unavailable or deleted items are
     * removed from the session as a side effect.
     *
     * @return Collection<int,array{item:MenuItem,quantity:int,unit_price:float,line_total:float}>
     */
    public function lines(): Collection
    {
        $raw = $this->raw();

        if (empty($raw)) {
            return collect();
        }

        $items = MenuItem::available()->whereIn('id', array_keys($raw))->get()->keyBy('id');

        $lines = collect();
        $cleaned = [];

        foreach ($raw as $id => $quantity) {
            $item = $items->get($id);
            if (! $item) {
                continue;
            }

            $cleaned[$id] = $quantity;
            $unitCents = (int) round(((float) $item->price) * 100);

            $lines->push([
                'item' => $item,
                'quantity' => $quantity,
                'unit_price' => $unitCents / 100,
                'line_total' => ($unitCents * $quantity) / 100,
            ]);
        }

        if ($cleaned !== $raw) {
            session([self::SESSION_KEY => $cleaned]);
        }

        return $lines;
    }

    public function subtotal(): float
    {
        return round($this->lines()->sum('line_total'), 2);
    }

    public function isEmpty(): bool
    {
        return $this->lines()->isEmpty();
    }
}
