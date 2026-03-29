<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'points_percentage',
        'subscription_package',
        'subscription_starts_at',
        'subscription_ends_at',
        'is_active',
        'owner_id',
    ];

    protected function casts(): array
    {
        return [
            'subscription_starts_at' => 'date',
            'subscription_ends_at' => 'date',
            'points_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function tenantPlugins(): HasMany
    {
        return $this->hasMany(TenantPlugin::class);
    }

    public function plugins(): HasManyThrough
    {
        return $this->hasManyThrough(
            Plugin::class,
            TenantPlugin::class,
            'business_id',
            'id',
            'id',
            'plugin_id'
        );
    }

    public function tenantPluginMigrations(): HasMany
    {
        return $this->hasMany(TenantPluginMigration::class);
    }

    public function refreshSubscriptionStatus(): bool
    {
        if (! $this->subscription_ends_at) {
            return (bool) $this->is_active;
        }

        $shouldBeActive = $this->isSubscriptionWithinPeriod();

        if ($this->is_active !== $shouldBeActive) {
            $this->forceFill([
                'is_active' => $shouldBeActive,
            ])->save();
        }

        return $shouldBeActive;
    }

    public function subscriptionExpired(): bool
    {
        return ! $this->refreshSubscriptionStatus();
    }

    public function isSubscriptionWithinPeriod(): bool
    {
        if (! $this->subscription_ends_at) {
            return (bool) $this->is_active;
        }

        return $this->subscription_ends_at->greaterThanOrEqualTo(Carbon::today());
    }
}
