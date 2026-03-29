<?php

namespace App\Policies;

use App\Models\Plugin;
use App\Models\User;

class PluginPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPlatformAccess();
    }

    public function upload(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function assign(User $user, Plugin $plugin): bool
    {
        return $user->hasPlatformAccess();
    }

    public function deactivate(User $user, Plugin $plugin): bool
    {
        return $user->hasPlatformAccess();
    }
}
