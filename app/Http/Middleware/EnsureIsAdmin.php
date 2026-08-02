<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Only lets authenticated users with the "access admin panel"
     * permission (Owner or Manager role) through to /admin/*.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->canAccessAdmin()) {
            abort(403, 'You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
