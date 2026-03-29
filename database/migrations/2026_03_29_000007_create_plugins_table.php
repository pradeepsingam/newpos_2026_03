<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191)->unique();
            $table->string('version', 191);
            $table->text('description')->nullable();
            $table->string('package_path');
            $table->json('manifest');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
