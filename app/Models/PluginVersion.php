<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PluginVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'plugin_id',
        'version',
        'provider_class',
        'package_path',
        'package_checksum',
        'signature',
        'manifest',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'manifest' => 'array',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tenantPlugins(): HasMany
    {
        return $this->hasMany(TenantPlugin::class);
    }
}
