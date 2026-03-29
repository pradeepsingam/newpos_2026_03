<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Plugin;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        if ($user?->hasPlatformAccess()) {
            $businesses = Business::query()->get();
            $businesses->each->refreshSubscriptionStatus();
            $recentTenants = Business::query()
                ->with('owner')
                ->latest()
                ->take(5)
                ->get();
            $expiringSoonCount = $businesses
                ->filter(fn (Business $business) => $business->is_active
                    && $business->subscription_ends_at
                    && Carbon::parse($business->subscription_ends_at)->between(now(), now()->copy()->addDays(7)))
                ->count();

            return view('dashboard', [
                'mode' => 'platform',
                'userName' => $user->name,
                'tenantCount' => $businesses->count(),
                'storeCount' => User::query()
                    ->where('role', User::ROLE_BUSINESS_OWNER)
                    ->count(),
                'activeCount' => $businesses->where('is_active', true)->count(),
                'inactiveCount' => $businesses->where('is_active', false)->count(),
                'pluginCount' => Plugin::query()->count(),
                'expiringSoonCount' => $expiringSoonCount,
                'recentTenants' => $recentTenants,
            ]);
        }

        $business = Business::query()->find($user?->business_id);
        $productCount = Product::query()->count();
        $todaySalesTotal = Sale::query()
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');
        $salesCount = Sale::query()->count();
        $lowStockCount = Product::query()
            ->where('stock', '<=', 5)
            ->count();
        $customerCount = Customer::query()->count();
        $averageSale = $salesCount > 0 ? round($todaySalesTotal / max(Sale::query()->whereDate('created_at', now()->toDateString())->count(), 1), 2) : 0;
        $recentSales = Sale::query()
            ->latest()
            ->take(5)
            ->get();
        $monthlySales = $this->buildMonthlySalesTrend();
        $topProducts = Product::query()
            ->orderBy('stock')
            ->take(4)
            ->get();

        return view('dashboard', [
            'mode' => 'tenant',
            'userName' => $user?->name,
            'businessName' => $business?->name,
            'productCount' => $productCount,
            'todaySalesTotal' => $todaySalesTotal,
            'salesCount' => $salesCount,
            'lowStockCount' => $lowStockCount,
            'customerCount' => $customerCount,
            'averageSale' => $averageSale,
            'recentSales' => $recentSales,
            'monthlySales' => $monthlySales,
            'topProducts' => $topProducts,
            'pointsRate' => $business?->points_percentage ?? 0,
        ]);
    }

    protected function buildMonthlySalesTrend(): Collection
    {
        return collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                $date = now()->copy()->subMonths($monthsAgo);
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();

                return [
                    'label' => $date->format('M'),
                    'total' => (float) Sale::query()
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('total_amount'),
                ];
            })
            ->push([
                'label' => now()->format('M'),
                'total' => (float) Sale::query()
                    ->whereBetween('created_at', [now()->copy()->startOfMonth(), now()->copy()->endOfMonth()])
                    ->sum('total_amount'),
            ]);
    }
}
