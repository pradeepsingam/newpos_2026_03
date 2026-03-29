<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class Tenant
{
    public static function id(): ?int
    {
        if (app()->bound('current_business_id')) {
            return (int) app('current_business_id');
        }

        if (Auth::check()) {
            return (int) Auth::user()->business_id;
        }

        return null;
    }

    public static function check(): bool
    {
        return static::id() !== null;
    }
}
