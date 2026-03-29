<?php

namespace App\Providers;

use App\Support\Plugins\PluginRuntimeRegistry;
use Illuminate\Support\ServiceProvider;

class PluginRuntimeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PluginRuntimeRegistry::bootApprovedProviders();
    }
}
