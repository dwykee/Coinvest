<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Crypto Portfolio Tracker | Coinvest</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&amp;family=Sora:wght@400;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-container": "#ca801e",
                        "on-surface-variant": "#a19baf",
                        "on-secondary-fixed-variant": "#4a3d7c",
                        "surface-variant": "#353534",
                        "on-secondary-container": "#baabf3",
                        "secondary-container": "#4a3d7c",
                        "on-surface": "#f5f3f7",
                        "primary": "#8b5cf6",
                        "tertiary-fixed": "#ffdcbb",
                        "surface-dim": "#0a0a0b",
                        "on-primary-container": "#340080",
                        "background": "#0a0a0b",
                        "tertiary-fixed-dim": "#ffb869",
                        "on-tertiary": "#482900",
                        "on-error": "#690005",
                        "on-tertiary-fixed-variant": "#673d00",
                        "surface-container-low": "#151416",
                        "inverse-surface": "#e5e2e1",
                        "secondary-fixed": "#e7deff",
                        "on-tertiary-container": "#3f2300",
                        "surface-container-lowest": "#050505",
                        "on-background": "#f5f3f7",
                        "primary-container": "#a078ff",
                        "primary-fixed-dim": "#d0bcff",
                        "outline": "#7a7485",
                        "surface-container-highest": "#28272a",
                        "surface-container": "#1c1b1f",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#313030",
                        "on-primary-fixed": "#23005c",
                        "surface-tint": "#d0bcff",
                        "surface": "#0a0a0b",
                        "surface-bright": "#393939",
                        "on-tertiary-fixed": "#2c1700",
                        "on-secondary": "#332664",
                        "on-primary-fixed-variant": "#5516be",
                        "secondary": "#ccbeff",
                        "on-error-container": "#ffdad6",
                        "tertiary": "#ffb869",
                        "secondary-fixed-dim": "#ccbeff",
                        "inverse-primary": "#6d3bd7",
                        "error": "#ffb4ab",
                        "outline-variant": "#494454",
                        "primary-fixed": "#e9ddff",
                        "on-secondary-fixed": "#1e0e4e",
                        "surface-container-high": "#222125",
                        "error-container": "#93000a",
                        "success-green": "#10B981",
                        "error-red": "#EF4444",
                        "surface-card": "#121114",
                        "data-grid": "rgba(139, 92, 246, 0.05)"
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
    </style>
</head>
<body class="antialiased bg-background text-on-background selection:bg-primary/30 selection:text-white">
<!-- TopNavBar Component -->
<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/5 transition-all">
    <div class="container flex justify-between items-center h-20">
        <div class="font-heading text-xl font-bold tracking-tight text-white flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-xl" data-icon="data_usage">data_usage</span>
            </span>
            Coinvest
        </div>
        <div class="hidden md:flex items-center space-x-10">
            <a class="text-white font-medium text-sm tracking-wide transition-colors duration-200 relative after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-primary" href="#">Home</a>
            <a class="text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#features">Fitur</a>
            <a class="text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#steps">Cara Kerja</a>
            <a class="text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#reports">Analisis</a>
        </div>
        <div class="flex items-center space-x-5">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-white font-medium text-sm px-6 py-2.5 rounded-full shadow-[0_4px_14px_rgba(139,92,246,0.3)] hover:shadow-[0_6px_20px_rgba(139,92,246,0.4)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden md:block text-on-surface-variant hover:text-white font-medium text-sm px-2 py-2 transition-colors duration-200">Log In</a>
                <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-sm px-6 py-2.5 rounded-full shadow-[0_4px_14px_rgba(139,92,246,0.3)] hover:shadow-[0_6px_20px_rgba(139,92,246,0.4)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative pt-32 md:pt-48 pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-60 z-0"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary/10 blur-[150px] rounded-full pointer-events-none z-0"></div>
        <div class="container relative z-10 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="font-heading text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-8 leading-[1.1] tracking-tight">Pantau seluruh portofolio crypto Anda <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-400">di satu tempat</span></h1>
                <p class="text-lg md:text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto font-light leading-relaxed">
                    Pantau harga real-time, analisis performa investasi, dan kelola keuntungan serta kerugian dari dashboard modern Coinvest.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-lg px-8 py-4 rounded-full w-full sm:w-auto shadow-[0_8px_30px_rgba(139,92,246,0.3)] hover:shadow-[0_12px_40px_rgba(139,92,246,0.4)] hover:-translate-y-1 transition-all duration-300 text-center">Mulai Gratis</a>
                    <a href="{{ route('demo') }}" class="text-white font-medium text-lg px-8 py-4 rounded-full border border-white/10 hover:bg-white/5 transition-colors w-full sm:w-auto text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl" data-icon="play_circle">play_circle</span>
                        Coba Demo Instan
                    </a>
                </div>
            </div>
            <div class="mt-24 md:mt-32 relative z-10 mx-auto max-w-5xl rounded-2xl border border-white/10 bg-surface-card/80 backdrop-blur-sm p-2 md:p-4 shadow-[0_20px_60px_rgba(0,0,0,0.6)] overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                <img alt="Coinvest Dashboard Interface" class="w-full h-auto object-cover rounded-xl border border-white/5" src="https://lh3.googleusercontent.com/aida/AP1WRLvdBJ8xlp9s_oFN0pNEBqpsTglTxZJVAWmgrI3h9XGWmZ6NUWT8IV4n7vf_sEIAo0gpKElYjzf8WfG_RS39GBNwvTUONADlqBPpGfiuD8lQxkTq8ylFOYcMYIGyp_4I9eKN0dnQpzxFHG05eKJj6EgoNNZ9v_66xawKREnWYcpLd5yD-LTooh4gyUJm3FMGlNW3-4UEOz7ktYrPA8-W_9S5psjzhsjwz5h7-IfLNoYAPtRXUdei_sj8tg"/>
            </div>
        </div>
    </section>

    <!-- Value Prop -->
    <section id="features" class="py-32">
        <div class="container">
            <div class="grid md:grid-cols-2 gap-20 items-center">
                <div>
                    <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-8 leading-tight tracking-tight">Mengapa Anda Butuh Portofolio Tracker?</h2>
                    <div class="space-y-6 text-on-surface-variant text-lg font-light leading-relaxed">
                        <p>Jika Anda menyimpan aset crypto di beberapa exchange atau wallet, Anda pasti kerepotan: harus login ke Binance, Tokocrypto, Indodax, lalu MetaMask, hanya untuk menghitung total saldo. Spreadsheet pun langsung usang saat harga pasar bergerak.</p>
                        <p>Coinvest menyelesaikan masalah ini. Kami mengintegrasikan seluruh riwayat transaksi Anda ke dalam satu tampilan terpadu, sehingga Anda selalu tahu nilai total aset, keuntungan (PnL), dan alokasi Anda secara real-time.</p>
                    </div>
                </div>
                <div class="bg-surface-card border border-white/5 rounded-2xl p-10 relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 blur-[80px] rounded-full pointer-events-none"></div>
                    <div class="flex items-center space-x-5 mb-8 relative z-10">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-3xl" data-icon="account_balance_wallet">account_balance_wallet</span>
                        </div>
                        <h3 class="font-heading text-2xl font-semibold text-white">Satu Tampilan Terpadu</h3>
                    </div>
                    <ul class="space-y-6 relative z-10">
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-success-green/20 flex items-center justify-center mr-4 mt-0.5 shrink-0">
                                <span class="material-symbols-outlined text-success-green text-sm" data-icon="check">check</span>
                            </div>
                            <span class="text-on-surface-variant text-lg">Melihat total saldo di seluruh platform</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-success-green/20 flex items-center justify-center mr-4 mt-0.5 shrink-0">
                                <span class="material-symbols-outlined text-success-green text-sm" data-icon="check">check</span>
                            </div>
                            <span class="text-on-surface-variant text-lg">Melacak harga beli rata-rata (average buy price)</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-success-green/20 flex items-center justify-center mr-4 mt-0.5 shrink-0">
                                <span class="material-symbols-outlined text-success-green text-sm" data-icon="check">check</span>
                            </div>
                            <span class="text-on-surface-variant text-lg">Memantau profit & loss (realized & unrealized) secara akurat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Showcase -->
    <section class="py-32 relative">
        <div class="absolute inset-0 bg-white/[0.02] border-y border-white/5"></div>
        <div class="container relative z-10">
            <div class="text-center mb-20 max-w-3xl mx-auto">
                <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">Pelacakan Portofolio yang Presisi</h2>
                <p class="text-on-surface-variant text-xl font-light leading-relaxed">Kelola transaksi, pantau harga koin teratas secara real-time, dan hitung performa portofolio Anda secara otomatis.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-surface-card p-2 md:p-4 shadow-[0_20px_60px_rgba(0,0,0,0.4)] mx-auto max-w-5xl">
                <img alt="Coinvest Import and Sync Features" class="w-full h-auto rounded-xl border border-white/5" src="https://lh3.googleusercontent.com/aida/AP1WRLs2cH625GATt5zUfvHVbxEdVmBuDKEj1Pcs5aXulNKM9ZQXx1iO0P0qgMPtyXE3luc7l93O8Yn4R6tJ9vbKUKXCIITqFWqx0s0WXSm_GXa3QsrjLcib_cTzvHobo4J-eW6p__D5LTR5Zv-gmSmQdf-aJhqvW8X9CpF-a32yGVXJbYvdd6K74CH_hM2BNzLo3FuXjQ8xjuZQpj--Pq6C-Oe4xvnROM6WCxBpYMIYBq7evFgXwcC2C7TDVA"/>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section id="steps" class="py-32">
        <div class="container">
            <div class="text-center mb-20">
                <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight">3 Langkah Mudah Menggunakan Coinvest</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-10">
                <!-- Step 1 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl p-10 relative hover:border-primary/50 transition-colors duration-300 group">
                    <div class="absolute -top-6 -left-6 w-14 h-14 bg-background rounded-full flex items-center justify-center font-heading text-xl font-bold text-primary border border-white/10 shadow-lg group-hover:scale-110 transition-transform duration-300">1</div>
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8">
                        <span class="material-symbols-outlined text-primary text-3xl" data-icon="person_add">person_add</span>
                    </div>
                    <h3 class="font-heading text-2xl font-semibold text-white mb-4">Buat Akun</h3>
                    <p class="text-on-surface-variant text-base leading-relaxed">Daftar dengan cepat menggunakan nama dan email Anda secara aman.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl p-10 relative hover:border-primary/50 transition-colors duration-300 group">
                    <div class="absolute -top-6 -left-6 w-14 h-14 bg-background rounded-full flex items-center justify-center font-heading text-xl font-bold text-primary border border-white/10 shadow-lg group-hover:scale-110 transition-transform duration-300">2</div>
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8">
                        <span class="material-symbols-outlined text-primary text-3xl" data-icon="add_card">add_card</span>
                    </div>
                    <h3 class="font-heading text-2xl font-semibold text-white mb-4">Input Transaksi</h3>
                    <p class="text-on-surface-variant text-base leading-relaxed">Catat transaksi beli atau jual crypto Anda untuk melacak harga masuk dan modal.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl p-10 relative hover:border-primary/50 transition-colors duration-300 group">
                    <div class="absolute -top-6 -left-6 w-14 h-14 bg-background rounded-full flex items-center justify-center font-heading text-xl font-bold text-primary border border-white/10 shadow-lg group-hover:scale-110 transition-transform duration-300">3</div>
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8">
                        <span class="material-symbols-outlined text-primary text-3xl" data-icon="monitoring">monitoring</span>
                    </div>
                    <h3 class="font-heading text-2xl font-semibold text-white mb-4">Pantau Kinerja</h3>
                    <p class="text-on-surface-variant text-base leading-relaxed">Lihat perkembangan portofolio, total keuntungan, dan persentase alokasi secara instan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Reports Grid -->
    <section id="reports" class="py-32 bg-white/[0.01]">
        <div class="container">
            <div class="max-w-3xl mb-20">
                <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">Analisis Mendalam untuk Portofolio Anda</h2>
                <p class="text-on-surface-variant text-xl font-light">Ubah data transaksi menjadi wawasan yang dapat ditindaklanjuti.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Report Card 1 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Current Balance Report showing real-time portfolio value" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLso1ELevpjCyg6uzUOvFj_uaM1EIf2nK_MLFfSRVSRIIS7ME4yYsDL86lPVQLBxlG8N9ebz1-gCwzMGHxG3njqt0xqLk2DsaMMjlop-3jJjRuH0wXeGXs1mWPCipCpmwsoPJycsNQJ4jXWconNkBneSWInS4ygvjp2vpEaFkm93tXfWgbDHyPiBA-K8LYF188LhMUSelvah9yU3PdyMBobsGwLn-pnrA_qTIboKoCdz6gu7hej6zDEfaZI"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Total Aset (Current Balance)</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Nilai saldo kumulatif portofolio Anda dihitung berdasarkan harga real-time.</p>
                    </div>
                </div>
                <!-- Report Card 2 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Realized and Unrealized Gains analysis dashboard" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLsvgeIkvNGz3kzyPXlRbKw-VFObMwSejtTc2ytZc-EtBVrOb5QjcXtJ3jZA88NuJrRUpJrptUdoTye2y6tgJIC-VkX8FlGh_2fS_a5mxA0HEsw-ZT6PZLIPeiJlYNeFgKhdcxvMrKmROQ73NuPyUE2iYbH_vadWgs2mQrhi3NTR2osyA_3JKXfurEgrV8ijkt2ykkvx3EpuvZ7iiQcLCdCE9vPuzhUD2V9LGaKefPEytQgOTLMKQVU9Y8s"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Keuntungan Realized & Unrealized</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Pantau keuntungan yang sudah dikunci (take profit) vs potensi laba mengambang.</p>
                    </div>
                </div>
                <!-- Report Card 3 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Detailed Trade Analysis report interface" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLt2zG2kGRoy9UoXDy3ELgomEJcOHh1CoVYy8X6hfSQcy7V4gC9v5_QqJ5ccoaPgg7O2J-sOIGVZisSR9qz0p3VJS4Ihh04WTGS6A1QjegUb9nSlRh7THc8hH7TCp5J8JKwTnI1vM-TaCjbnETuVh7u_F7LRBnrjMhYYtI0rFld_oV77LuZjR9iVTozad-Mwapcc59oq7xfilJb6TR7G2cOwSs4tSJVm8wfG3o9CMzvN7Oa_WTUXORFOX-g"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Analisis Transaksi</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Detail lengkap untuk setiap histori transaksi beli/jual beserta harga rata-rata.</p>
                    </div>
                </div>
                <!-- Report Card 4 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Portfolio Allocation pie charts and distribution" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLvp4Wiealx0ZE8ftvA0sOBxeAoiiyEVX7NFDqWUw1lhH-o75IefHBmpSCYnySzyA8kekv7hP5rb2kZkXxonUFBSSVHxPTB9e3GbOSwXOOMobU-jOhDiJq4QYKZO_vWPWqmt1Z0gh_qtv6ir-csSbLRYmGnnBuAVjg1njSlNYY1xc0UvLXyaVqCr8OAr3cnxw3wOaudS57cxMgFMyCQpjFuPEFz_dp7yczSceomrJauXN7yeRuGer1ZBH9s"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Distribusi Alokasi Aset</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Visualisasi persentase kepemilikan aset untuk memantau diversifikasi portofolio Anda.</p>
                    </div>
                </div>
                <!-- Report Card 5 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Balance distribution by Exchange platform" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLuGdGNoKMzYG4V4NnS_kpzovySB-U3Yt0XBbXOxnt9BGEiB44_CCklsfd6THTzD64tkGNkmtthTKkAUGYCl0xXgmR6p-hc8JWJmnfSD2YVj1DS4FDcPGdvru43HyGXRS5y4WBRKdVmMhx3swRENmE3-8lU0tai89Ojpm4rbEkOnHgyIT0GqiTlArnZRC2och0Yd7Q2UrePnw2uRAGf-8A5Ozq3ga-4484i0FOAE_nOznvFtUxbTuHqGWA"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Saldo Berdasarkan Platform</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Identifikasi sebaran saldo portofolio Anda di berbagai bursa/exchange.</p>
                    </div>
                </div>
                <!-- Report Card 6 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <img alt="Custom Date Ranges filtering for portfolio performance" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AP1WRLtGd13ICj7xVcJ1N9qP2db0jB5WshrYeInuMaZwj6gEQe9N0Fn_199mh-3R3LAzxBWYknjUVkqvuSSSdywHnEA5MjU8SeYhIqFMylarObcv9yKpLcczRpMYGYiqC8KtnkwMJx398u0RmoVXJpA_Q4f8umRyL_JxCJ4f49pKX6SgbEZr6uvxKe5U4TfjYs7wcWyXbu9JHvlXZ0OMcedTe4HOJ8svsEgbklYGFNoAYhiwaNHSSNjK9LSwTNU"/>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Filter Rentang Waktu Kustom</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Lacak tingkat pertumbuhan modal dan profitabilitas dalam jangka waktu tertentu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-32">
        <div class="container">
            <div class="bg-primary/5 rounded-[2rem] border border-primary/10 overflow-hidden relative">
                <div class="absolute inset-0 bg-grid-pattern opacity-30 pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary/10 to-transparent pointer-events-none"></div>
                <div class="grid md:grid-cols-2">
                    <div class="p-12 md:p-20 flex flex-col justify-center relative z-10">
                        <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight leading-tight">Mulai Kelola Portofolio Crypto Anda Sekarang</h2>
                        <p class="text-on-surface-variant text-lg font-light mb-10 max-w-md">Bergabunglah bersama ribuan investor cerdas yang memantau performa investasi mereka secara detail dengan Coinvest.</p>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-lg px-10 py-4 rounded-full shadow-[0_8px_30px_rgba(139,92,246,0.3)] hover:shadow-[0_12px_40px_rgba(139,92,246,0.4)] hover:-translate-y-1 transition-all duration-300 text-center inline-block w-max">Daftar Sekarang</a>
                        </div>
                    </div>
                    <div class="hidden md:block relative h-full min-h-[400px]">
                        <img alt="Professional using Coinvest on laptop" class="absolute inset-0 w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida/AP1WRLu51k9HhQMVtBAAlwDjlUZfaEVFgyCi7UA1vptXt0PXRiorSx2VgcTURYhTdxeRIOPry1yd2GW2R1JkNU0_nuF4wDbMMeWyYBcb8Pnwd1FHFAjXXfjYZ38hceHKSrkCoo8N1woKZTqp0ZGpyaUthu5HMAKbjjI6khXVdZQu-DFTsgNH6A8hrKQGv7W3rjql4rGqAie1hZU3vs_27txECKFXjKgWmLt9VOBwlbaj_W3GmZfqD0dN6NIzLw"/>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0d0a14] via-[#0d0a14]/50 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer Component -->
<footer class="w-full py-12 border-t border-white/5 bg-background">
    <div class="container flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded bg-primary flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-sm" data-icon="data_usage">data_usage</span>
            </span>
            <span class="font-heading text-lg font-bold tracking-tight text-white">Coinvest</span>
        </div>
        <div class="flex flex-wrap justify-center gap-6 text-sm">
            <a class="text-on-surface-variant hover:text-white transition-colors" href="#">Kebijakan Privasi</a>
            <a class="text-on-surface-variant hover:text-white transition-colors" href="#">Syarat Layanan</a>
            <a class="text-on-surface-variant hover:text-white transition-colors" href="#">API Market</a>
            <a class="text-on-surface-variant hover:text-white transition-colors" href="#">Bantuan</a>
        </div>
        <div class="text-sm text-on-surface-variant/70">
            © 2026 Coinvest UAS Project. All Rights Reserved.
        </div>
    </div>
</footer>
</body>
</html>
