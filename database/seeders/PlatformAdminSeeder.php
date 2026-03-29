<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PlatformAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutGlobalScopes()
            ->where('email', 'platform@saaspos.test')
            ->delete();

        User::withoutGlobalScopes()->updateOrCreate(
            ['email' => 'superadmin@saaspos.test'],
            [
                'business_id' => null,
                'role' => User::ROLE_SUPERADMIN,
                'name' => 'Super Admin',
                'password' => 'password',
            ]
        );
    }
}
