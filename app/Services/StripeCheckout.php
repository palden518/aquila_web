<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class StripeCheckout
{
    protected string $apiUrl = 'https://api.stripe.com/v1';

    public function isConfigured(): bool
    {
        return filled(config('aquila.stripe.secret'));
    }

    public function cardEnabled(): bool
    {
        return $this->isConfigured()
            && Setting::get('orders_card_enabled', '1') !== '0';
    }

    public function currency(): string
    {
        return config('aquila.stripe.currency', 'aud');
    }

    protected function request()
    {
        $secret = config('aquila.stripe.secret');

        if (blank($secret)) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        return Http::asForm()
            ->withBasicAuth($secret, '')
            ->acceptJson()
            ->timeout(30);
    }

    /**
     * Create a hosted Stripe Checkout Session.
     */
    public function createSession(Order $order): object
    {
        $order->loadMissing('items', 'addons');

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'quantity' => (int) $item->quantity,
                'price_data' => [
                    'currency' => $this->currency(),
                    'unit_amount' => $this->cents($item->unit_price),
                    'product_data' => [
                        'name' => $item->item_name,
                    ],
                ],
            ];
        }

        foreach ($order->addons as $addon) {
            $amount = $this->cents($addon->unit_price);

            if ($amount <= 0) {
                continue;
            }

            $lineItems[] = [
                'quantity' => (int) $addon->quantity,
                'price_data' => [
                    'currency' => $this->currency(),
                    'unit_amount' => $amount,
                    'product_data' => [
                        'name' => $addon->addon_name . ' (add-on)',
                    ],
                ],
            ];
        }

        if (empty($lineItems)) {
            throw new RuntimeException('The order has no payable items.');
        }

        $reference = [
            'order_number' => $order->order_number,
            'order_id' => (string) $order->id,
        ];

        $payload = [
            'mode' => 'payment',
            'line_items' => $lineItems,
            'client_reference_id' => $order->order_number,
            'customer_email' => $order->customer_email,
            'locale' => 'auto',

            'success_url' => route('orders.payment.return', [
                'order' => $order->order_number,
            ]) . '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route('orders.confirmation', [
                'order' => $order->order_number,
            ]) . '?payment=cancelled',

            'expires_at' => now()->addMinutes(35)->timestamp,

            'metadata' => $reference,

            'payment_intent_data' => [
                'description' => 'Aquila order ' . $order->order_number,
                'metadata' => $reference,
            ],
        ];

        $response = $this->request()->post(
            $this->apiUrl . '/checkout/sessions',
            $payload
        );

        $response->throw();

        return json_decode($response->body());
    }

    /**
     * Retrieve an existing Checkout Session from Stripe.
     */
     public function retrieveSession(string $sessionId): object
        {
            if (! preg_match('/^cs_(test|live)_/', $sessionId)) {
                throw new RuntimeException('Invalid Stripe Checkout Session ID.');
            }

            $response = $this->request()->get(
                $this->apiUrl . '/checkout/sessions/' . rawurlencode($sessionId)
            );

            $response->throw();

            return json_decode($response->body());
        }
    /**
     * Check that a Checkout Session belongs to this order
     * and has the correct amount and currency.
     */
    public function sessionMatchesOrder(object $session, Order $order): bool
    {
        return ($session->client_reference_id ?? null) === $order->order_number
            && (string) ($session->metadata->order_id ?? '') === (string) $order->id
            && (int) ($session->amount_total ?? 0) === $this->cents($order->total)
            && strtolower((string) ($session->currency ?? '')) === $this->currency();
    }

    /**
     * Verify the Stripe webhook signature and decode its event.
     */
    public function constructWebhookEvent(
        string $payload,
        ?string $signature
    ): object {
        $secret = config('aquila.stripe.webhook_secret');

        if (blank($secret) || blank($signature)) {
            throw new RuntimeException('Stripe webhook secret or signature is missing.');
        }

        $parts = [];

        foreach (explode(',', $signature) as $part) {
            $pair = explode('=', trim($part), 2);

            if (count($pair) === 2) {
                $parts[$pair[0]][] = $pair[1];
            }
        }

        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];

        if (! $timestamp || empty($signatures)) {
            throw new RuntimeException('Invalid Stripe signature header.');
        }

        // Reject signatures older than five minutes.
        if (abs(time() - (int) $timestamp) > 300) {
            throw new RuntimeException('Stripe webhook timestamp is too old.');
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        $valid = false;

        foreach ($signatures as $candidate) {
            if (hash_equals($expectedSignature, $candidate)) {
                $valid = true;
                break;
            }
        }

        if (! $valid) {
            throw new RuntimeException('Stripe webhook signature verification failed.');
        }

        $event = json_decode($payload);

        if (
            json_last_error() !== JSON_ERROR_NONE
            || ! is_object($event)
            || empty($event->type)
        ) {
            throw new RuntimeException('Invalid Stripe webhook JSON.');
        }

        return $event;
    }

    /**
     * Extract the PaymentIntent ID from a Checkout Session.
     */
    public function paymentIntentId(object $session): ?string
    {
        $intent = $session->payment_intent ?? null;

        if (is_object($intent)) {
            return $intent->id ?? null;
        }

        return is_string($intent) ? $intent : null;
    }

    protected function cents($amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
