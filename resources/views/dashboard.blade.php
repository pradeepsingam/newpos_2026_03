@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (($mode ?? 'tenant') === 'platform')
        <section class="space-y-5">
            <div class="hero-surface hero-dot-pattern overflow-hidden rounded-[30px] px-6 py-7 sm:px-8 sm:py-9">
                <div class="relative z-10 flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-white/70">Platform overview</p>
                        <h1 class="mt-4 text-3xl font-semibold tracking-tight sm:text-5xl">Good morning, {{ $userName }}</h1>
                        <p class="mt-3 max-w-xl text-sm leading-7 text-white/75 sm:text-base">
                            Track tenant growth, subscription health, and rollout readiness from one calm control center.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 xl:w-[520px]">
                        <div class="hero-card rounded-[24px] p-5 backdrop-blur-sm">
                            <p class="text-sm text-white/70">Total tenants</p>
                            <p class="mt-3 text-4xl font-semibold">{{ $tenantCount }}</p>
                            <p class="mt-2 text-sm text-emerald-100/80">{{ $activeCount }} active accounts live</p>
                        </div>
                        <div class="hero-card rounded-[24px] p-5 backdrop-blur-sm">
                            <p class="text-sm text-white/70">Approved plugins</p>
                            <p class="mt-3 text-4xl font-semibold">{{ $pluginCount }}</p>
                            <p class="mt-2 text-sm text-sky-100/80">{{ $expiringSoonCount }} tenants expiring in 7 days</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Tenants</p>
                    <p class="mt-4 text-4xl font-semibold text-[color:var(--text-main)]">{{ $tenantCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">All subscribed businesses on the platform.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Owner accounts</p>
                    <p class="mt-4 text-4xl font-semibold text-[color:var(--text-main)]">{{ $storeCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Business owners able to access each store.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Active</p>
                    <p class="mt-4 text-4xl font-semibold text-emerald-500">{{ $activeCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Tenants with a valid active subscription.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Inactive</p>
                    <p class="mt-4 text-4xl font-semibold text-rose-500">{{ $inactiveCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Accounts that need renewal or attention.</p>
                </div>
            </div>

            <div class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
                <div class="panel rounded-[30px] p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Subscription health</h2>
                            <p class="mt-1 text-sm text-[color:var(--text-soft)]">Live breakdown of platform subscription status.</p>
                        </div>
                        <a href="{{ route('tenants.index') }}" class="text-sm font-medium text-brand-600">Open tenants</a>
                    </div>

                    @php
                        $platformTotal = max($tenantCount, 1);
                        $activeWidth = round(($activeCount / $platformTotal) * 100, 1);
                        $inactiveWidth = round(($inactiveCount / $platformTotal) * 100, 1);
                        $expiringWidth = round(($expiringSoonCount / $platformTotal) * 100, 1);
                    @endphp

                    <div class="mt-8 h-4 overflow-hidden rounded-full bg-slate-100">
                        <div class="flex h-full">
                            <div class="h-full bg-gradient-to-r from-brand-500 to-brand-400" style="width: {{ $activeWidth }}%"></div>
                            <div class="h-full bg-sky-400" style="width: {{ $expiringWidth }}%"></div>
                            <div class="h-full bg-orange-400" style="width: {{ $inactiveWidth }}%"></div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        <div class="panel-soft rounded-2xl px-4 py-4">
                            <p class="text-sm font-semibold text-[color:var(--text-main)]">Active</p>
                            <p class="mt-2 text-sm text-[color:var(--text-soft)]">{{ $activeCount }} businesses currently billing normally.</p>
                        </div>
                        <div class="panel-soft rounded-2xl px-4 py-4">
                            <p class="text-sm font-semibold text-[color:var(--text-main)]">Expiring soon</p>
                            <p class="mt-2 text-sm text-[color:var(--text-soft)]">{{ $expiringSoonCount }} businesses expire within 7 days.</p>
                        </div>
                        <div class="panel-soft rounded-2xl px-4 py-4">
                            <p class="text-sm font-semibold text-[color:var(--text-main)]">Inactive</p>
                            <p class="mt-2 text-sm text-[color:var(--text-soft)]">{{ $inactiveCount }} businesses need follow-up or renewal.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="panel rounded-[30px] p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Recent tenants</h2>
                                <p class="mt-1 text-sm text-[color:var(--text-soft)]">Latest businesses added to the workspace.</p>
                            </div>
                            <a href="{{ route('tenants.index') }}" class="text-sm font-medium text-brand-600">View all</a>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse ($recentTenants as $tenant)
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-[color:var(--text-main)]">{{ $tenant->name }}</p>
                                        <p class="mt-1 truncate text-sm text-[color:var(--text-soft)]">{{ $tenant->owner?->email ?? 'No owner email' }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tenant->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-[color:var(--text-soft)]">No tenants created yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel rounded-[30px] p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-[color:var(--text-muted)]">Quick actions</p>
                        <div class="mt-5 grid gap-3">
                            <a href="{{ route('tenants.index') }}" class="panel-soft rounded-2xl px-4 py-4 text-sm font-medium text-[color:var(--text-main)] transition hover:border-brand-200 hover:text-brand-600">Open tenant management</a>
                            <a href="{{ route('plugins.index') }}" class="panel-soft rounded-2xl px-4 py-4 text-sm font-medium text-[color:var(--text-main)] transition hover:border-brand-200 hover:text-brand-600">Open plugin manager</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @php
            $maxMonthlyTotal = max(collect($monthlySales)->max('total'), 1);
            $pointCoordinates = collect($monthlySales)
                ->values()
                ->map(function ($item, $index) use ($monthlySales, $maxMonthlyTotal) {
                    $count = max(count($monthlySales) - 1, 1);
                    $x = 24 + ($index * (520 / $count));
                    $y = 190 - (($item['total'] / $maxMonthlyTotal) * 150);
                    return round($x, 1).','.round($y, 1);
                })
                ->implode(' ');
        @endphp

        <section class="space-y-5">
            <div class="hero-surface hero-dot-pattern overflow-hidden rounded-[30px] px-6 py-7 sm:px-8 sm:py-9">
                <div class="relative z-10 flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-white/70">Retail workspace</p>
                        <h1 class="mt-4 text-3xl font-semibold tracking-tight sm:text-5xl">Good morning, {{ $userName }}</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-white/75 sm:text-base">
                            {{ $businessName ?: 'Your business' }} is ready for billing, customer loyalty tracking, and quick inventory decisions.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 xl:w-[540px]">
                        <div class="hero-card rounded-[24px] p-5 backdrop-blur-sm">
                            <p class="text-sm text-white/70">Today revenue</p>
                            <p class="mt-3 text-4xl font-semibold">{{ number_format((float) $todaySalesTotal, 2) }}</p>
                            <p class="mt-2 text-sm text-emerald-100/80">{{ $salesCount }} completed bills in your workspace</p>
                        </div>
                        <div class="hero-card rounded-[24px] p-5 backdrop-blur-sm">
                            <p class="text-sm text-white/70">Loyalty points</p>
                            <p class="mt-3 text-4xl font-semibold">{{ number_format((float) $pointsRate, 0) }}%</p>
                            <p class="mt-2 text-sm text-sky-100/80">Current earning rate applied to customer purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Today</p>
                    <p class="mt-4 text-4xl font-semibold text-[color:var(--text-main)]">{{ number_format((float) $todaySalesTotal, 2) }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Revenue recorded for {{ now()->format('d M Y') }}.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Products</p>
                    <p class="mt-4 text-4xl font-semibold text-[color:var(--text-main)]">{{ $productCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Items currently available in your catalog.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Customers</p>
                    <p class="mt-4 text-4xl font-semibold text-[color:var(--text-main)]">{{ $customerCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Saved shoppers with loyalty history and phone records.</p>
                </div>
                <div class="panel rounded-[28px] p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--text-muted)]">Low stock</p>
                    <p class="mt-4 text-4xl font-semibold {{ $lowStockCount > 0 ? 'text-amber-500' : 'text-[color:var(--text-main)]' }}">{{ $lowStockCount }}</p>
                    <p class="mt-3 text-sm text-[color:var(--text-soft)]">Products at 5 units or below that may need refilling.</p>
                </div>
            </div>

            <div class="grid gap-5 xl:grid-cols-[1.12fr_0.88fr]">
                <div class="panel rounded-[30px] p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Revenue movement</h2>
                            <p class="mt-1 text-sm text-[color:var(--text-soft)]">Monthly sales trend across the last six months.</p>
                        </div>
                        <a href="{{ route('sales.index') }}" class="text-sm font-medium text-brand-600">View sales</a>
                    </div>

                    <div class="mt-8 overflow-hidden rounded-[28px] panel-soft px-4 py-5 sm:px-6">
                        <div class="relative h-[260px]">
                            <div class="absolute inset-0 grid grid-rows-4 gap-y-0">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="border-b border-dashed border-slate-200/80"></div>
                                @endfor
                            </div>

                            <svg viewBox="0 0 580 220" class="relative z-10 h-full w-full overflow-visible">
                                <defs>
                                    <linearGradient id="salesFill" x1="0%" x2="0%" y1="0%" y2="100%">
                                        <stop offset="0%" stop-color="rgba(115,91,248,0.24)" />
                                        <stop offset="100%" stop-color="rgba(115,91,248,0.02)" />
                                    </linearGradient>
                                </defs>
                                <polyline
                                    points="{{ $pointCoordinates }}"
                                    fill="none"
                                    stroke="#735bf8"
                                    stroke-width="4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <polygon
                                    points="{{ $pointCoordinates }} 544,212 24,212"
                                    fill="url(#salesFill)"
                                />
                            </svg>
                        </div>

                        <div class="mt-4 grid grid-cols-7 gap-2 text-center text-xs font-medium uppercase tracking-[0.18em] text-[color:var(--text-muted)]">
                            @foreach ($monthlySales as $item)
                                <div>{{ $item['label'] }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="panel rounded-[30px] p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Recent sales</h2>
                                <p class="mt-1 text-sm text-[color:var(--text-soft)]">Latest billing activity across your store.</p>
                            </div>
                            <a href="{{ route('sales.index') }}" class="text-sm font-medium text-brand-600">View all</a>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse ($recentSales as $sale)
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-[color:var(--text-main)]">
                                            {{ $sale->customer_name ?: 'Walking Customer' }}
                                        </p>
                                        <p class="mt-1 text-sm text-[color:var(--text-soft)]">
                                            {{ $sale->created_at?->format('d M Y, h:i A') }} • {{ ucfirst($sale->payment_method) }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-semibold text-brand-600">{{ number_format((float) $sale->total_amount, 2) }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-[color:var(--text-soft)]">No sales recorded yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="panel rounded-[30px] p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Quick actions</h2>
                                <p class="mt-1 text-sm text-[color:var(--text-soft)]">Shortcuts for the most common store tasks.</p>
                            </div>
                            <a href="{{ route('pos.index') }}" class="text-sm font-medium text-brand-600">Open POS</a>
                        </div>

                        <div class="mt-5 grid gap-3">
                            <a href="{{ route('pos.index') }}" class="panel-soft rounded-2xl px-4 py-4 text-sm font-medium text-[color:var(--text-main)] transition hover:border-brand-200 hover:text-brand-600">Create a new bill</a>
                            <a href="{{ route('products.create') }}" class="panel-soft rounded-2xl px-4 py-4 text-sm font-medium text-[color:var(--text-main)] transition hover:border-brand-200 hover:text-brand-600">Add a product</a>
                            <a href="{{ route('customers.index') }}" class="panel-soft rounded-2xl px-4 py-4 text-sm font-medium text-[color:var(--text-main)] transition hover:border-brand-200 hover:text-brand-600">Review customers and points</a>
                        </div>
                    </div>

                    <div class="panel rounded-[30px] p-6">
                        <h2 class="text-2xl font-semibold text-[color:var(--text-main)]">Low stock watch</h2>
                        <p class="mt-1 text-sm text-[color:var(--text-soft)]">Products that should be checked before the next rush.</p>

                        <div class="mt-5 space-y-3">
                            @forelse ($topProducts as $product)
                                <div class="panel-soft flex items-center justify-between rounded-2xl px-4 py-4">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-[color:var(--text-main)]">{{ $product->name }}</p>
                                        <p class="mt-1 text-sm text-[color:var(--text-soft)]">Current stock level</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->stock <= 5 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $product->stock }} left
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-[color:var(--text-soft)]">No products available yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
