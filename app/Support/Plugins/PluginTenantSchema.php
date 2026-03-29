<?php

namespace App\Support\Plugins;

use App\Models\Business;
use App\Models\PluginVersion;
use Illuminate\Support\Facades\Schema;

class PluginTenantSchema
{
    protected static ?Business $business = null;
    protected static ?PluginVersion $pluginVersion = null;

    public static function for(Business $business, PluginVersion $pluginVersion, callable $callback): mixed
    {
        $previousBusiness = static::$business;
        $previousVersion = static::$pluginVersion;

        static::$business = $business;
        static::$pluginVersion = $pluginVersion;

        try {
            return $callback();
        } finally {
            static::$business = $previousBusiness;
            static::$pluginVersion = $previousVersion;
        }
    }

    public static function table(string $table): string
    {
        if (! static::$business || ! static::$pluginVersion) {
            return $table;
        }

        return sprintf(
            'tenant_%d_plugin_%s_%s',
            static::$business->id,
            str_replace('-', '_', static::$pluginVersion->plugin->slug),
            $table
        );
    }

    public static function schema(): \Illuminate\Database\Schema\Builder
    {
        return Schema::connection(config('database.default'));
    }
}
