<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard') | Coinvest</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface-variant": "#a19baf",
                        "surface-variant": "#353534",
                        "on-surface": "#f5f3f7",
                        "primary": "#8b5cf6",
                        "surface-dim": "#0a0a0b",
                        "background": "#0a0a0b",
                        "surface-container-low": "#151416",
                        "on-background": "#f5f3f7",
                        "primary-container": "#a078ff",
                        "outline": "#7a7485",
                        "surface-container": "#1c1b1f",
                        "on-primary": "#ffffff",
                        "secondary": "#ccbeff",
                        "error": "#ffb4ab",
                        "success-green": "#10B981",
                        "error-red": "#EF4444",
                        "surface-card": "#121114",
                    },
                    "borderRadius": {
                        "DEFAULT": "0.375rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "sans": ["Plus Jakarta Sans", "sans-serif"],
                        "heading": ["Sora", "sans-serif"],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0a0a0b;
            color: #f5f3f7;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Sora', sans-serif;
        }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .btn-primary {
            background: linear-gradient(180deg, #9f7aea 0%, #8b5cf6 100%);
        }
        .container {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        @media (min-width: 768px) {
            .container { padding-left: 2rem; padding-right: 2rem; }
        }
        .glass-panel {
            background: rgba(18, 17, 20, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased bg-background text-on-background selection:bg-primary/30 selection:text-white min-h-screen flex flex-col">
    <div class="fixed inset-0 bg-grid-pattern opacity-40 z-0 pointer-events-none"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-primary/5 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <!-- Navigation -->
    <nav class="sticky top-0 w-full z-40 bg-background/80 backdrop-blur-xl border-b border-white/5 transition-all">
        <div class="container flex justify-between items-center h-20">
            <!-- Brand -->
            <a href="{{ route('landing') }}" class="font-heading text-xl font-bold tracking-tight text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-[0_2px_10px_rgba(245,158,11,0.3)]">
                    <svg viewBox="0 0 100 100" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <defs><linearGradient id="coinvestLgApp" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FDE047"/><stop offset="1" stop-color="#F59E0B"/></linearGradient></defs>
                        <circle cx="50" cy="50" r="34" stroke="url(#coinvestLgApp)" stroke-width="14" stroke-linecap="round" stroke-dasharray="168.5 45.1" transform="rotate(38 50 50)"/>
                        <path d="M38 60 L62 40 M62 40 L52 40 M62 40 L62 50" stroke="url(#coinvestLgApp)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                Coinvest
            </a>
            
            <!-- Links -->
            <div class="hidden md:flex items-center space-x-1">
                <a class="text-sm font-medium tracking-wide transition-colors duration-200 py-1.5 px-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant hover:text-white hover:bg-white/5' }}"
                   href="{{ route('dashboard') }}">Dashboard</a>

                <a class="text-sm font-medium tracking-wide transition-colors duration-200 py-1.5 px-3 rounded-lg {{ request()->routeIs('wallets.*') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant hover:text-white hover:bg-white/5' }}"
                   href="{{ route('wallets.index') }}">Wallets</a>

                <a class="text-sm font-medium tracking-wide transition-colors duration-200 py-1.5 px-3 rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant hover:text-white hover:bg-white/5' }}"
                   href="{{ route('transactions.index') }}">Transaksi</a>

                <a class="text-sm font-medium tracking-wide transition-colors duration-200 py-1.5 px-3 rounded-lg {{ request()->routeIs('market') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant hover:text-white hover:bg-white/5' }}"
                   href="{{ route('market') }}">Pasar Live</a>

                <a class="text-sm font-medium tracking-wide transition-colors duration-200 py-1.5 px-3 rounded-lg {{ request()->routeIs('reports') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant hover:text-white hover:bg-white/5' }}"
                   href="{{ route('reports') }}">Analisis</a>
            </div>

            <!-- Profile / Logout -->
            <div class="flex items-center space-x-4" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-white hover:text-primary transition-colors bg-white/5 px-3 py-2 rounded-xl border border-white/5 focus:outline-none">
                        <span class="material-symbols-outlined text-lg">account_circle</span>
                        <span>{{ Auth::user()->name }}</span>
                        <span class="material-symbols-outlined text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-48 rounded-xl bg-surface-card border border-white/10 shadow-2xl p-2 z-50 text-left"
                         style="display: none;">
                        <span class="block px-3 py-2 text-xs text-on-surface-variant border-b border-white/5 mb-1">{{ Auth::user()->email }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-error hover:bg-error/10 hover:text-red-400 rounded-lg transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow py-8 relative z-10">
        <div class="container">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 flex items-center justify-between p-4 rounded-xl border border-success-green/20 bg-success-green/10 text-emerald-400" x-transition>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-300">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show"
                     class="mb-6 p-4 rounded-xl border border-error-red/20 bg-error-red/10 text-rose-400" x-transition>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">error</span>
                            <span class="text-sm font-semibold">Terjadi Kesalahan:</span>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-rose-300">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                    <ul class="list-disc pl-9 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-8 border-t border-white/5 bg-background mt-auto relative z-10">
        <div class="container flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded bg-white flex items-center justify-center shadow-[0_2px_8px_rgba(245,158,11,0.25)]">
                    <svg viewBox="0 0 100 100" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <defs><linearGradient id="coinvestLgAppFooter" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FDE047"/><stop offset="1" stop-color="#F59E0B"/></linearGradient></defs>
                        <circle cx="50" cy="50" r="34" stroke="url(#coinvestLgAppFooter)" stroke-width="14" stroke-linecap="round" stroke-dasharray="168.5 45.1" transform="rotate(38 50 50)"/>
                        <path d="M38 60 L62 40 M62 40 L52 40 M62 40 L62 50" stroke="url(#coinvestLgAppFooter)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="font-heading text-base font-bold tracking-tight text-white">Coinvest</span>
            </div>
            <div class="text-xs text-on-surface-variant/70">
                © 2026 Coinvest UAS Project. Made with PHP Laravel.
            </div>
        </div>
    </footer>
</body>
</html>