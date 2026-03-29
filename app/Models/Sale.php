<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'customer_id',
        'subtotal_amount',
        'total_amount',
        'redeemed_points',
        'earned_points',
        'payment_method',
        'customer_name',
        'customer_phone',
        'is_walking_customer',
        'amount_paid',
        'balance_amount',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'redeemed_points' => 'integer',
        'earned_points' => 'integer',
        'is_walking_customer' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
