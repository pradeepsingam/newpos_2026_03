<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->business_id) {
            abort(403, 'User is not assigned to a business.');
        }

        $business = Business::query()->find($user->business_id);

        if (! $business) {
            abort(404, 'Business not found.');
        }

        if ($business->subscriptionExpired()) {
            return redirect()
                ->route('subscription.notice')
                ->with('status', 'Please renew your subscription to continue using the system.');
        }

        return $next($request);
    }
}
