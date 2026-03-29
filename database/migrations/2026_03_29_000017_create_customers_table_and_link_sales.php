<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->index('business_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('business_id')->constrained('customers')->nullOnDelete();
        });

        $sales = DB::table('sales')
            ->where('is_walking_customer', false)
            ->whereNotNull('customer_name')
            ->orderBy('id')
            ->get();

        foreach ($sales as $sale) {
            $existingCustomer = DB::table('customers')
                ->where('business_id', $sale->business_id)
                ->where('name', $sale->customer_name)
                ->where(function ($query) use ($sale) {
                    if ($sale->customer_phone) {
                        $query->where('phone', $sale->customer_phone);
                    } else {
                        $query->whereNull('phone');
                    }
                })
                ->first();

            $customerId = $existingCustomer?->id;

            if (! $customerId) {
                $customerId = DB::table('customers')->insertGetId([
                    'business_id' => $sale->business_id,
                    'name' => $sale->customer_name,
                    'phone' => $sale->customer_phone,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('sales')
                ->where('id', $sale->id)
                ->update(['customer_id' => $customerId]);
        }
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
