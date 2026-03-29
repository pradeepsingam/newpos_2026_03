<?php

namespace App\Support\Plugins;

use App\Models\Business;
use App\Models\PluginVersion;
use App\Models\TenantPluginMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PluginTenantMigrationRunner
{
    public function runForTenant(Business $business, PluginVersion $pluginVersion): void
    {
        $manifest = PluginManifest::fromFile($pluginVersion->package_path . DIRECTORY_SEPARATOR . 'plugin.json');
        $migrationPath = $manifest->migrationsPath();

        if ($migrationPath === null || ! is_dir($migrationPath)) {
            return;
        }

        $files = collect(File::files($migrationPath))
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->sortBy(fn ($file) => $file->getFilename())
            ->values();

        foreach ($files as $file) {
            $migrationName = $file->getFilename();

            $alreadyRan = TenantPluginMigration::query()
                ->withoutGlobalScopes()
                ->where('business_id', $business->id)
                ->where('plugin_version_id', $pluginVersion->id)
                ->where('migration', $migrationName)
                ->exists();

            if ($alreadyRan) {
                continue;
            }

            PluginTenantSchema::for($business, $pluginVersion, function () use ($file, $business, $pluginVersion, $migrationName): void {
                $migration = require $file->getRealPath();

                if (! $migration instanceof Migration) {
                    return;
                }

                DB::transaction(function () use ($migration, $business, $pluginVersion, $migrationName): void {
                    $migration->up();

                    TenantPluginMigration::query()->withoutGlobalScopes()->create([
                        'business_id' => $business->id,
                        'plugin_version_id' => $pluginVersion->id,
                        'migration' => $migrationName,
                        'ran_at' => now(),
                    ]);
                });
            });
        }
    }
}
