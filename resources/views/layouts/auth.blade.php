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
        html[data-theme='light'] .text-white\/60,
        html[data-theme='light'] .text-white\/55,
        html[data-theme='light'] .text-white\/50 {
            color: #334155 !important;
        }

        html[data-theme='light'] .bg-white\/5,
        html[data-theme='light'] .bg-black\/20,
        html[data-theme='light'] .bg-black\/30 {
            background: rgba(255, 255, 255, 0.92) !important;
        }

        html[data-theme='light'] .border-white\/10 {
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        html[data-theme='light'] input {
            background: #ffffff !important;
            color: #0f172a !important;
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        html[data-theme='light'] input::placeholder {
            color: #94a3b8 !important;
        }

        html[data-theme='light'] .shadow-2xl,
        html[data-theme='light'] .shadow-xl,
        html[data-theme='light'] .shadow-sm,
        html[data-theme='light'] .shadow-glow {
            box-shadow: none !important;
        }

        html[data-theme='light'] .backdrop-blur-2xl,
        html[data-theme='light'] .backdrop-blur-xl {
            backdrop-filter: none !important;
        }
    </style>
</head>
<body class="min-h-screen bg-obsidian text-ivory antialiased">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(212,175,55,0.18),_transparent_28%),radial-gradient(circle_at_bottom,_rgba(255,255,255,0.08),_transparent_26%)]"></div>

        <div class="relative w-full max-w-md rounded-[28px] border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur-2xl sm:p-8">
            <div class="mb-6 flex justify-end">
                <button
                    id="theme-toggle"
                    type="button"
                    class="rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-white/75 transition hover:border-gold/40 hover:text-gold"
                >
                    Light Mode
                </button>
            </div>

            <div class="mb-8 text-center">
                <p class="text-xs uppercase tracking-[0.24em] text-gold/80">Sri Lanka SaaS POS</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-[0.08em] text-ivory">@yield('heading')</h1>
                <p class="mt-3 text-sm leading-6 text-white/60">@yield('subheading')</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
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
