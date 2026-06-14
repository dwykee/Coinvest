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

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-white/10"></div>
                <span class="text-xs text-on-surface-variant uppercase tracking-wider">atau</span>
                <div class="flex-1 h-px bg-white/10"></div>
            </div>

            <!-- Google Login -->
            <a href="{{ route('auth.google.redirect') }}"
               class="w-full flex items-center justify-center gap-3 bg-white hover:bg-white/90 text-gray-700 font-medium py-3.5 rounded-xl shadow-sm hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z" fill="#34A853"/>
                    <path d="M3.964 10.707A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/>
                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

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
