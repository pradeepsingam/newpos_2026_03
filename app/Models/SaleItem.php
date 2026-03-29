<?php

namespace App\Models;

use App\Support\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('business', function (Builder $builder): void {
            if (Tenant::check()) {
                $builder->whereHas('sale', function (Builder $saleQuery): void {
                    $saleQuery->where('business_id', Tenant::id());
                });
            }
        });
    }

    public function scopeForBusiness(Builder $query, int $businessId): Builder
    {
        return $query->withoutGlobalScope('business')->whereHas('sale', function (Builder $saleQuery) use ($businessId): void {
            $saleQuery->where('business_id', $businessId);
        });
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
