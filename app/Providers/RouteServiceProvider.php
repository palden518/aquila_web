<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define route configuration and rate limiters.
     */
    public function boot(): void
    {
        // Existing API rate limiter — unchanged.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | AQL-69: Public Form Rate Limiting
        |--------------------------------------------------------------------------
        |
        | Each form has an independent rate limit.
        | Maximum 5 submissions per minute per IP address.
        |
        */

        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perMinute(5)
                ->by('contact:' . $request->ip());
        });

        RateLimiter::for('reservation-form', function (Request $request) {
            return Limit::perMinute(5)
                ->by('reservation:' . $request->ip());
        });

        RateLimiter::for('catering-form', function (Request $request) {
            return Limit::perMinute(5)
                ->by('catering:' . $request->ip());
        });

        // Existing route registration — unchanged.
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
