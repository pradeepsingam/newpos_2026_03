<?php

namespace App\Providers;

use App\Models\Plugin;
use App\Policies\PluginPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Plugin::class => PluginPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'isSuperadmin') && $user->isSuperadmin()) {
                return true;
            }

            return null;
        });
    }
}
