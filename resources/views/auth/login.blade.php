<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Log In | Coinvest</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&amp;family=Sora:wght@400;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
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
        .glass-panel {
            background: rgba(18, 17, 20, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased bg-background text-on-background selection:bg-primary/30 selection:text-white min-h-screen flex flex-col justify-center items-center py-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-40 z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-primary/10 blur-[150px] rounded-full pointer-events-none z-0"></div>

    <div class="w-full max-w-md px-6 relative z-10">
        <!-- Back to home -->
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-white transition-colors mb-8 text-sm group">
            <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Beranda
        </a>

        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="font-heading text-3xl font-bold tracking-tight text-white flex items-center justify-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-2xl">data_usage</span>
                </span>
                Coinvest
            </div>
            <p class="text-on-surface-variant text-sm mt-3">Masuk untuk memantau portofolio Anda</p>
        </div>

        <!-- Card Container -->
        <div class="glass-panel rounded-2xl p-8 shadow-2xl">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl border border-error-red/20 bg-error-red/10 text-rose-400 text-sm">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">error</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">mail</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" 
                               class="w-full pl-11 pr-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">lock</span>
                        <input id="password" type="password" name="password" required placeholder="••••••••" 
                               class="w-full pl-11 pr-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 bg-surface-dim border border-white/10 rounded text-primary focus:ring-primary focus:ring-offset-background"/>
                    <label for="remember" class="ml-2.5 text-sm text-on-surface-variant hover:text-white transition-colors cursor-pointer select-none">Ingat saya</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary text-white font-medium py-3.5 rounded-xl shadow-[0_4px_14px_rgba(139,92,246,0.3)] hover:shadow-[0_6px_20px_rgba(139,92,246,0.4)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Bypass -->
            <div class="mt-6 pt-6 border-t border-white/5 text-center">
                <p class="text-xs text-on-surface-variant mb-3">Mau coba tanpa daftar?</p>
                <a href="{{ route('demo') }}" class="inline-flex items-center gap-2 bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2.5 rounded-xl border border-primary/20 text-xs font-medium transition-all hover:scale-105">
                    <span class="material-symbols-outlined text-base">play_circle</span>
                    Gunakan Akun Demo Instan
                </a>
            </div>
        </div>

        <!-- Toggle link -->
        <p class="text-center text-sm text-on-surface-variant mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-primary hover:text-purple-400 font-medium transition-colors">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>
