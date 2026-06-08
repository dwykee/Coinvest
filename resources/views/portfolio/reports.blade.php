@extends('layouts.app')

@section('title', 'Analisis & Laporan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white font-heading">Analisis & Laporan Pajak</h1>
        <p class="text-on-surface-variant text-sm mt-1">Dapatkan estimasi pajak transaksi dan analisis alokasi dana secara komprehensif.</p>
    </div>
</div>

<!-- Key Performance Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Realized Gains -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden shadow-lg border-t-4 {{ $realizedGains >= 0 ? 'border-t-success-green' : 'border-t-error-red' }}">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 blur-[40px] rounded-full pointer-events-none"></div>
        <div class="flex justify-between items-start mb-3">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Keuntungan Realized (Laba Bersih)</span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white">
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold tracking-tight font-heading {{ $realizedGains >= 0 ? 'text-success-green' : 'text-error-red' }}">
            {{ $realizedGains >= 0 ? '+' : '' }}${{ number_format($realizedGains, 2) }}
        </h2>
        <p class="text-xs text-on-surface-variant mt-2">Dihitung dari keuntungan posisi yang sudah dijual (diselesaikan).</p>
    </div>

    <!-- Unrealized Gains -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden shadow-lg border-t-4 {{ $unrealizedGains >= 0 ? 'border-t-success-green' : 'border-t-error-red' }}">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 blur-[40px] rounded-full pointer-events-none"></div>
        <div class="flex justify-between items-start mb-3">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Keuntungan Unrealized (Floating PnL)</span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-white/5 text-white">
                <span class="material-symbols-outlined text-lg">show_chart</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold tracking-tight font-heading {{ $unrealizedGains >= 0 ? 'text-success-green' : 'text-error-red' }}">
            {{ $unrealizedGains >= 0 ? '+' : '' }}${{ number_format($unrealizedGains, 2) }}
        </h2>
        <p class="text-xs text-on-surface-variant mt-2">Potensi keuntungan/kerugian dari sisa posisi aset terbuka saat ini.</p>
    </div>

    <!-- Total Portfolio Value -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden shadow-lg border-t-4 border-t-primary">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 blur-[40px] rounded-full pointer-events-none"></div>
        <div class="flex justify-between items-start mb-3">
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Nilai Portofolio</span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-primary/10 text-primary">
                <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-white tracking-tight font-heading">${{ number_format($totalValue, 2) }}</h2>
        <p class="text-xs text-on-surface-variant mt-2">Nilai konversi pasar real-time saat ini.</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Chart: Asset distribution by Value -->
    <div class="glass-panel rounded-2xl p-6 shadow-lg">
        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-6">Distribusi Nilai Aset</h3>
        @if(count($holdings) > 0)
            <div class="w-full max-h-[300px] flex items-center justify-center">
                <div class="w-full max-w-[280px]">
                    <canvas id="holdingsValueChart"></canvas>
                </div>
            </div>
        @else
            <div class="text-center py-20 text-on-surface-variant flex flex-col items-center">
                <span class="material-symbols-outlined text-4xl mb-3 text-white/20">pie_chart</span>
                <p class="text-sm">Belum ada data alokasi.</p>
            </div>
        @endif
    </div>

    <!-- Chart: Balance by Exchange -->
    <div class="glass-panel rounded-2xl p-6 shadow-lg">
        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-6">Saldo Berdasarkan Platform (Bursa)</h3>
        @if($totalValue > 0)
            <div class="w-full max-h-[300px] flex items-center justify-center">
                <div class="w-full max-w-[280px]">
                    <canvas id="exchangeChart"></canvas>
                </div>
            </div>
        @else
            <div class="text-center py-20 text-on-surface-variant flex flex-col items-center">
                <span class="material-symbols-outlined text-4xl mb-3 text-white/20">hub</span>
                <p class="text-sm">Belum ada dana yang terdistribusi di platform.</p>
            </div>
        @endif
    </div>
</div>

<!-- Indo Crypto Tax Estimator Card (PMK-68) -->
<div class="glass-panel rounded-2xl p-8 shadow-lg border border-primary/20 bg-primary/[0.02]">
    <div class="flex items-start gap-4 mb-6">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-2xl">receipt_long</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-white font-heading">Estimasi Pajak Crypto Indonesia (PMK No. 68)</h3>
            <p class="text-on-surface-variant text-sm mt-1">Simulasi pelaporan pajak berdasarkan peraturan perpajakan aset kripto di Indonesia.</p>
        </div>
    </div>

    @php
        // Fetch all user transactions to compute tax values
        $allTx = Auth::user()->transactions;
        $totalVolume = $allTx->sum('total_usd');
        
        // Under PMK-68:
        // Transactions on registered exchanges (e.g. Indodax, Tokocrypto):
        // - PPh 22 Final = 0.1% of transaction value
        // - PPN = 0.11% of transaction value
        // Let's assume these rates
        $pphRate = 0.001; // 0.1%
        $ppnRate = 0.0011; // 0.11%
        
        $estimatedPPh = $totalVolume * $pphRate;
        $estimatedPPN = $totalVolume * $ppnRate;
        $totalTax = $estimatedPPh + $estimatedPPN;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-y border-white/5 py-6 mb-6">
        <div class="space-y-1">
            <span class="text-xs text-on-surface-variant font-medium">Total Volume Transaksi</span>
            <p class="text-2xl font-bold text-white">${{ number_format($totalVolume, 2) }}</p>
            <span class="text-[10px] text-on-surface-variant block">Akumulasi seluruh nilai transaksi</span>
        </div>
        <div class="space-y-1">
            <span class="text-xs text-on-surface-variant font-medium">Estimasi PPh 22 Final (0.1%)</span>
            <p class="text-2xl font-bold text-white">${{ number_format($estimatedPPh, 2) }}</p>
            <span class="text-[10px] text-on-surface-variant block">PPh atas transaksi penyerahan aset kripto</span>
        </div>
        <div class="space-y-1">
            <span class="text-xs text-on-surface-variant font-medium">Estimasi PPN Kripto (0.11%)</span>
            <p class="text-2xl font-bold text-white">${{ number_format($estimatedPPN, 2) }}</p>
            <span class="text-[10px] text-on-surface-variant block">PPN atas transaksi pembelian/penjualan</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/[0.02] border border-white/5 p-4 rounded-xl">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
            <div>
                <span class="text-xs text-on-surface-variant font-medium block">Total Estimasi Kewajiban Pajak</span>
                <span class="text-lg font-bold text-white">${{ number_format($totalTax, 2) }}</span>
            </div>
        </div>
        <div class="text-xs text-on-surface-variant max-w-md text-left sm:text-right leading-relaxed">
            *Estimasi di atas menggunakan tarif standar PMK-68/PMK.03/2022 untuk transaksi melalui pedagang fisik aset kripto terdaftar (Bappebti). Estimasi ini bersifat edukatif untuk simulasi Tugas Akhir (UAS).
        </div>
    </div>
</div>

@if(count($holdings) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colors = ['#8b5cf6', '#a78bfa', '#c084fc', '#e879f9', '#6366f1', '#4f46e5', '#a855f7', '#d946ef', '#ec4899', '#f43f5e'];

        // Holdings Chart
        const hCtx = document.getElementById('holdingsValueChart').getContext('2d');
        const holdingsData = {
            labels: {!! json_encode(array_column($holdings, 'symbol')) !!},
            datasets: [{
                data: {!! json_encode(array_column($holdings, 'value')) !!},
                backgroundColor: colors.slice(0, {!! count($holdings) !!}),
                borderWidth: 1,
                borderColor: 'rgba(255, 255, 255, 0.05)'
            }]
        };

        new Chart(hCtx, {
            type: 'pie',
            data: holdingsData,
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#a19baf',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            },
                            boxWidth: 12
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Exchange Distribution Chart
        const eCtx = document.getElementById('exchangeChart').getContext('2d');
        const exchangeData = {
            labels: {!! json_encode(array_keys($exchanges)) !!},
            datasets: [{
                data: {!! json_encode(array_values($exchanges)) !!},
                backgroundColor: ['#6366f1', '#8b5cf6', '#a855f7'],
                borderWidth: 1,
                borderColor: 'rgba(255, 255, 255, 0.05)'
            }]
        };

        new Chart(eCtx, {
            type: 'doughnut',
            data: exchangeData,
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#a19baf',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            },
                            boxWidth: 12
                        }
                    }
                },
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endif
@endsection
