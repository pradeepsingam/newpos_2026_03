<?php

namespace App\Support\Plugins;

use App\Models\Business;
use App\Models\Plugin;
use App\Models\PluginVersion;
use App\Models\TenantPlugin;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ZipArchive;

class PluginManager
{
    public function __construct(
        protected PluginSignatureValidator $signatureValidator,
        protected PluginTenantMigrationRunner $tenantMigrationRunner
    ) {
    }

    public function installGlobalPackage(UploadedFile $uploadedFile, User $approvedBy): PluginVersion
    {
        if (strtolower($uploadedFile->getClientOriginalExtension()) !== 'zip') {
            throw ValidationException::withMessages([
                'plugin_zip' => 'Please upload a valid ZIP plugin package.',
            ]);
        }

        $tempRoot = storage_path('app/plugin-temp/' . Str::uuid());
        $finalRoot = storage_path('app/plugins');
        $packageChecksum = hash_file('sha256', $uploadedFile->getRealPath());

        File::ensureDirectoryExists($tempRoot);
        File::ensureDirectoryExists($finalRoot);

        $archive = new ZipArchive();

        if ($archive->open($uploadedFile->getRealPath()) !== true) {
            throw ValidationException::withMessages([
                'plugin_zip' => 'The ZIP plugin package could not be opened.',
            ]);
        }

        $archive->extractTo($tempRoot);
        $archive->close();

        $manifestFile = $this->locateManifest($tempRoot);
        $manifest = PluginManifest::fromFile($manifestFile);
        $manifest->validatePackageStructure();
        $this->signatureValidator->validate($manifest);

        return DB::transaction(function () use ($manifest, $manifestFile, $tempRoot, $finalRoot, $packageChecksum, $approvedBy) {
            $plugin = Plugin::query()->firstOrCreate(
                ['slug' => $manifest->slug()],
                [
                    'name' => $manifest->name(),
                    'description' => $manifest->description(),
                ]
            );

            $plugin->update([
                'name' => $manifest->name(),
                'description' => $manifest->description(),
            ]);

            $duplicateVersion = PluginVersion::query()
                ->where('plugin_id', $plugin->id)
                ->where('version', $manifest->version())
                ->exists();

            if ($duplicateVersion) {
                throw ValidationException::withMessages([
                    'plugin_zip' => 'This plugin version already exists.',
                ]);
            }

            $versionPath = $finalRoot . DIRECTORY_SEPARATOR . $manifest->slug() . DIRECTORY_SEPARATOR . $manifest->version();
            File::ensureDirectoryExists(dirname($versionPath));

            if (File::exists($versionPath)) {
                throw ValidationException::withMessages([
                    'plugin_zip' => 'This plugin version directory already exists.',
                ]);
            }

            File::moveDirectory(dirname($manifestFile), $versionPath);
            File::deleteDirectory($tempRoot);

            return PluginVersion::create([
                'plugin_id' => $plugin->id,
                'version' => $manifest->version(),
                'provider_class' => $manifest->provider(),
                'package_path' => $versionPath,
                'package_checksum' => $packageChecksum,
                'signature' => $manifest->signature(),
                'manifest' => $manifest->all(),
                'is_approved' => true,
                'approved_by' => $approvedBy->id,
                'approved_at' => now(),
            ]);
        });
    }

    public function assignVersionToBusiness(PluginVersion $pluginVersion, Business $business, User $enabledBy): TenantPlugin
    {
        $tenantPlugin = TenantPlugin::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'business_id' => $business->id,
                'plugin_id' => $pluginVersion->plugin_id,
            ],
            [
                'plugin_version_id' => $pluginVersion->id,
                'enabled_by' => $enabledBy->id,
                'status' => 'active',
                'installed_version' => $pluginVersion->version,
                'activated_at' => now(),
                'disabled_at' => null,
            ]
        );

        $this->tenantMigrationRunner->runForTenant($business, $pluginVersion);

        return $tenantPlugin;
    }

    public function disableForBusiness(Plugin $plugin, Business $business): void
    {
        TenantPlugin::query()
            ->withoutGlobalScopes()
            ->where('business_id', $business->id)
            ->where('plugin_id', $plugin->id)
            ->update([
                'status' => 'inactive',
                'disabled_at' => now(),
            ]);
    }

    protected function locateManifest(string $tempRoot): string
    {
        $pluginJsonFiles = collect(File::allFiles($tempRoot))
            ->filter(fn ($file) => strtolower($file->getFilename()) === 'plugin.json')
            ->values();

        if ($pluginJsonFiles->count() !== 1) {
            throw ValidationException::withMessages([
                'plugin_zip' => 'The ZIP must contain exactly one plugin.json manifest.',
            ]);
        }

        return $pluginJsonFiles->first()->getRealPath();
    }
}
