<?php

namespace App\Http\Middleware;

use App\Models\Plugin;
use App\Models\TenantPlugin;
use App\Support\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePluginActive
{
    public function handle(Request $request, Closure $next, string $pluginSlug): Response
    {
        $plugin = Plugin::query()->where('slug', $pluginSlug)->first();

        if (! $plugin) {
            abort(404);
        }

        $tenantPlugin = TenantPlugin::query()
            ->where('business_id', Tenant::id())
            ->where('plugin_id', $plugin->id)
            ->where('status', 'active')
            ->with('version')
            ->first();

        if (! $tenantPlugin || ! $tenantPlugin->version) {
            abort(403, 'This plugin is not active for your business.');
        }

        app()->instance('current_plugin', $plugin);
        app()->instance('current_plugin_version', $tenantPlugin->version);

        return $next($request);
    }
}
