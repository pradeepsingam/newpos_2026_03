<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Plugin::class);

        $businesses = Business::query()
            ->with('owner')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $businesses->each->refreshSubscriptionStatus();

        return view('tenants.index', [
            'businesses' => $businesses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', Plugin::class);

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'owner_password' => ['required', 'confirmed', Password::defaults()],
            'subscription_package' => ['required', 'string', 'max:255'],
            'subscription_starts_at' => ['required', 'date'],
            'subscription_ends_at' => ['required', 'date', 'after_or_equal:subscription_starts_at'],
        ]);

        $business = DB::transaction(function () use ($data) {
            $business = Business::query()->create([
                'name' => $data['business_name'],
                'subscription_package' => $data['subscription_package'],
                'subscription_starts_at' => $data['subscription_starts_at'],
                'subscription_ends_at' => $data['subscription_ends_at'],
                'is_active' => true,
            ]);

            $owner = User::withoutGlobalScopes()->create([
                'business_id' => $business->id,
                'role' => User::ROLE_BUSINESS_OWNER,
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => Hash::make($data['owner_password']),
            ]);

            $business->update([
                'owner_id' => $owner->id,
            ]);

            return $business;
        });

        $business->refreshSubscriptionStatus();

        return redirect()
            ->route('tenants.index')
            ->with('status', "{$business->name} was created and is ready for onboarding.");
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        $this->authorize('viewAny', Plugin::class);

        $business->loadMissing('owner');

        $owner = $business->owner;

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . ($owner?->id ?? 'NULL'),
            ],
            'owner_password' => ['nullable', 'confirmed', Password::defaults()],
            'subscription_package' => ['required', 'string', 'max:255'],
            'subscription_starts_at' => ['required', 'date'],
            'subscription_ends_at' => ['required', 'date', 'after_or_equal:subscription_starts_at'],
        ]);

        DB::transaction(function () use ($business, $owner, $data) {
            $business->update([
                'name' => $data['business_name'],
                'subscription_package' => $data['subscription_package'],
                'subscription_starts_at' => $data['subscription_starts_at'],
                'subscription_ends_at' => $data['subscription_ends_at'],
            ]);

            if ($owner) {
                $owner->fill([
                    'name' => $data['owner_name'],
                    'email' => $data['owner_email'],
                ]);

                if (! empty($data['owner_password'])) {
                    $owner->password = Hash::make($data['owner_password']);
                }

                $owner->save();
            }

            $business->refreshSubscriptionStatus();
        });

        return redirect()
            ->route('tenants.index')
            ->with('status', "{$business->name} was updated successfully.");
    }

    public function destroy(Business $business): RedirectResponse
    {
        $this->authorize('viewAny', Plugin::class);

        $business->loadMissing('owner');

        if (
            $business->products()->exists() ||
            $business->sales()->exists() ||
            $business->tenantPlugins()->exists() ||
            $business->tenantPluginMigrations()->exists() ||
            $business->users()->whereKeyNot($business->owner_id)->exists()
        ) {
            return redirect()
                ->route('tenants.index')
                ->with('status', 'This business cannot be deleted because it already has business data.');
        }

        $businessName = $business->name;

        try {
            DB::transaction(function () use ($business) {
                if ($business->owner) {
                    $business->owner->delete();
                }

                $business->delete();
            });
        } catch (QueryException) {
            return redirect()
                ->route('tenants.index')
                ->with('status', 'This business cannot be deleted because it is linked to existing records.');
        }

        return redirect()
            ->route('tenants.index')
            ->with('status', "{$businessName} was deleted successfully.");
    }
}
