<?php

namespace App\Support\Plugins;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

abstract class PluginServiceProvider extends ServiceProvider
{
    abstract public static function pluginSlug(): string;

    public static function routeFile(): ?string
    {
        return null;
    }

    public static function viewsPath(): ?string
    {
        return null;
    }

    public static function routePrefix(): string
    {
        return static::pluginSlug();
    }

    public function bootPlugin(): void
    {
        if (static::viewsPath()) {
            View::addNamespace('plugin-' . static::pluginSlug(), static::viewsPath());
        }

        if (static::routeFile() && is_file(static::routeFile())) {
            Route::middleware(['auth', \App\Http\Middleware\EnsureBusinessContext::class, \App\Http\Middleware\EnsurePluginActive::class . ':' . static::pluginSlug()])
                ->prefix('plugins/' . static::routePrefix())
                ->name('plugins.' . static::pluginSlug() . '.')
                ->group(static::routeFile());
        }
    }
}
