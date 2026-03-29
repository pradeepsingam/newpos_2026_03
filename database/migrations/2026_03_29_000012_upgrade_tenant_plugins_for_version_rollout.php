<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_plugins', function (Blueprint $table) {
            $table->foreignId('plugin_version_id')->nullable()->after('plugin_id')->constrained('plugin_versions')->nullOnDelete();
            $table->foreignId('enabled_by')->nullable()->after('plugin_version_id')->constrained('users')->nullOnDelete();
            $table->timestamp('disabled_at')->nullable()->after('activated_at');
            $table->index(['business_id', 'plugin_version_id']);
        });
    }

    public function down(): void
    {
        Schema::table('tenant_plugins', function (Blueprint $table) {
            $table->dropForeign(['plugin_version_id']);
            $table->dropForeign(['enabled_by']);
            $table->dropIndex(['business_id', 'plugin_version_id']);
            $table->dropColumn(['plugin_version_id', 'enabled_by', 'disabled_at']);
        });
    }
};
