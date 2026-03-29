<?php

namespace App\Support\Plugins;

use App\Models\PluginVersion;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PluginRuntimeRegistry
{
    public static function bootApprovedProviders(): void
    {
        if (! Schema::hasTable('plugin_versions')) {
            return;
        }

        PluginVersion::query()
            ->where('is_approved', true)
            ->with('plugin')
            ->get()
            ->each(function (PluginVersion $pluginVersion): void {
                try {
                    $manifest = PluginManifest::fromFile($pluginVersion->package_path . DIRECTORY_SEPARATOR . 'plugin.json');
                } catch (Throwable) {
                    return;
                }

                if ($manifest->providersPath() !== null) {
                    PluginAutoloader::register('Plugins\\', $manifest->providersPath());
                }

                $providerClass = $pluginVersion->provider_class;

                if (! class_exists($providerClass)) {
                    return;
                }

                $provider = app($providerClass);

                if ($provider instanceof PluginServiceProvider) {
                    $provider->register();
                    $provider->bootPlugin();
                }
            });
    }
}
