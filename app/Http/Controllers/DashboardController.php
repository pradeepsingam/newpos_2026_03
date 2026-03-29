<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        if ($user?->hasPlatformAccess()) {
            $businesses = Business::query()->get();
            $businesses->each->refreshSubscriptionStatus();

            return view('dashboard', [
                'mode' => 'platform',
                'tenantCount' => $businesses->count(),
                'storeCount' => User::query()
                    ->where('role', User::ROLE_BUSINESS_OWNER)
                    ->count(),
                'activeCount' => $businesses->where('is_active', true)->count(),
                'inactiveCount' => $businesses->where('is_active', false)->count(),
            ]);
        }

        $productCount = Product::query()->count();
        $todaySalesTotal = Sale::query()
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');
        $salesCount = Sale::query()->count();
        $lowStockCount = Product::query()
            ->where('stock', '<=', 5)
            ->count();
        $businessId = $user?->business_id;
        $businessName = optional(Business::query()->find($businessId))->name;

        return view('dashboard', [
            'mode' => 'tenant',
            'businessName' => $businessName,
            'productCount' => $productCount,
            'todaySalesTotal' => $todaySalesTotal,
            'salesCount' => $salesCount,
            'lowStockCount' => $lowStockCount,
        ]);
    }
}
