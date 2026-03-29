<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('points_percentage', 5, 2)->default(0)->after('logo_path');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('points_balance')->default(0)->after('phone');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal_amount', 12, 2)->default(0)->after('customer_id');
            $table->unsignedInteger('redeemed_points')->default(0)->after('subtotal_amount');
            $table->unsignedInteger('earned_points')->default(0)->after('redeemed_points');
        });

        DB::table('sales')->update([
            'subtotal_amount' => DB::raw('total_amount'),
            'redeemed_points' => 0,
            'earned_points' => 0,
        ]);
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_amount',
                'redeemed_points',
                'earned_points',
            ]);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('points_balance');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('points_percentage');
        });
    }
};
