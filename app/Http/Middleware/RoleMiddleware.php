<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Allow the request through only when the signed-in user holds
     * one of the given roles.
     *
     * Usage in routes: ->middleware('role:admin,technician')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if (! $user->hasRole(...$roles)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
