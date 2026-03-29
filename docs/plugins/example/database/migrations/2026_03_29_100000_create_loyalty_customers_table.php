<?php

use App\Support\Plugins\PluginTenantSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        PluginTenantSchema::schema()->create(PluginTenantSchema::table('customers'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->unsignedInteger('points')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        PluginTenantSchema::schema()->dropIfExists(PluginTenantSchema::table('customers'));
    }
};
