<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        if (! $user->business_id) {
            abort(403, 'User is not assigned to a business.');
        }

        app()->instance('current_business_id', (int) $user->business_id);

        return $next($request);
    }
}
