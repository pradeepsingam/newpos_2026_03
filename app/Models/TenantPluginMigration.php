<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantPluginMigration extends Model
{
    use HasFactory;
    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'plugin_version_id',
        'migration',
        'ran_at',
    ];

    protected $casts = [
        'ran_at' => 'datetime',
    ];

    public function pluginVersion(): BelongsTo
    {
        return $this->belongsTo(PluginVersion::class);
    }
}
