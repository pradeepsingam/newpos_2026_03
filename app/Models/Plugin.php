<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function tenantPlugins(): HasMany
    {
        return $this->hasMany(TenantPlugin::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PluginVersion::class);
    }

    public function latestApprovedVersion(): HasOne
    {
        return $this->hasOne(PluginVersion::class)->where('is_approved', true)->latestOfMany();
    }

    public function migrations(): HasMany
    {
        return $this->hasMany(PluginMigration::class);
    }
}
