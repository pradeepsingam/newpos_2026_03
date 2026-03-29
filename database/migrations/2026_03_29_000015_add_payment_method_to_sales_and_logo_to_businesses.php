<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('payment_method')->default('cash')->after('total_amount');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('name');
        });

        DB::table('sales')->update([
            'payment_method' => 'cash',
        ]);
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }
};
