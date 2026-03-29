<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        $request->session()->regenerate();

        if (! $request->user()?->business_id && ! $request->user()?->hasPlatformAccess()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account is not linked to a business.',
            ]);
        }

        if ($request->user()->hasPlatformAccess()) {
            return redirect()->intended(route('dashboard'));
        }

        $business = Business::query()->find($request->user()->business_id);

        if ($business && $business->subscriptionExpired()) {
            app()->instance('current_business_id', (int) $business->id);

            return redirect()
                ->route('subscription.notice')
                ->with('status', 'Please renew your subscription to continue using the system.');
        }

        return redirect()->intended(route('dashboard'));
    }
}
