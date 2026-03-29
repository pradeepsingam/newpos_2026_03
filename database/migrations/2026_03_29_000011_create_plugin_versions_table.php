<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugin_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plugin_id')->constrained()->cascadeOnDelete();
            $table->string('version', 191);
            $table->string('provider_class');
            $table->string('package_path');
            $table->string('package_checksum');
            $table->string('signature');
            $table->json('manifest');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['plugin_id', 'version']);
            $table->index(['plugin_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugin_versions');
    }
};
