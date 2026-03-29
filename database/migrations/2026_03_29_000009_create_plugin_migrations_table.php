<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugin_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plugin_id')->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('migration_path')->nullable();
            $table->timestamp('ran_at');
            $table->timestamps();

            $table->unique(['plugin_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugin_migrations');
    }
};
