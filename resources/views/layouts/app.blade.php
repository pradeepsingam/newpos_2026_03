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
                        obsidian: '#0B0B0B',
                        gold: '#D4AF37',
                        ivory: '#F8F6F0',
                    },
                    boxShadow: {
                        glow: '0 20px 60px rgba(0, 0, 0, 0.35)',
                    },
                    letterSpacing: {
                        wideplus: '0.14em',
                    },
                },
            },
        };
    </script>
    <script>
        (() => {
            const storedTheme = localStorage.getItem('saas-pos-theme') || 'dark';
            document.documentElement.dataset.theme = storedTheme;
        })();
    </script>
    <style>
        html[data-theme='light'] body {
            background: #f6f1e7 !important;
            color: #111827 !important;
        }

        html[data-theme='light'] .text-ivory,
        html[data-theme='light'] .text-white\/80,
        html[data-theme='light'] .text-white\/75,
        html[data-theme='light'] .text-white\/70,
        html[data-theme='light'] .text-white\/65,
        html[data-theme='light'] .text-white\/60,
        html[data-theme='light'] .text-white\/55,
        html[data-theme='light'] .text-white\/50,
        html[data-theme='light'] .text-white\/45,
        html[data-theme='light'] .text-white\/40 {
            color: #334155 !important;
        }

        html[data-theme='light'] .bg-white\/5,
        html[data-theme='light'] .bg-black\/25,
        html[data-theme='light'] .bg-black\/20,
        html[data-theme='light'] .bg-black\/30 {
            background: rgba(255, 255, 255, 0.9) !important;
        }

        html[data-theme='light'] .border-white\/10,
        html[data-theme='light'] .border-white\/15 {
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        html[data-theme='light'] header {
            background: rgba(255, 255, 255, 0.8) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        html[data-theme='light'] input,
        html[data-theme='light'] select,
        html[data-theme='light'] textarea {
            background: #ffffff !important;
            color: #0f172a !important;
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        html[data-theme='light'] input::placeholder,
        html[data-theme='light'] textarea::placeholder {
            color: #94a3b8 !important;
        }

        html[data-theme='light'] .bg-red-500\/10 {
            background: rgba(254, 226, 226, 0.9) !important;
        }

        html[data-theme='light'] .shadow-glow,
        html[data-theme='light'] .shadow-2xl,
        html[data-theme='light'] .shadow-xl,
        html[data-theme='light'] .shadow-sm {
            box-shadow: none !important;
        }

        html[data-theme='light'] .backdrop-blur-xl,
        html[data-theme='light'] .backdrop-blur-2xl {
            backdrop-filter: none !important;
        }
    </style>
</head>
<body class="min-h-screen bg-obsidian text-ivory antialiased">
    @php($currentBusiness = auth()->check() && auth()->user()->business_id ? \App\Models\Business::query()->find(auth()->user()->business_id) : null)
    @php($subscriptionInactive = $currentBusiness ? ! $currentBusiness->refreshSubscriptionStatus() : false)

    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(212,175,55,0.18),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(255,255,255,0.08),_transparent_24%)]"></div>

        <header class="relative border-b border-white/10 bg-black/30 backdrop-blur-xl">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-5 sm:px-6">
                <div class="space-y-3">
                    <div>
                        <a href="{{ route('dashboard') }}" class="text-lg font-semibold uppercase tracking-[0.18em] text-ivory">SaaS POS</a>
                        <p class="mt-1 text-xs uppercase tracking-wideplus text-gold/80">Modern retail operations</p>
                    </div>

                    <nav class="flex flex-wrap gap-2 text-sm text-white/80">
                        @if (auth()->check() && (! method_exists(auth()->user(), 'hasPlatformAccess') || ! auth()->user()->hasPlatformAccess()) && ! $subscriptionInactive)
                            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Dashboard</a>
                            <a href="{{ route('pos.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">POS</a>
                            <a href="{{ route('products.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Products</a>
                            <a href="{{ route('customers.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Customers</a>
                            <a href="{{ route('sales.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Sales</a>
                            <a href="{{ route('settings.edit') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Settings</a>
                        @endif
                        @if (auth()->check() && (! method_exists(auth()->user(), 'hasPlatformAccess') || ! auth()->user()->hasPlatformAccess()) && $subscriptionInactive)
                            <a href="{{ route('subscription.notice') }}" class="rounded-xl border border-gold/35 bg-gold/10 px-3 py-2 text-gold transition hover:bg-gold/20">Subscription</a>
                        @endif
                        @if (auth()->check() && method_exists(auth()->user(), 'hasPlatformAccess') && auth()->user()->hasPlatformAccess())
                            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Dashboard</a>
                            <a href="{{ route('plugins.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Plugins</a>
                            <a href="{{ route('tenants.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 transition hover:border-gold/40 hover:text-gold">Tenants</a>
                        @endif
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        id="theme-toggle"
                        type="button"
                        class="rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-white/75 transition hover:border-gold/40 hover:text-gold"
                    >
                        Light Mode
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-xl border border-gold/35 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold transition hover:bg-gold/20"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="relative mx-auto max-w-6xl px-4 py-8 sm:px-6">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-gold/25 bg-gold/10 px-4 py-3 text-sm text-gold">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        (() => {
            const toggle = document.getElementById('theme-toggle');
            if (!toggle) return;

            const syncLabel = () => {
                const theme = document.documentElement.dataset.theme || 'dark';
                toggle.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
            };

            toggle.addEventListener('click', () => {
                const nextTheme = (document.documentElement.dataset.theme || 'dark') === 'dark' ? 'light' : 'dark';
                document.documentElement.dataset.theme = nextTheme;
                localStorage.setItem('saas-pos-theme', nextTheme);
                syncLabel();
            });

            syncLabel();
        })();
    </script>
</body>
</html>
