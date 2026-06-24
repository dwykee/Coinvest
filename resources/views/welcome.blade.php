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
                        "primary": "#ffffff",
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
                        "data-grid": "rgba(255, 255, 255, 0.05)"
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
            background: #ffffff;
            color: #0a0a0b !important;
        }
        .btn-primary:hover {
            background: #e8e8ec;
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
    <style>
        .nav-link { position: relative; }
        .nav-link::after {
            content: "";
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            height: 2px;
            width: 0;
            background: #ffffff;
            transition: width 0.3s ease;
        }
        .nav-link.active::after { width: 100%; }
        .nav-link.active { color: #ffffff !important; }
    </style>
</head>
<body class="antialiased bg-background text-on-background selection:bg-primary/30 selection:text-white">
<!-- TopNavBar Component -->
<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/5 transition-all">
    <div class="container flex justify-between items-center h-20">
        <div class="font-heading text-xl font-bold tracking-tight text-white flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-[0_2px_8px_rgba(245,158,11,0.25)]">
                <svg viewBox="0 0 100 100" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none">
                    <defs><linearGradient id="coinvestLgN" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FDE047"/><stop offset="1" stop-color="#F59E0B"/></linearGradient></defs>
                    <circle cx="50" cy="50" r="34" stroke="url(#coinvestLgN)" stroke-width="14" stroke-linecap="round" stroke-dasharray="168.5 45.1" transform="rotate(38 50 50)"/>
                    <path d="M38 60 L62 40 M62 40 L52 40 M62 40 L62 50" stroke="url(#coinvestLgN)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            Coinvest
        </div>
        <div class="hidden md:flex items-center space-x-10">
            <a class="nav-link text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#home" data-target="home">Home</a>
            <a class="nav-link text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#features" data-target="features">Fitur</a>
            <a class="nav-link text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#steps" data-target="steps">Cara Kerja</a>
            <a class="nav-link text-on-surface-variant hover:text-white font-medium text-sm tracking-wide transition-colors duration-200" href="#reports" data-target="reports">Analisis</a>
        </div>
        <div class="flex items-center space-x-5">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-white font-medium text-sm px-6 py-2.5 rounded-full shadow-[0_4px_14px_rgba(255,255,255,0.12)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.18)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden md:block text-on-surface-variant hover:text-white font-medium text-sm px-2 py-2 transition-colors duration-200">Log In</a>
                <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-sm px-6 py-2.5 rounded-full shadow-[0_4px_14px_rgba(255,255,255,0.12)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.18)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<main class="pt-20">
    <!-- Hero Section -->
    <section id="home" class="relative pt-32 md:pt-48 pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-60 z-0"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary/10 blur-[150px] rounded-full pointer-events-none z-0"></div>
        <div class="container relative z-10 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="font-heading text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-8 leading-[1.1] tracking-tight">Pantau seluruh portofolio crypto Anda <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/60">di satu tempat</span></h1>
                <p class="text-lg md:text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto font-light leading-relaxed">
                    Pantau harga real-time, analisis performa investasi, dan kelola keuntungan serta kerugian dari dashboard modern Coinvest.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-lg px-8 py-4 rounded-full w-full sm:w-auto shadow-[0_8px_30px_rgba(255,255,255,0.12)] hover:shadow-[0_12px_40px_rgba(255,255,255,0.18)] hover:-translate-y-1 transition-all duration-300 text-center">Mulai Gratis</a>
                    <a href="{{ route('demo') }}" class="text-white font-medium text-lg px-8 py-4 rounded-full border border-white/10 hover:bg-white/5 transition-colors w-full sm:w-auto text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl" data-icon="play_circle">play_circle</span>
                        Coba Demo Instan
                    </a>
                </div>
            </div>
            <div class="mt-24 md:mt-32 relative z-10 mx-auto max-w-5xl rounded-2xl border border-white/10 bg-surface-card/80 backdrop-blur-sm p-2 md:p-4 shadow-[0_20px_60px_rgba(0,0,0,0.6)] overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                <svg viewBox="0 0 1000 600" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-auto rounded-xl border border-white/5"><defs><linearGradient id="hBg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#141416"/><stop offset="1" stop-color="#0b0b0d"/></linearGradient><linearGradient id="hAr" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff" stop-opacity="0.22"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/></linearGradient></defs><rect x="0" y="0" width="1000" height="600" rx="0" fill="url(#hBg)" opacity="1.0"/><rect x="0" y="0" width="210" height="600" rx="0" fill="#0e0e10" opacity="1.0"/><rect x="28" y="34" width="26" height="26" rx="8" fill="#ffffff" opacity="1"/><rect x="64" y="40" width="84" height="12" rx="6" fill="#ffffff" opacity="0.85"/><rect x="20" y="110" width="170" height="34" rx="8" fill="#ffffff" opacity="0.06"/><rect x="32" y="120" width="16" height="16" rx="4" fill="#ffffff" opacity="0.9"/><rect x="60" y="122" width="108" height="12" rx="6" fill="#ffffff" opacity="0.9"/><rect x="32" y="162" width="16" height="16" rx="4" fill="#ffffff" opacity="0.4"/><rect x="60" y="164" width="108" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="32" y="204" width="16" height="16" rx="4" fill="#ffffff" opacity="0.4"/><rect x="60" y="206" width="108" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="32" y="246" width="16" height="16" rx="4" fill="#ffffff" opacity="0.4"/><rect x="60" y="248" width="108" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="32" y="288" width="16" height="16" rx="4" fill="#ffffff" opacity="0.4"/><rect x="60" y="290" width="108" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="246" y="34" width="180" height="16" rx="6" fill="#ffffff" opacity="0.85"/><rect x="246" y="58" width="120" height="10" rx="5" fill="#ffffff" opacity="0.3"/><rect x="862" y="34" width="46" height="32" rx="16" fill="#ffffff" opacity="0.08"/><circle cx="936" cy="50" r="16" fill="#ffffff" opacity="0.9"/><rect x="246" y="92" width="222" height="96" rx="14" fill="#ffffff" opacity="0.04"/><rect x="268" y="116" width="86" height="10" rx="5" fill="#ffffff" opacity="0.35"/><rect x="268" y="138" width="120" height="18" rx="6" fill="#ffffff" opacity="0.85"/><rect x="268" y="166" width="52" height="12" rx="6" fill="#10B981" opacity="0.85"/><rect x="488" y="92" width="222" height="96" rx="14" fill="#ffffff" opacity="0.04"/><rect x="510" y="116" width="86" height="10" rx="5" fill="#ffffff" opacity="0.35"/><rect x="510" y="138" width="120" height="18" rx="6" fill="#ffffff" opacity="0.85"/><rect x="510" y="166" width="52" height="12" rx="6" fill="#10B981" opacity="0.85"/><rect x="730" y="92" width="222" height="96" rx="14" fill="#ffffff" opacity="0.04"/><rect x="752" y="116" width="86" height="10" rx="5" fill="#ffffff" opacity="0.35"/><rect x="752" y="138" width="120" height="18" rx="6" fill="#ffffff" opacity="0.85"/><rect x="752" y="166" width="52" height="12" rx="6" fill="#EF4444" opacity="0.85"/><rect x="246" y="212" width="466" height="302" rx="16" fill="#ffffff" opacity="0.03"/><rect x="270" y="236" width="150" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="270" y="270" width="418" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="270" y="318" width="418" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="270" y="366" width="418" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="270" y="414" width="418" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="270" y="462" width="418" height="1" rx="0" fill="#ffffff" opacity="0.05"/><path d="M 270.0 429.5 L 316.4 414.8 L 362.9 423.2 L 409.3 387.5 L 455.8 398.0 L 502.2 356.0 L 548.7 366.5 L 595.1 330.8 L 641.6 339.2 L 688.0 303.5 L 688.0 482.0 L 270.0 482.0 Z" fill="url(#hAr)"/><path d="M 270.0 429.5 L 316.4 414.8 L 362.9 423.2 L 409.3 387.5 L 455.8 398.0 L 502.2 356.0 L 548.7 366.5 L 595.1 330.8 L 641.6 339.2 L 688.0 303.5" fill="none" stroke="#ffffff" stroke-width="2.5"/><rect x="730" y="212" width="222" height="302" rx="16" fill="#ffffff" opacity="0.03"/><rect x="752" y="236" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="766" cy="284" r="11" fill="#ffffff" opacity="0.9"/><rect x="786" y="276" width="84" height="10" rx="5" fill="#ffffff" opacity="0.55"/><rect x="786" y="292" width="52" height="9" rx="5" fill="#ffffff" opacity="0.25"/><rect x="896" y="280" width="34" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="766" cy="328" r="11" fill="#ffffff" opacity="0.6"/><rect x="786" y="320" width="84" height="10" rx="5" fill="#ffffff" opacity="0.55"/><rect x="786" y="336" width="52" height="9" rx="5" fill="#ffffff" opacity="0.25"/><rect x="896" y="324" width="34" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="766" cy="372" r="11" fill="#10B981" opacity="0.9"/><rect x="786" y="364" width="84" height="10" rx="5" fill="#ffffff" opacity="0.55"/><rect x="786" y="380" width="52" height="9" rx="5" fill="#ffffff" opacity="0.25"/><rect x="896" y="368" width="34" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="766" cy="416" r="11" fill="#ffffff" opacity="0.6"/><rect x="786" y="408" width="84" height="10" rx="5" fill="#ffffff" opacity="0.55"/><rect x="786" y="424" width="52" height="9" rx="5" fill="#ffffff" opacity="0.25"/><rect x="896" y="412" width="34" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="766" cy="460" r="11" fill="#EF4444" opacity="0.8"/><rect x="786" y="452" width="84" height="10" rx="5" fill="#ffffff" opacity="0.55"/><rect x="786" y="468" width="52" height="9" rx="5" fill="#ffffff" opacity="0.25"/><rect x="896" y="456" width="34" height="12" rx="6" fill="#ffffff" opacity="0.7"/></svg>
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
                <svg viewBox="0 0 1000 470" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-auto rounded-xl border border-white/5"><defs><linearGradient id="iBg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#141416"/><stop offset="1" stop-color="#0b0b0d"/></linearGradient></defs><rect x="0" y="0" width="1000" height="470" rx="0" fill="url(#iBg)" opacity="1.0"/><rect x="40" y="34" width="210" height="16" rx="6" fill="#ffffff" opacity="0.85"/><rect x="770" y="30" width="190" height="38" rx="19" fill="#ffffff" opacity="1"/><rect x="792" y="42" width="30" height="14" rx="4" fill="#0b0b0d" opacity="1"/><rect x="830" y="43" width="108" height="12" rx="6" fill="#0b0b0d" opacity="1"/><rect x="40" y="82" width="118" height="40" rx="12" fill="#ffffff" opacity="0.05"/><circle cx="66" cy="102" r="12" fill="#ffffff" opacity="0.8"/><rect x="86" y="96" width="56" height="10" rx="5" fill="#ffffff" opacity="0.5"/><rect x="172" y="82" width="118" height="40" rx="12" fill="#ffffff" opacity="0.05"/><circle cx="198" cy="102" r="12" fill="#ffffff" opacity="0.8"/><rect x="218" y="96" width="56" height="10" rx="5" fill="#ffffff" opacity="0.5"/><rect x="304" y="82" width="118" height="40" rx="12" fill="#ffffff" opacity="0.05"/><circle cx="330" cy="102" r="12" fill="#ffffff" opacity="0.8"/><rect x="350" y="96" width="56" height="10" rx="5" fill="#ffffff" opacity="0.5"/><rect x="436" y="82" width="118" height="40" rx="12" fill="#ffffff" opacity="0.05"/><circle cx="462" cy="102" r="12" fill="#ffffff" opacity="0.8"/><rect x="482" y="96" width="56" height="10" rx="5" fill="#ffffff" opacity="0.5"/><rect x="40" y="148" width="920" height="288" rx="16" fill="#ffffff" opacity="0.03"/><rect x="70" y="176" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="250" y="176" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="440" y="176" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="610" y="176" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="780" y="176" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="64" y="206" width="872" height="1" rx="0" fill="#ffffff" opacity="0.06"/><rect x="70" y="226" width="46" height="16" rx="8" fill="#10B981" opacity="0.85"/><rect x="250" y="228" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="228" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="228" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="228" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="252" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="70" y="260" width="46" height="16" rx="8" fill="#10B981" opacity="0.85"/><rect x="250" y="262" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="262" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="262" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="262" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="286" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="70" y="294" width="46" height="16" rx="8" fill="#EF4444" opacity="0.85"/><rect x="250" y="296" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="296" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="296" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="296" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="320" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="70" y="328" width="46" height="16" rx="8" fill="#10B981" opacity="0.85"/><rect x="250" y="330" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="330" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="330" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="330" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="354" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="70" y="362" width="46" height="16" rx="8" fill="#10B981" opacity="0.85"/><rect x="250" y="364" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="364" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="364" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="364" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="388" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="70" y="396" width="46" height="16" rx="8" fill="#EF4444" opacity="0.85"/><rect x="250" y="398" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="440" y="398" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="610" y="398" width="96" height="12" rx="6" fill="#ffffff" opacity="0.45"/><rect x="780" y="398" width="110" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="64" y="422" width="872" height="1" rx="0" fill="#ffffff" opacity="0.04"/></svg>
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
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="cb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#cb)" opacity="1.0"/><defs><linearGradient id="cbA" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#10B981" stop-opacity="0.30"/><stop offset="1" stop-color="#10B981" stop-opacity="0"/></linearGradient></defs><rect x="30" y="30" width="110" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="30" y="52" width="170" height="26" rx="6" fill="#ffffff" opacity="0.9"/><rect x="30" y="90" width="60" height="16" rx="8" fill="#10B981" opacity="0.85"/><path d="M 30.0 206.0 L 78.6 195.5 L 127.1 199.0 L 175.7 185.0 L 224.3 188.5 L 272.9 174.5 L 321.4 178.0 L 370.0 164.0 L 370.0 220.0 L 30.0 220.0 Z" fill="url(#cbA)"/><path d="M 30.0 206.0 L 78.6 195.5 L 127.1 199.0 L 175.7 185.0 L 224.3 188.5 L 272.9 174.5 L 321.4 178.0 L 370.0 164.0" fill="none" stroke="#10B981" stroke-width="2.5"/></svg>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Total Aset (Current Balance)</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Nilai saldo kumulatif portofolio Anda dihitung berdasarkan harga real-time.</p>
                    </div>
                </div>
                <!-- Report Card 2 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="cg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#cg)" opacity="1.0"/><rect x="30" y="28" width="90" height="10" rx="5" fill="#ffffff" opacity="0.4"/><rect x="30" y="52" width="160" height="70" rx="12" fill="#ffffff" opacity="0.04"/><rect x="48" y="68" width="70" height="9" rx="5" fill="#ffffff" opacity="0.4"/><rect x="48" y="86" width="86" height="18" rx="6" fill="#10B981" opacity="0.85"/><rect x="210" y="52" width="160" height="70" rx="12" fill="#ffffff" opacity="0.04"/><rect x="228" y="68" width="70" height="9" rx="5" fill="#ffffff" opacity="0.4"/><rect x="228" y="86" width="86" height="18" rx="6" fill="#ffffff" opacity="0.8"/><rect x="40" y="182.0" width="40" height="28.0" rx="6" fill="#10B981" opacity="0.85"/><rect x="98" y="161.0" width="40" height="49.0" rx="6" fill="#ffffff" opacity="0.5"/><rect x="156" y="171.5" width="40" height="38.5" rx="6" fill="#10B981" opacity="0.85"/><rect x="214" y="147.0" width="40" height="63.0" rx="6" fill="#ffffff" opacity="0.5"/><rect x="272" y="164.5" width="40" height="45.5" rx="6" fill="#10B981" opacity="0.85"/><rect x="330" y="150.5" width="40" height="59.5" rx="6" fill="#ffffff" opacity="0.5"/></svg>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Keuntungan Realized & Unrealized</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Pantau keuntungan yang sudah dikunci (take profit) vs potensi laba mengambang.</p>
                    </div>
                </div>
                <!-- Report Card 3 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="ct" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#ct)" opacity="1.0"/><rect x="30" y="26" width="120" height="10" rx="5" fill="#ffffff" opacity="0.45"/><rect x="30" y="52" width="340" height="1" rx="0" fill="#ffffff" opacity="0.07"/><rect x="30" y="66" width="44" height="18" rx="9" fill="#10B981" opacity="0.85"/><rect x="90" y="69" width="120" height="12" rx="6" fill="#ffffff" opacity="0.55"/><rect x="240" y="69" width="60" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="320" y="69" width="50" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="30" y="96" width="340" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="30" y="106" width="44" height="18" rx="9" fill="#EF4444" opacity="0.85"/><rect x="90" y="109" width="120" height="12" rx="6" fill="#ffffff" opacity="0.55"/><rect x="240" y="109" width="60" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="320" y="109" width="50" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="30" y="136" width="340" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="30" y="146" width="44" height="18" rx="9" fill="#10B981" opacity="0.85"/><rect x="90" y="149" width="120" height="12" rx="6" fill="#ffffff" opacity="0.55"/><rect x="240" y="149" width="60" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="320" y="149" width="50" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="30" y="176" width="340" height="1" rx="0" fill="#ffffff" opacity="0.05"/><rect x="30" y="186" width="44" height="18" rx="9" fill="#EF4444" opacity="0.85"/><rect x="90" y="189" width="120" height="12" rx="6" fill="#ffffff" opacity="0.55"/><rect x="240" y="189" width="60" height="12" rx="6" fill="#ffffff" opacity="0.4"/><rect x="320" y="189" width="50" height="12" rx="6" fill="#ffffff" opacity="0.7"/><rect x="30" y="216" width="340" height="1" rx="0" fill="#ffffff" opacity="0.05"/></svg>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Analisis Transaksi</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Detail lengkap untuk setiap histori transaksi beli/jual beserta harga rata-rata.</p>
                    </div>
                </div>
                <!-- Report Card 4 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="ca" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#ca)" opacity="1.0"/><rect x="30" y="26" width="130" height="10" rx="5" fill="#ffffff" opacity="0.45"/><circle cx="120" cy="135" r="58" fill="none" stroke="#ffffff" stroke-opacity="0.9" stroke-width="26" stroke-dasharray="145.77 218.65" stroke-dashoffset="-0.00" transform="rotate(-90 120 135)"/><circle cx="120" cy="135" r="58" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="26" stroke-dasharray="102.04 262.39" stroke-dashoffset="-145.77" transform="rotate(-90 120 135)"/><circle cx="120" cy="135" r="58" fill="none" stroke="#10B981" stroke-opacity="0.85" stroke-width="26" stroke-dasharray="65.60 298.83" stroke-dashoffset="-247.81" transform="rotate(-90 120 135)"/><circle cx="120" cy="135" r="58" fill="none" stroke="#ffffff" stroke-opacity="0.25" stroke-width="26" stroke-dasharray="51.02 313.41" stroke-dashoffset="-313.41" transform="rotate(-90 120 135)"/><circle cx="120" cy="135" r="32" fill="#11111400" opacity="1"/><circle cx="238" cy="86" r="7" fill="#ffffff" opacity="0.9"/><rect x="254" y="80" width="80" height="12" rx="6" fill="#ffffff" opacity="0.5"/><rect x="344" y="80" width="30" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="238" cy="120" r="7" fill="#ffffff" opacity="0.5"/><rect x="254" y="114" width="80" height="12" rx="6" fill="#ffffff" opacity="0.5"/><rect x="344" y="114" width="30" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="238" cy="154" r="7" fill="#10B981" opacity="0.85"/><rect x="254" y="148" width="80" height="12" rx="6" fill="#ffffff" opacity="0.5"/><rect x="344" y="148" width="30" height="12" rx="6" fill="#ffffff" opacity="0.7"/><circle cx="238" cy="188" r="7" fill="#ffffff" opacity="0.25"/><rect x="254" y="182" width="80" height="12" rx="6" fill="#ffffff" opacity="0.5"/><rect x="344" y="182" width="30" height="12" rx="6" fill="#ffffff" opacity="0.7"/></svg>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Distribusi Alokasi Aset</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Visualisasi persentase kepemilikan aset untuk memantau diversifikasi portofolio Anda.</p>
                    </div>
                </div>
                <!-- Report Card 5 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="cp" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#cp)" opacity="1.0"/><rect x="30" y="26" width="140" height="10" rx="5" fill="#ffffff" opacity="0.45"/><circle cx="40" cy="70" r="8" fill="#ffffff" opacity="0.8"/><rect x="58" y="62" width="70" height="9" rx="5" fill="#ffffff" opacity="0.45"/><rect x="58" y="78" width="300" height="12" rx="6" fill="#ffffff" opacity="0.06"/><rect x="58" y="78" width="270.0" height="12" rx="6" fill="#ffffff" opacity="0.85"/><circle cx="40" cy="110" r="8" fill="#ffffff" opacity="0.5"/><rect x="58" y="102" width="70" height="9" rx="5" fill="#ffffff" opacity="0.45"/><rect x="58" y="118" width="300" height="12" rx="6" fill="#ffffff" opacity="0.06"/><rect x="58" y="118" width="186.0" height="12" rx="6" fill="#ffffff" opacity="0.5"/><circle cx="40" cy="150" r="8" fill="#ffffff" opacity="0.5"/><rect x="58" y="142" width="70" height="9" rx="5" fill="#ffffff" opacity="0.45"/><rect x="58" y="158" width="300" height="12" rx="6" fill="#ffffff" opacity="0.06"/><rect x="58" y="158" width="120.0" height="12" rx="6" fill="#ffffff" opacity="0.5"/><circle cx="40" cy="190" r="8" fill="#ffffff" opacity="0.5"/><rect x="58" y="182" width="70" height="9" rx="5" fill="#ffffff" opacity="0.45"/><rect x="58" y="198" width="300" height="12" rx="6" fill="#ffffff" opacity="0.06"/><rect x="58" y="198" width="75.0" height="12" rx="6" fill="#ffffff" opacity="0.5"/></svg>
                    </div>
                    <div class="p-8">
                        <h4 class="font-heading text-xl font-semibold text-white mb-3">Saldo Berdasarkan Platform</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed">Identifikasi sebaran saldo portofolio Anda di berbagai bursa/exchange.</p>
                    </div>
                </div>
                <!-- Report Card 6 -->
                <div class="bg-surface-card border border-white/5 rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 shadow-lg hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group">
                    <div class="relative h-56 overflow-hidden bg-surface-container-high border-b border-white/5">
                        <svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"><defs><linearGradient id="cd" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16161a"/><stop offset="1" stop-color="#0d0d10"/></linearGradient></defs><rect x="0" y="0" width="400" height="240" rx="0" fill="url(#cd)" opacity="1.0"/><defs><linearGradient id="cdA" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff" stop-opacity="0.22"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/></linearGradient></defs><rect x="30" y="28" width="44" height="22" rx="11" fill="#ffffff" opacity="0.07"/><rect x="42" y="36" width="20" height="8" rx="4" fill="#ffffff" opacity="0.5"/><rect x="82" y="28" width="44" height="22" rx="11" fill="#ffffff" opacity="0.07"/><rect x="94" y="36" width="20" height="8" rx="4" fill="#ffffff" opacity="0.5"/><rect x="134" y="28" width="44" height="22" rx="11" fill="#ffffff" opacity="0.9"/><rect x="146" y="36" width="20" height="8" rx="4" fill="#0b0b0d" opacity="1"/><rect x="186" y="28" width="44" height="22" rx="11" fill="#ffffff" opacity="0.07"/><rect x="198" y="36" width="20" height="8" rx="4" fill="#ffffff" opacity="0.5"/><path d="M 30.0 174.0 L 72.5 156.0 L 115.0 162.0 L 157.5 138.0 L 200.0 150.0 L 242.5 126.0 L 285.0 135.6 L 327.5 108.0 L 370.0 114.0 L 370.0 210.0 L 30.0 210.0 Z" fill="url(#cdA)"/><path d="M 30.0 174.0 L 72.5 156.0 L 115.0 162.0 L 157.5 138.0 L 200.0 150.0 L 242.5 126.0 L 285.0 135.6 L 327.5 108.0 L 370.0 114.0" fill="none" stroke="#ffffff" stroke-width="2.5"/></svg>
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
                            <a href="{{ route('register') }}" class="btn-primary text-white font-medium text-lg px-10 py-4 rounded-full shadow-[0_8px_30px_rgba(255,255,255,0.12)] hover:shadow-[0_12px_40px_rgba(255,255,255,0.18)] hover:-translate-y-1 transition-all duration-300 text-center inline-block w-max">Daftar Sekarang</a>
                        </div>
                    </div>
                    <div class="hidden md:block relative h-full min-h-[400px]">
                        <svg viewBox="0 0 600 500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 w-full h-full object-cover"><defs><linearGradient id="ctaBg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1a1a1f"/><stop offset="1" stop-color="#0b0b0d"/></linearGradient><radialGradient id="ctaGlow" cx="0.7" cy="0.25" r="0.6"><stop offset="0" stop-color="#ffffff" stop-opacity="0.16"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/></radialGradient><linearGradient id="ctaAr" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff" stop-opacity="0.25"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/></linearGradient></defs><rect x="0" y="0" width="600" height="500" rx="0" fill="url(#ctaBg)" opacity="1.0"/><rect x="0" y="0" width="600" height="500" rx="0" fill="url(#ctaGlow)" opacity="1.0"/><rect x="0" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="40" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="80" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="120" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="160" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="200" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="240" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="280" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="320" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="360" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="400" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="440" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="480" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="520" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="560" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="600" y="0" width="1" height="500" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="0" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="40" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="80" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="120" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="160" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="200" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="240" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="280" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="320" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="360" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="400" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="440" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="0" y="480" width="600" height="1" rx="0" fill="#ffffff" opacity="0.04"/><rect x="120" y="150" width="360" height="210" rx="18" fill="#141416" opacity="0.95"/><rect x="146" y="176" width="120" height="12" rx="6" fill="#ffffff" opacity="0.6"/><rect x="146" y="198" width="90" height="20" rx="6" fill="#ffffff" opacity="0.9"/><rect x="420" y="176" width="40" height="16" rx="8" fill="#10B981" opacity="0.85"/><path d="M 146.0 317.5 L 190.0 304.0 L 234.0 311.2 L 278.0 290.5 L 322.0 295.0 L 366.0 277.0 L 410.0 280.6 L 454.0 260.8 L 454.0 340.0 L 146.0 340.0 Z" fill="url(#ctaAr)"/><path d="M 146.0 317.5 L 190.0 304.0 L 234.0 311.2 L 278.0 290.5 L 322.0 295.0 L 366.0 277.0 L 410.0 280.6 L 454.0 260.8" fill="none" stroke="#ffffff" stroke-width="2.5"/></svg>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0b] via-[#0a0a0b]/50 to-transparent"></div>
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
            <span class="w-6 h-6 rounded bg-white flex items-center justify-center">
                <svg viewBox="0 0 100 100" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none">
                    <defs><linearGradient id="coinvestLgF" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FDE047"/><stop offset="1" stop-color="#F59E0B"/></linearGradient></defs>
                    <circle cx="50" cy="50" r="34" stroke="url(#coinvestLgF)" stroke-width="14" stroke-linecap="round" stroke-dasharray="168.5 45.1" transform="rotate(38 50 50)"/>
                    <path d="M38 60 L62 40 M62 40 L52 40 M62 40 L62 50" stroke="url(#coinvestLgF)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
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
    <script>
        (function () {
            const links = Array.from(document.querySelectorAll('.nav-link'));
            const sections = links
                .map(l => document.getElementById(l.getAttribute('data-target')))
                .filter(Boolean);
            function setActive(id) {
                links.forEach(l => l.classList.toggle('active', l.getAttribute('data-target') === id));
            }
            setActive('home');
            if ('IntersectionObserver' in window) {
                const obs = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) setActive(e.target.id);
                    });
                }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });
                sections.forEach(s => obs.observe(s));
            }
            links.forEach(l => l.addEventListener('click', function () {
                setActive(l.getAttribute('data-target'));
            }));
        })();
    </script>
</body>
</html>
