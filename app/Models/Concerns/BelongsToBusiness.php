<?php

namespace App\Models\Concerns;

use App\Support\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToBusiness
{
    protected static function bootBelongsToBusiness(): void
    {
        static::creating(function ($model): void {
            if (empty($model->business_id) && Tenant::check()) {
                $model->business_id = Tenant::id();
            }
        });

        static::addGlobalScope('business', function (Builder $builder): void {
            if (Tenant::check()) {
                $builder->where(
                    $builder->getModel()->getTable() . '.business_id',
                    Tenant::id()
                );
            }
        });
    }

    public function scopeForBusiness(Builder $query, int $businessId): Builder
    {
        return $query->withoutGlobalScope('business')->where(
            $query->getModel()->getTable() . '.business_id',
            $businessId
        );
    }
}
