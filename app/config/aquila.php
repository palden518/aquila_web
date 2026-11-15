<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe (online card payments at checkout)
    |--------------------------------------------------------------------------
    | Secrets live in .env, never in the database or the repository.
    | With no STRIPE_SECRET set, checkout simply offers "pay at pickup" only.
    */
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => strtolower(env('STRIPE_CURRENCY', 'aud')),
    ],

];
