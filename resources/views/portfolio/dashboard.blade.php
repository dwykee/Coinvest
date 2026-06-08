@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Koinly-inspired dashboard styles */
    .dash-tabs {
        display: inline-flex;
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        padding: 3px;
        gap: 2px;
    }
    .dash-tab {
        padding: 6px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #8a8494;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: transparent;
    }
    .dash-tab.active {
        background: #8b5cf6;
        color: #fff;
        box-shadow: 0 2px 8px rgba(139,92,246,0.3);
    }
    .dash-tab:hover:not(.active) {
        color: #ccc;
        background: rgba(255,255,255,0.05);
    }

    /* Date range picker style */
    .date-range-picker {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        color: #ccc;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .date-range-picker:hover {
        border-color: rgba(255,255,255,0.15);
    }

    /* Main overview card */
    .overview-card {
        background: rgba(18, 17, 24, 0.6);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 0;
        overflow: hidden;
    }
    .overview-card-inner {
        padding: 28px 32px 0 32px;
    }

    /* Chart area placeholder (sparkline-like line) */
    .chart-area {
        position: relative;
        height: 160px;
        margin-top: 20px;
        overflow: hidden;
    }
    .chart-line {
        position: absolute;
        bottom: 40%;
        left: 0;
        right: 0;
        height: 2px;
        background: repeating-linear-gradient(
            90deg,
            #3b82f6 0px,
            #3b82f6 8px,
            transparent 8px,
            transparent 14px
        );
        opacity: 0.5;
    }

    /* Stats bar at bottom of overview */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        border-top: 1px solid rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.02);
    }
    .stat-item {
        padding: 16px 20px;
        border-right: 1px solid rgba(255,255,255,0.06);
    }
    .stat-item:last-child {
        border-right: none;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #8a8494;
        text-transform: capitalize;
        margin-bottom: 4px;
    }
    .stat-label.green { color: #10B981; }
    .stat-label.red { color: #EF4444; }
    .stat-label.blue { color: #3b82f6; }
    .stat-value {
        font-size: 15px;
        font-weight: 700;
        color: #f5f3f7;
        font-family: 'Sora', sans-serif;
    }

    /* Holdings card */
    .holdings-card {
        background: rgba(18, 17, 24, 0.6);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        overflow: hidden;
    }
    .holdings-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .holdings-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #f5f3f7;
        font-family: 'Sora', sans-serif;
    }
    .holdings-header-left .icon-circle {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(139,92,246,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .holdings-body {
        padding: 24px;
    }

    /* Holdings table */
    .holdings-table {
        width: 100%;
        border-collapse: collapse;
    }
    .holdings-table th {
        padding: 10px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b6579;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .holdings-table td {
        padding: 14px 12px;
        font-size: 13px;
        color: #f5f3f7;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .holdings-table tr:last-child td {
        border-bottom: none;
    }
    .holdings-table tr {
        transition: background 0.15s;
    }
    .holdings-table tr:hover {
        background: rgba(255,255,255,0.02);
    }

    /* Asset cell */
    .asset-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .asset-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        text-transform: uppercase;
    }
    .asset-name {
        font-weight: 700;
        font-size: 13px;
    }
    .asset-fullname {
        font-size: 11px;
        color: #6b6579;
        margin-top: 1px;
    }

    /* PnL badge */
    .pnl-positive { color: #10B981; }
    .pnl-negative { color: #EF4444; }

    /* Chart & Table layout */
    .holdings-grid {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 32px;
        align-items: start;
    }

    /* Pie chart container */
    .pie-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .pie-legend {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .pie-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
    }
    .pie-legend-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pie-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .pie-legend-symbol {
        font-weight: 700;
        color: #f5f3f7;
    }
    .pie-legend-pct {
        font-weight: 600;
        color: #a19baf;
    }

    /* Recent tx section */
    .recent-tx-card {
        background: rgba(18, 17, 24, 0.6);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        overflow: hidden;
    }
    .recent-tx-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .recent-tx-body {
        padding: 0;
    }

    /* TX type badges */
    .tx-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .tx-badge-buy {
        background: rgba(16,185,129,0.1);
        color: #10B981;
        border: 1px solid rgba(16,185,129,0.15);
    }
    .tx-badge-sell {
        background: rgba(239,68,68,0.1);
        color: #EF4444;
        border: 1px solid rgba(239,68,68,0.15);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-bar {
            grid-template-columns: repeat(3, 1fr);
        }
        .stat-item:nth-child(3) {
            border-right: none;
        }
        .holdings-grid {
            grid-template-columns: 1fr;
        }
        .pie-container {
            flex-direction: row;
            gap: 24px;
        }
    }
    @media (max-width: 640px) {
        .stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        .stat-item:nth-child(2n) {
            border-right: none;
        }
        .overview-card-inner {
            padding: 20px 18px 0 18px;
        }
        .pie-container {
            flex-direction: column;
        }
    }
</style>

<!-- Top Bar: Tabs + Date Range -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="dash-tabs">
        <button class="dash-tab active">Overview</button>
        <button class="dash-tab" onclick="window.location.href='{{ route('market') }}'">Pasar Live</button>
        <button class="dash-tab" onclick="window.location.href='{{ route('reports') }}'">Analisis</button>
    </div>
    <div class="date-range-picker">
        <span class="material-symbols-outlined text-base" style="font-size:16px">date_range</span>
        <span>Jan 1, {{ date('Y') }} – {{ date('M j, Y') }}</span>
        <span class="material-symbols-outlined text-base" style="font-size:14px;opacity:0.5">calendar_today</span>
    </div>
</div>

<!-- Overview Card -->
<div class="overview-card mb-6">
    <div class="overview-card-inner">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <!-- Left: Total value -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span style="font-size: 13px; color: #8a8494; font-weight: 600;">Total value</span>
                    <span class="material-symbols-outlined" style="font-size:14px; color:#6b6579; cursor:help;" title="Nilai total portofolio berdasarkan harga pasar terkini">info</span>
                </div>
                <div class="flex items-end gap-3">
                    <h1 class="font-heading" style="font-size: 36px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -1px;">
                        ${{ number_format($totalValue, 2) }}
                    </h1>
                    @php $pnlUp = $netPnLPercent >= 0; @endphp
                    <span class="flex items-center gap-1 text-sm font-bold {{ $pnlUp ? 'pnl-positive' : 'pnl-negative' }}" style="margin-bottom: 4px;">
                        <span class="material-symbols-outlined" style="font-size:16px">{{ $pnlUp ? 'arrow_upward' : 'arrow_downward' }}</span>
                        {{ number_format(abs($netPnLPercent), 2) }}%
                    </span>
                </div>
            </div>

            <!-- Right: Cost basis & Unrealized gains -->
            <div class="flex gap-10">
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span style="font-size: 12px; color: #8a8494; font-weight: 600;">Cost basis</span>
                        <span class="material-symbols-outlined" style="font-size:13px; color:#6b6579; cursor:help;" title="Total modal investasi">info</span>
                    </div>
                    <span class="font-heading" style="font-size: 20px; font-weight: 700; color: #fff;">
                        ${{ number_format($totalCost, 2) }}
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-1 mb-1">
                        <span style="font-size: 12px; color: #8a8494; font-weight: 600;">Unrealized gains</span>
                        <span class="material-symbols-outlined" style="font-size:13px; color:#6b6579; cursor:help;" title="Keuntungan/kerugian yang belum direalisasikan">info</span>
                    </div>
                    <span class="font-heading {{ $pnlUp ? 'pnl-positive' : 'pnl-negative' }}" style="font-size: 20px; font-weight: 700;">
                        {{ $pnlUp ? '+' : '' }}${{ number_format($netPnL, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Sparkline chart area -->
        <div class="chart-area">
            <canvas id="portfolioChart" style="width:100%;height:100%;"></canvas>
            <div class="chart-line" style="display:none;"></div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        @php
            $totalBuy = $recentTransactions->where('type','buy')->sum('total_usd') ?? 0;
            $totalSell = $recentTransactions->where('type','sell')->sum('total_usd') ?? 0;
        @endphp
        <div class="stat-item">
            <div class="stat-label green">In</div>
            <div class="stat-value">${{ number_format($totalBuy, 0) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label red">Out</div>
            <div class="stat-value">${{ number_format($totalSell, 0) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label blue">Income</div>
            <div class="stat-value">${{ number_format($totalBuy, 0) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label red">Expenses</div>
            <div class="stat-value">${{ number_format($totalSell, 0) }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Trading fees</div>
            <div class="stat-value">$0</div>
        </div>
        <div class="stat-item">
            <div class="stat-label {{ $netPnL >= 0 ? 'green' : 'red' }}">Realized gains</div>
            <div class="stat-value {{ $netPnL >= 0 ? 'pnl-positive' : 'pnl-negative' }}">
                {{ $netPnL >= 0 ? '+' : '' }}${{ number_format($netPnL, 0) }}
            </div>
        </div>
    </div>
</div>

<!-- Holdings Section -->
<div class="holdings-card mb-6">
    <div class="holdings-header">
        <div class="holdings-header-left">
            <div class="icon-circle">
                <span class="material-symbols-outlined" style="font-size:16px; color:#8b5cf6">account_balance</span>
            </div>
            <span>Holdings</span>
            <span style="font-size:12px;color:#6b6579;font-weight:500;background:rgba(255,255,255,0.04);padding:2px 8px;border-radius:6px;">{{ count($holdings) }}</span>
        </div>
        <a href="{{ route('transactions.index') }}" class="flex items-center gap-1 text-xs font-semibold hover:underline" style="color:#8b5cf6">
            <span class="material-symbols-outlined" style="font-size:14px">add</span>
            Tambah Transaksi
        </a>
    </div>
    <div class="holdings-body">
        @if(count($holdings) > 0)
            <div class="holdings-grid">
                <!-- Pie Chart Column -->
                <div class="pie-container">
                    <div style="width:180px;height:180px;">
                        <canvas id="allocationChart"></canvas>
                    </div>
                    <div class="pie-legend">
                        @php
                            $colors = ['#8b5cf6', '#a78bfa', '#c084fc', '#e879f9', '#6366f1', '#4f46e5', '#a855f7', '#d946ef', '#ec4899', '#f43f5e'];
                        @endphp
                        @foreach($holdings as $index => $holding)
                            @php
                                $color = $colors[$index % count($colors)];
                                $percentage = $totalValue > 0 ? ($holding['current_value'] / $totalValue) * 100 : 0;
                            @endphp
                            <div class="pie-legend-item">
                                <div class="pie-legend-left">
                                    <span class="pie-legend-dot" style="background:{{ $color }}"></span>
                                    <span class="pie-legend-symbol">{{ $holding['symbol'] }}</span>
                                </div>
                                <span class="pie-legend-pct">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Table Column -->
                <div style="overflow-x:auto;">
                    <table class="holdings-table">
                        <thead>
                            <tr>
                                <th>Aset</th>
                                <th style="text-align:right">Jumlah</th>
                                <th style="text-align:right">Avg. Buy</th>
                                <th style="text-align:right">Harga</th>
                                <th style="text-align:right">Nilai</th>
                                <th style="text-align:right">PnL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $assetColors = [
                                    'BTC' => '#f7931a',
                                    'ETH' => '#627eea',
                                    'SOL' => '#00ffa3',
                                    'BNB' => '#f0b90b',
                                    'XRP' => '#00aae4',
                                    'ADA' => '#0033ad',
                                    'DOGE' => '#c2a633',
                                    'DOT' => '#e6007a',
                                    'AVAX' => '#e84142',
                                    'LINK' => '#2a5ada',
                                ];
                            @endphp
                            @foreach($holdings as $holding)
                                @php $ac = $assetColors[$holding['symbol']] ?? '#8b5cf6'; @endphp
                                <tr>
                                    <td>
                                        <div class="asset-cell">
                                            <div class="asset-icon" style="background:{{ $ac }}20; color:{{ $ac }}; border: 1px solid {{ $ac }}30;">
                                                {{ substr($holding['symbol'], 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="asset-name">{{ $holding['symbol'] }}</div>
                                                <div class="asset-fullname">{{ $holding['name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align:right;font-weight:600;">{{ number_format($holding['quantity'], 4) }}</td>
                                    <td style="text-align:right;color:#8a8494;">${{ number_format($holding['avg_buy_price'], 2) }}</td>
                                    <td style="text-align:right;font-weight:600;">${{ number_format($holding['current_price'], 2) }}</td>
                                    <td style="text-align:right;font-weight:700;">${{ number_format($holding['current_value'], 2) }}</td>
                                    <td style="text-align:right;">
                                        <div class="{{ $holding['pnl'] >= 0 ? 'pnl-positive' : 'pnl-negative' }}" style="font-weight:700;font-size:13px;">
                                            {{ $holding['pnl'] >= 0 ? '+' : '' }}${{ number_format($holding['pnl'], 2) }}
                                        </div>
                                        <div class="{{ $holding['pnl'] >= 0 ? 'pnl-positive' : 'pnl-negative' }}" style="font-size:11px;opacity:0.8;">
                                            {{ $holding['pnl'] >= 0 ? '+' : '' }}{{ number_format($holding['pnl_percent'], 2) }}%
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div style="text-align:center;padding:48px 0;color:#6b6579;">
                <span class="material-symbols-outlined" style="font-size:48px;opacity:0.3;display:block;margin-bottom:12px;">account_balance_wallet</span>
                <p style="font-size:14px;margin-bottom:8px;">You don't have any holdings yet!</p>
                <a href="{{ route('transactions.index') }}" style="color:#8b5cf6;font-size:13px;font-weight:600;" class="hover:underline">Tambah transaksi pertama →</a>
            </div>
        @endif
    </div>
</div>

<!-- Recent Transactions -->
<div class="recent-tx-card">
    <div class="recent-tx-header">
        <div class="holdings-header-left">
            <div class="icon-circle">
                <span class="material-symbols-outlined" style="font-size:16px; color:#8b5cf6">receipt_long</span>
            </div>
            <span style="font-size:15px;font-weight:700;color:#f5f3f7;font-family:'Sora',sans-serif;">Transaksi Terakhir</span>
        </div>
        <a href="{{ route('transactions.index') }}" class="flex items-center gap-1 text-xs font-semibold hover:underline" style="color:#8b5cf6">
            Lihat Semua
            <span class="material-symbols-outlined" style="font-size:14px">arrow_forward</span>
        </a>
    </div>
    <div class="recent-tx-body">
        @if(count($recentTransactions) > 0)
            <table class="holdings-table">
                <thead>
                    <tr>
                        <th style="padding-left:24px;">Tanggal</th>
                        <th>Tipe</th>
                        <th>Aset</th>
                        <th style="text-align:right">Jumlah</th>
                        <th style="text-align:right">Harga</th>
                        <th style="text-align:right;padding-right:24px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $tx)
                        <tr>
                            <td style="padding-left:24px;color:#8a8494;font-size:12px;">
                                {{ $tx->transaction_date->format('d M Y') }}
                            </td>
                            <td>
                                <span class="tx-badge {{ $tx->type === 'buy' ? 'tx-badge-buy' : 'tx-badge-sell' }}">
                                    {{ $tx->type === 'buy' ? 'BUY' : 'SELL' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight:700;">{{ $tx->asset_symbol }}</span>
                                <span style="color:#6b6579;font-size:11px;margin-left:4px;">{{ $tx->asset_name }}</span>
                            </td>
                            <td style="text-align:right;font-weight:600;">{{ number_format($tx->quantity, 4) }}</td>
                            <td style="text-align:right;color:#8a8494;">${{ number_format($tx->price_usd, 2) }}</td>
                            <td style="text-align:right;font-weight:700;padding-right:24px;">${{ number_format($tx->total_usd, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align:center;padding:40px 0;color:#6b6579;">
                <span class="material-symbols-outlined" style="font-size:36px;opacity:0.3;display:block;margin-bottom:8px;">history</span>
                <p style="font-size:13px;">Belum ada riwayat transaksi.</p>
            </div>
        @endif
    </div>
</div>

<!-- Charts Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($holdings) > 0)
    // -- Doughnut / Pie Chart --
    const pieCtx = document.getElementById('allocationChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($holdings, 'symbol')) !!},
            datasets: [{
                data: {!! json_encode(array_column($holdings, 'current_value')) !!},
                backgroundColor: {!! json_encode(array_map(function($idx) use ($colors) { return $colors[$idx % count($colors)]; }, array_keys($holdings))) !!},
                borderWidth: 2,
                borderColor: 'rgba(18, 17, 24, 0.8)',
                hoverOffset: 6,
                hoverBorderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(28,27,31,0.95)',
                    titleColor: '#f5f3f7',
                    bodyColor: '#a19baf',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed !== null) {
                                label += ': ' + new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed);
                            }
                            return label;
                        }
                    }
                }
            },
            cutout: '72%',
            responsive: true,
            maintainAspectRatio: false
        }
    });
    @endif

    // -- Portfolio Sparkline Chart --
    const chartCtx = document.getElementById('portfolioChart');
    if (chartCtx) {
        const totalVal = {{ $totalValue }};
        // Generate synthetic portfolio performance data
        const days = 30;
        const dataPoints = [];
        const labels = [];
        let val = totalVal * 0.85;
        for (let i = 0; i < days; i++) {
            val += (Math.random() - 0.45) * (totalVal * 0.03);
            val = Math.max(val, totalVal * 0.7);
            dataPoints.push(val);
            labels.push('');
        }
        dataPoints[days - 1] = totalVal;

        const gradient = chartCtx.getContext('2d').createLinearGradient(0, 0, 0, 160);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: dataPoints,
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28,27,31,0.95)',
                        titleColor: '#f5f3f7',
                        bodyColor: '#a19baf',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
});
</script>

@endsection
