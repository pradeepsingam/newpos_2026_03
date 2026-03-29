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
            $table->string('customer_name')->nullable()->after('payment_method');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->boolean('is_walking_customer')->default(false)->after('customer_phone');
            $table->decimal('amount_paid', 12, 2)->default(0)->after('is_walking_customer');
            $table->decimal('balance_amount', 12, 2)->default(0)->after('amount_paid');
        });

        DB::table('sales')->update([
            'customer_name' => 'Walking Customer',
            'customer_phone' => null,
            'is_walking_customer' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'is_walking_customer',
                'amount_paid',
                'balance_amount',
            ]);
        });
    }
};
