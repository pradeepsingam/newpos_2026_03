<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('subscription_package')->default('Starter')->after('name');
            $table->date('subscription_starts_at')->nullable()->after('subscription_package');
            $table->date('subscription_ends_at')->nullable()->after('subscription_starts_at');
            $table->boolean('is_active')->default(true)->after('subscription_ends_at');
        });

        DB::table('businesses')->update([
            'subscription_package' => 'Starter',
            'subscription_starts_at' => Carbon::today()->toDateString(),
            'subscription_ends_at' => Carbon::today()->addDays(30)->toDateString(),
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_package',
                'subscription_starts_at',
                'subscription_ends_at',
                'is_active',
            ]);
        });
    }
};
