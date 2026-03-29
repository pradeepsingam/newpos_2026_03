<?php

namespace App\Support\Plugins;

use App\Support\Tenant;

class PluginTenantData
{
    public static function businessId(): ?int
    {
        return Tenant::id();
    }

    public static function table(string $table): string
    {
        return PluginTenantSchema::table($table);
    }
}
