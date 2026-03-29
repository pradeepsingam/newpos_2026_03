<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SaaS POS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f4f4ff',
                            100: '#ebeaff',
                            200: '#d8d5ff',
                            300: '#bbb4ff',
                            400: '#9788ff',
                            500: '#735bf8',
                            600: '#5a43e6',
                            700: '#4732c5',
                            800: '#38289e',
                            900: '#30257c',
                        },
                        accent: {
                            400: '#1db4ff',
                            500: '#1396eb',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        panel: '0 18px 45px rgba(99, 102, 241, 0.08)',
                        shell: '0 28px 80px rgba(15, 23, 42, 0.12)',
                    },
                },
            },
        };
    </script>
    <script>
        (() => {
            const storedTheme = localStorage.getItem('saas-pos-theme') || 'light';
            document.documentElement.dataset.theme = storedTheme;
        })();
    </script>
    <style>
        :root {
            --app-bg: #f5f7fb;
            --shell-bg: rgba(255, 255, 255, 0.82);
            --panel-bg: rgba(255, 255, 255, 0.92);
            --panel-alt: rgba(245, 247, 255, 0.95);
            --panel-border: rgba(148, 163, 184, 0.18);
            --sidebar-bg: linear-gradient(180deg, rgba(247, 248, 255, 0.98), rgba(240, 244, 255, 0.96));
            --text-main: #1f2a44;
            --text-soft: #6b7280;
            --text-muted: #94a3b8;
            --surface: #eef2ff;
            --surface-strong: #ffffff;
            --hero-start: #735bf8;
            --hero-end: #1097f0;
            --hero-card: rgba(255, 255, 255, 0.12);
            --hero-card-border: rgba(255, 255, 255, 0.12);
            --hero-text: #f8fbff;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        html[data-theme='dark'] {
            --app-bg: #09111f;
            --shell-bg: rgba(9, 17, 31, 0.9);
            --panel-bg: rgba(13, 23, 39, 0.94);
            --panel-alt: rgba(19, 31, 52, 0.94);
            --panel-border: rgba(148, 163, 184, 0.16);
            --sidebar-bg: linear-gradient(180deg, rgba(11, 18, 32, 0.98), rgba(17, 29, 48, 0.96));
            --text-main: #edf2ff;
            --text-soft: #a8b4cc;
            --text-muted: #7f8ea8;
            --surface: rgba(255, 255, 255, 0.05);
            --surface-strong: rgba(255, 255, 255, 0.08);
            --hero-start: #5b46e5;
            --hero-end: #0f86d8;
            --hero-card: rgba(255, 255, 255, 0.08);
            --hero-card-border: rgba(255, 255, 255, 0.08);
            --hero-text: #f9fbff;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(115, 91, 248, 0.14), transparent 24%),
                radial-gradient(circle at top right, rgba(16, 151, 240, 0.12), transparent 28%),
                var(--app-bg);
            color: var(--text-main);
        }

        .app-shell {
            background: var(--shell-bg);
            border: 1px solid var(--panel-border);
            backdrop-filter: blur(22px);
        }

        .panel {
            background: var(--panel-bg);
            border: 1px solid var(--panel-border);
            box-shadow: 0 18px 45px rgba(99, 102, 241, 0.08);
        }

        .panel-soft {
            background: var(--panel-alt);
            border: 1px solid var(--panel-border);
        }

        .sidebar-gradient {
            background: var(--sidebar-bg);
        }

        .hero-surface {
            background:
                radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.18), transparent 18%),
                radial-gradient(circle at 80% 75%, rgba(255, 255, 255, 0.12), transparent 16%),
                linear-gradient(135deg, var(--hero-start), var(--hero-end));
            color: var(--hero-text);
        }

        .hero-dot-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.14) 1px, transparent 1px);
            background-size: 18px 18px;
        }

        .hero-card {
            background: var(--hero-card);
            border: 1px solid var(--hero-card-border);
        }

        .nav-link {
            color: var(--text-soft);
        }

        .nav-link-active {
            background: rgba(115, 91, 248, 0.12);
            color: #5a43e6;
            border-color: rgba(115, 91, 248, 0.22);
        }

        html[data-theme='dark'] .nav-link-active {
            color: #cdd5ff;
            background: rgba(115, 91, 248, 0.22);
            border-color: rgba(151, 136, 255, 0.26);
        }

        .search-input,
        .form-surface {
            background: var(--surface-strong);
            color: var(--text-main);
            border: 1px solid var(--panel-border);
        }

        .search-input::placeholder,
        .form-surface::placeholder {
            color: var(--text-muted);
        }

        .top-divider {
            border-color: var(--panel-border);
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased">
    @php($currentUser = auth()->user())
    @php($hasPlatformAccess = $currentUser && method_exists($currentUser, 'hasPlatformAccess') && $currentUser->hasPlatformAccess())
    @php($currentBusiness = $currentUser && $currentUser->business_id ? \App\Models\Business::query()->find($currentUser->business_id) : null)
    @php($subscriptionInactive = $currentBusiness ? ! $currentBusiness->refreshSubscriptionStatus() : false)

    <div class="min-h-screen px-3 py-3 sm:px-5 sm:py-5">
        <div class="app-shell mx-auto flex min-h-[calc(100vh-1.5rem)] max-w-[1600px] flex-col overflow-hidden rounded-[32px] shadow-shell sm:min-h-[calc(100vh-2.5rem)]">
            <div class="top-divider flex items-center gap-3 border-b px-4 py-3 sm:px-6">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-[#ff5f57]"></span>
                    <span class="h-3 w-3 rounded-full bg-[#febc2e]"></span>
                    <span class="h-3 w-3 rounded-full bg-[#28c840]"></span>
                </div>
                <div class="mx-auto hidden max-w-xl flex-1 rounded-xl border border-slate-200/70 bg-white/70 px-4 py-2 text-center text-sm text-slate-400 sm:block">
                    {{ parse_url(config('app.url'), PHP_URL_HOST) ?: request()->getHost() }}
                </div>
            </div>

            <div class="flex min-h-0 flex-1 flex-col lg:flex-row">
                <aside class="sidebar-gradient top-divider border-b px-4 py-5 lg:min-h-0 lg:w-[270px] lg:border-b-0 lg:border-r lg:px-5 lg:py-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-accent-500 text-lg font-bold text-white shadow-panel">
                            SP
                        </div>
                        <div>
                            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight text-[color:var(--text-main)]">SaaS POS</a>
                            <p class="text-xs uppercase tracking-[0.28em] text-[color:var(--text-muted)]">Retail Studio</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2 overflow-x-auto pb-1 lg:hidden">
                        @if (! $hasPlatformAccess && ! $subscriptionInactive)
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Dashboard</a>
                            <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">POS</a>
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Products</a>
                            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Customers</a>
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Sales</a>
                            <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Settings</a>
                        @elseif ($hasPlatformAccess)
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Dashboard</a>
                            <a href="{{ route('plugins.index') }}" class="nav-link {{ request()->routeIs('plugins.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Plugins</a>
                            <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'nav-link-active' : 'panel-soft' }} rounded-2xl px-4 py-2.5 text-sm font-medium">Tenants</a>
                        @else
                            <a href="{{ route('subscription.notice') }}" class="nav-link nav-link-active rounded-2xl px-4 py-2.5 text-sm font-medium">Subscription</a>
                        @endif
                    </div>

                    <div class="mt-8 hidden lg:block">
                        <p class="px-3 text-xs font-semibold uppercase tracking-[0.28em] text-[color:var(--text-muted)]">Overview</p>

                        <nav class="mt-4 space-y-2">
                            @if (! $hasPlatformAccess && ! $subscriptionInactive)
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 13h6V4H4zm10 7h6v-9h-6zM4 20h6v-5H4zm10-10h6V4h-6z"/></svg>
                                    </span>
                                    Dashboard
                                </a>
                                <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 5h14v14H5z"/><path d="M8 9h8M8 13h5"/></svg>
                                    </span>
                                    POS Billing
                                </a>
                                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 3l8 4.5-8 4.5-8-4.5z"/><path d="M4 7.5V16.5L12 21 20 16.5V7.5"/></svg>
                                    </span>
                                    Products
                                </a>
                                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
                                    </span>
                                    Customers
                                </a>
                                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    </span>
                                    Sales
                                </a>
                                <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.82-.33 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.7 1.7 0 0 0 .33-1.82 1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.7 1.7 0 0 0 1.82.33h.09A1.7 1.7 0 0 0 10.91 3H11a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.33 1.82v.09A1.7 1.7 0 0 0 21 10.91H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
                                    </span>
                                    Settings
                                </a>
                            @elseif ($hasPlatformAccess)
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 13h6V4H4zm10 7h6v-9h-6zM4 20h6v-5H4zm10-10h6V4h-6z"/></svg>
                                    </span>
                                    Dashboard
                                </a>
                                <a href="{{ route('plugins.index') }}" class="nav-link {{ request()->routeIs('plugins.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2v6m0 8v6m10-10h-6M8 12H2"/><path d="M16 8a4 4 0 1 0-8 0v8a4 4 0 1 0 8 0z"/></svg>
                                    </span>
                                    Plugins
                                </a>
                                <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'nav-link-active' : '' }} flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </span>
                                    Tenants
                                </a>
                            @else
                                <a href="{{ route('subscription.notice') }}" class="nav-link nav-link-active flex items-center gap-3 rounded-2xl border border-transparent px-3 py-3 text-sm font-medium transition">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl panel-soft">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H15a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    </span>
                                    Subscription
                                </a>
                            @endif
                        </nav>
                    </div>

                    <div class="panel mt-8 hidden rounded-[28px] p-4 lg:block">
                        <p class="text-xs uppercase tracking-[0.26em] text-[color:var(--text-muted)]">Workspace</p>
                        <div class="mt-3">
                            <p class="text-sm font-semibold text-[color:var(--text-main)]">{{ $hasPlatformAccess ? 'Platform Console' : ($currentBusiness?->name ?: 'Store Workspace') }}</p>
                            <p class="mt-1 text-sm text-[color:var(--text-soft)]">{{ $hasPlatformAccess ? 'Manage platform rollout and tenant growth.' : 'Tap-friendly retail operations and reporting.' }}</p>
                        </div>
                    </div>

                    <div class="panel mt-6 hidden rounded-[26px] p-4 lg:block">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-accent-500 text-sm font-semibold text-white">
                                {{ strtoupper(substr($currentUser?->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-[color:var(--text-main)]">{{ $currentUser?->name }}</p>
                                <p class="truncate text-xs uppercase tracking-[0.18em] text-[color:var(--text-muted)]">{{ $hasPlatformAccess ? 'Superadmin' : 'Business Owner' }}</p>
                            </div>
                        </div>
                    </div>
                </aside>

                <section class="min-h-0 flex-1">
                    <header class="top-divider border-b px-4 py-4 sm:px-6 sm:py-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div class="flex items-center gap-3">
                                <label class="search-input flex min-w-0 items-center gap-3 rounded-2xl px-4 py-3 shadow-panel xl:w-[320px]">
                                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                                    <input type="text" placeholder="Search anything..." class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400">
                                    <span class="hidden rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-400 sm:inline-flex">⌘K</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-between gap-3 sm:justify-end">
                                <button
                                    id="theme-toggle"
                                    type="button"
                                    class="panel-soft rounded-2xl px-4 py-2.5 text-sm font-medium text-[color:var(--text-soft)] transition hover:border-brand-200 hover:text-brand-600"
                                >
                                    Dark Mode
                                </button>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2.5 text-sm font-medium text-white shadow-panel transition hover:opacity-95"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="px-4 py-5 sm:px-6 sm:py-6">
                        @if (session('status'))
                            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </main>
                </section>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const toggle = document.getElementById('theme-toggle');
            if (!toggle) return;

            const syncLabel = () => {
                const theme = document.documentElement.dataset.theme || 'light';
                toggle.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
            };

            toggle.addEventListener('click', () => {
                const nextTheme = (document.documentElement.dataset.theme || 'light') === 'dark' ? 'light' : 'dark';
                document.documentElement.dataset.theme = nextTheme;
                localStorage.setItem('saas-pos-theme', nextTheme);
                syncLabel();
            });

            syncLabel();
        })();
    </script>
</body>
</html>
