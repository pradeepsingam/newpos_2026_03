<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_plugin_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plugin_version_id')->constrained()->cascadeOnDelete();
            $table->string('migration');
            $table->timestamp('ran_at');
            $table->timestamps();

            $table->unique(['business_id', 'plugin_version_id', 'migration']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_plugin_migrations');
    }
};
