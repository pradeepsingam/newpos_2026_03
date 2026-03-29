<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantPlugin extends Model
{
    use HasFactory;
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'plugin_id',
        'plugin_version_id',
        'enabled_by',
        'status',
        'installed_version',
        'activated_at',
        'disabled_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(PluginVersion::class, 'plugin_version_id');
    }

    public function enabledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enabled_by');
    }
}
