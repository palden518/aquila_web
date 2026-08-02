<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasPermission
{
    /**
     * Usage in routes: ->middleware('permission:manage menu')
     * Matches the permission names seeded in the `permissions` table.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || ! $user->can($permission)) {
            abort(403, "You don't have permission to do that ({$permission}).");
        }

        return $next($request);
    }
}
