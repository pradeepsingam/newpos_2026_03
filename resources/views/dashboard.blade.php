@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (($mode ?? 'tenant') === 'platform')
        <section class="space-y-6">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
                <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Platform overview</p>
                <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <h1 class="text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">
                            Superadmin Dashboard
                        </h1>
                        <p class="mt-3 text-sm leading-7 text-white/60 sm:text-base">
                            Track tenant growth, store count, and subscription health from one control surface.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a
                            href="{{ route('tenants.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-gold px-5 py-3.5 text-sm font-semibold uppercase tracking-[0.16em] text-black transition hover:bg-[#e6c766]"
                        >
                            Manage Tenants
                        </a>
                        <a
                            href="{{ route('plugins.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-5 py-3.5 text-sm font-semibold uppercase tracking-[0.16em] text-white/80 transition hover:border-gold/40 hover:text-gold"
                        >
                            Manage Plugins
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Tenants</p>
                    <p class="mt-3 text-3xl font-semibold text-gold">{{ $tenantCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Total tenant businesses on the platform</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Stores</p>
                    <p class="mt-3 text-3xl font-semibold text-ivory">{{ $storeCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Business owner accounts currently provisioned</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Active</p>
                    <p class="mt-3 text-3xl font-semibold text-emerald-200">{{ $activeCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Tenants with a valid active subscription</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Inactive</p>
                    <p class="mt-3 text-3xl font-semibold text-rose-200">{{ $inactiveCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Tenants that need subscription renewal</p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1.15fr_0.85fr]">
                <a href="{{ route('tenants.index') }}" class="rounded-[28px] border border-gold/20 bg-[linear-gradient(135deg,rgba(212,175,55,0.16),rgba(255,255,255,0.02))] p-6 shadow-glow transition hover:border-gold/40">
                    <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Tenant operations</p>
                    <h2 class="mt-3 text-2xl font-semibold tracking-[0.06em] text-ivory">Create, update, and renew tenants</h2>
                    <p class="mt-3 max-w-md text-sm leading-7 text-white/60">Manage subscription dates, store ownership, and activation status from the tenant management screen.</p>
                </a>

                <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Platform actions</p>
                    <div class="mt-4 grid gap-3">
                        <a href="{{ route('tenants.index') }}" class="rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/80 transition hover:border-gold/40 hover:text-gold">Open tenant management</a>
                        <a href="{{ route('plugins.index') }}" class="rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/80 transition hover:border-gold/40 hover:text-gold">Open plugin management</a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="space-y-6">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl sm:p-8">
                <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Business overview</p>
                <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <h1 class="text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">
                            {{ $businessName ?: 'Your business' }}
                        </h1>
                        <p class="mt-3 text-sm leading-7 text-white/60 sm:text-base">
                            A calm, touch-friendly workspace for billing customers, tracking products, and reviewing sales without clutter.
                        </p>
                    </div>

                    <a
                        href="{{ route('pos.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-gold px-5 py-3.5 text-sm font-semibold uppercase tracking-[0.16em] text-black transition hover:bg-[#e6c766]"
                    >
                        Open POS
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Today</p>
                    <p class="mt-3 text-3xl font-semibold text-gold">{{ number_format((float) $todaySalesTotal, 2) }}</p>
                    <p class="mt-2 text-sm text-white/55">Total sales for {{ now()->format('d M Y') }}</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Products</p>
                    <p class="mt-3 text-3xl font-semibold text-ivory">{{ $productCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Active products in catalog</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Sales</p>
                    <p class="mt-3 text-3xl font-semibold text-ivory">{{ $salesCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Completed bills so far</p>
                </div>

                <div class="rounded-[24px] border border-white/10 bg-white/5 p-5 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Low stock</p>
                    <p class="mt-3 text-3xl font-semibold {{ $lowStockCount > 0 ? 'text-gold' : 'text-ivory' }}">{{ $lowStockCount }}</p>
                    <p class="mt-2 text-sm text-white/55">Products at 5 units or below</p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
                <a href="{{ route('pos.index') }}" class="rounded-[28px] border border-gold/20 bg-[linear-gradient(135deg,rgba(212,175,55,0.16),rgba(255,255,255,0.02))] p-6 shadow-glow transition hover:border-gold/40">
                    <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Fast lane</p>
                    <h2 class="mt-3 text-2xl font-semibold tracking-[0.06em] text-ivory">Premium billing flow</h2>
                    <p class="mt-3 max-w-md text-sm leading-7 text-white/60">Open the sales screen optimized for quick taps, clean totals, and smooth cart updates during busy hours.</p>
                </a>

                <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-glow backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.18em] text-white/45">Quick actions</p>
                    <div class="mt-4 grid gap-3">
                        <a href="{{ route('products.create') }}" class="rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/80 transition hover:border-gold/40 hover:text-gold">Add a new product</a>
                        <a href="{{ route('sales.index') }}" class="rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/80 transition hover:border-gold/40 hover:text-gold">Review sales history</a>
                        <a href="{{ route('products.index') }}" class="rounded-xl border border-white/10 bg-black/30 px-4 py-4 text-sm text-white/80 transition hover:border-gold/40 hover:text-gold">Manage product catalog</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
