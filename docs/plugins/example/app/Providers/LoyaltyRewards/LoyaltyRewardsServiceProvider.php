<?php

namespace Plugins\LoyaltyRewards;

use App\Support\Plugins\PluginServiceProvider;

class LoyaltyRewardsServiceProvider extends PluginServiceProvider
{
    public static function pluginSlug(): string
    {
        return 'loyalty-rewards';
    }

    public static function routeFile(): ?string
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';
    }

    public static function viewsPath(): ?string
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
    }

    public static function routePrefix(): string
    {
        return 'loyalty';
    }
}
