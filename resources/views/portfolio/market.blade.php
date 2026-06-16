@extends('layouts.app')

@section('title', 'Pasar Live')

@section('content')
<style>
    [x-cloak] {
        display: none !important;
    }

    .price-up {
        color: #10B981;
    }

    .price-down {
        color: #EF4444;
    }

    @keyframes flashGreen {
        0% {
            background: rgba(16, 185, 129, 0.15);
        }

        100% {
            background: transparent;
        }
    }

    @keyframes flashRed {
        0% {
            background: rgba(239, 68, 68, 0.15);
        }

        100% {
            background: transparent;
        }
    }

    .flash-up {
        animation: flashGreen 0.8s ease-out;
    }

    .flash-down {
        animation: flashRed 0.8s ease-out;
    }

    .coin-row {
        transition: background 0.15s;
    }

    .coin-row:hover {
        background: rgba(255, 255, 255, 0.015);
    }

    .coin-logo,
    .coin-logo-fallback {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .coin-logo {
        object-fit: contain;
        background: rgba(255, 255, 255, 0.05);
    }

    .coin-logo-fallback {
        background: rgba(139, 92, 246, 0.15);
        border: 1px solid rgba(139, 92, 246, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #8b5cf6;
        font-family: 'Sora', sans-serif;
    }

    .change-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid;
    }

    .change-up {
        color: #10B981;
        background: rgba(16, 185, 129, 0.08);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .change-down {
        color: #EF4444;
        background: rgba(239, 68, 68, 0.08);
        border-color: rgba(239, 68, 68, 0.2);
    }

    .refresh-ring {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 6px rgba(16, 185, 129, 0.7);
        animation: pulse 1.5s infinite;
    }

    .refresh-ring.loading {
        background: #f59e0b;
        box-shadow: 0 0 6px rgba(245, 158, 11, 0.7);
        animation: spin 1s linear infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(0.8);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="font-heading" style="font-size:26px;font-weight:800;color:#f5f3f7;letter-spacing:-0.5px;">
            Pasar Crypto Real-Time
        </h1>
        <p style="font-size:13px;color:#7a7485;margin-top:4px;">
            Pantau pergerakan harga aset crypto utama secara langsung.
        </p>
    </div>
</div>

<div x-data="marketApp()" x-init="init()">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <div class="relative" style="max-width:360px;width:100%;">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size:18px;">
                search
            </span>

            <input
                type="text"
                x-model="search"
                placeholder="Cari nama koin atau simbol..."
                style="width:100%;padding:10px 14px 10px 42px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;color:#f5f3f7;font-size:13px;outline:none;transition:border-color 0.2s;"
                onfocus="this.style.borderColor='rgba(139,92,246,0.5)'"
                onblur="this.style.borderColor='rgba(255,255,255,0.08)'"
            >
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button
                type="button"
                @click="fetchPrices(true)"
                :disabled="loading"
                style="display:flex;align-items:center;gap:6px;font-size:12px;color:#c4b5fd;background:rgba(139,92,246,0.10);border:1px solid rgba(139,92,246,0.25);padding:8px 14px;border-radius:10px;font-weight:700;cursor:pointer;"
            >
                <span class="material-symbols-outlined" style="font-size:14px;" x-text="loading ? 'hourglass_top' : 'sync'"></span>
                <span x-text="loading ? 'Loading...' : 'Refresh'"></span>
            </button>

            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#7a7485;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);padding:8px 14px;border-radius:10px;">
                <div class="refresh-ring" :class="loading ? 'loading' : ''"></div>
                <span x-show="!loading">Update dalam <strong x-text="countdown" style="color:#f5f3f7;"></strong>s</span>
                <span x-show="loading" style="color:#f59e0b;">Memperbarui...</span>
            </div>

            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#7a7485;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);padding:8px 14px;border-radius:10px;">
                <span style="font-size:11px;" x-text="lastUpdate ? 'Update: ' + lastUpdate : 'Menunggu data...'"></span>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-2xl overflow-hidden shadow-lg">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                        <th style="padding:14px 20px;text-align:center;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;width:60px;">#</th>
                        <th style="padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Koin</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Harga USD</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">24h</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Market Cap</th>
                        <th style="padding:14px 20px;text-align:center;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;width:180px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="(coin, index) in filteredCoins()" :key="coin.symbol">
                        <tr class="coin-row" :id="'row-' + coin.symbol" style="border-bottom:1px solid rgba(255,255,255,0.03);">
                            <td style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#4d4757;" x-text="index + 1"></td>

                            <td style="padding:14px 20px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <template x-if="coin.logo">
                                        <img
                                            :src="coin.logo"
                                            :alt="coin.symbol"
                                            class="coin-logo"
                                            x-on:error="coin.logo = null"
                                        >
                                    </template>

                                    <template x-if="!coin.logo">
                                        <div class="coin-logo-fallback" x-text="coin.symbol.substring(0, 2)"></div>
                                    </template>

                                    <div>
                                        <div style="font-size:14px;font-weight:700;color:#f5f3f7;font-family:'Sora',sans-serif;" x-text="coin.symbol"></div>
                                        <div style="font-size:11px;color:#7a7485;margin-top:1px;" x-text="coin.name"></div>
                                    </div>
                                </div>
                            </td>

                            <td style="padding:14px 20px;text-align:right;">
                                <span
                                    style="font-size:14px;font-weight:700;color:#f5f3f7;font-family:'Sora',sans-serif;"
                                    x-text="formatUsd(coin.price)"
                                ></span>
                            </td>

                            <td style="padding:14px 20px;text-align:right;">
                                <span class="change-badge" :class="coin.change >= 0 ? 'change-up' : 'change-down'">
                                    <span class="material-symbols-outlined" style="font-size:11px;" x-text="coin.change >= 0 ? 'arrow_upward' : 'arrow_downward'"></span>
                                    <span x-text="Math.abs(coin.change || 0).toFixed(2) + '%'"></span>
                                </span>
                            </td>

                            <td style="padding:14px 20px;text-align:right;font-size:12px;color:#a19baf;font-weight:600;">
                                <span x-text="coin.market_cap ? '$' + formatMarketCap(coin.market_cap) : '—'"></span>
                            </td>

                            <td style="padding:14px 20px;">
                                <div style="display:flex;justify-content:center;gap:8px;">
                                    <button
                                        type="button"
                                        @click="openTrade('buy', coin)"
                                        style="padding:6px 14px;background:rgba(16,185,129,0.08);color:#10B981;border:1px solid rgba(16,185,129,0.2);border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.15s;"
                                        onmouseover="this.style.background='#10B981';this.style.color='#fff'"
                                        onmouseout="this.style.background='rgba(16,185,129,0.08)';this.style.color='#10B981'"
                                    >
                                        <span class="material-symbols-outlined" style="font-size:13px;">add_shopping_cart</span>
                                        Beli
                                    </button>

                                    <button
                                        type="button"
                                        @click="openTrade('sell', coin)"
                                        style="padding:6px 14px;background:rgba(239,68,68,0.08);color:#EF4444;border:1px solid rgba(239,68,68,0.2);border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.15s;"
                                        onmouseover="this.style.background='#EF4444';this.style.color='#fff'"
                                        onmouseout="this.style.background='rgba(239,68,68,0.08)';this.style.color='#EF4444'"
                                    >
                                        <span class="material-symbols-outlined" style="font-size:13px;">sell</span>
                                        Jual
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="filteredCoins().length === 0 && !loading" x-cloak>
                        <td colspan="6" style="padding:48px;text-align:center;color:#4d4757;font-size:13px;">
                            <span class="material-symbols-outlined" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;">search_off</span>
                            Tidak ada koin yang cocok.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background:rgba(10,10,11,0.85);backdrop-filter:blur(8px);"
        x-transition
    >
        <div
            @click.away="modalOpen = false"
            class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl relative"
        >
            <button
                type="button"
                @click="modalOpen = false"
                style="position:absolute;top:16px;right:16px;background:none;border:none;color:#7a7485;cursor:pointer;transition:color 0.15s;"
                onmouseover="this.style.color='#f5f3f7'"
                onmouseout="this.style.color='#7a7485'"
            >
                <span class="material-symbols-outlined">close</span>
            </button>

            <h3 class="font-heading" style="font-size:17px;font-weight:700;color:#f5f3f7;margin-bottom:22px;display:flex;align-items:center;gap:8px;">
                <span
                    class="material-symbols-outlined"
                    :style="tradeType === 'buy' ? 'color:#10B981' : 'color:#EF4444'"
                    x-text="tradeType === 'buy' ? 'add_shopping_cart' : 'sell'"
                ></span>

                <span>Quick</span>
                <span x-text="tradeType === 'buy' ? 'Beli' : 'Jual'"></span>
                <span x-text="tradeSymbol"></span>
            </h3>

            <form method="POST" action=" route('transactions.store') " style="display:flex;flex-direction:column;gap:14px;">
                @csrf

                <input type="hidden" name="type" :value="tradeType">
                <input type="hidden" name="asset_symbol" :value="tradeSymbol">

                <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:#f5f3f7;" x-text="tradeName"></div>
                        <div style="font-size:11px;color:#7a7485;margin-top:2px;" x-text="'Harga pasar: ' + formatUsd(tradePrice)"></div>
                    </div>

                    <span
                        style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:0.5px;"
                        :style="tradeType === 'buy' ? 'background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2)' : 'background:rgba(239,68,68,0.1);color:#EF4444;border:1px solid rgba(239,68,68,0.2)'"
                        x-text="tradeType === 'buy' ? 'BELI' : 'JUAL'"
                    ></span>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Jumlah Koin</label>

                    <div style="position:relative;">
                        <input
                            type="number"
                            step="any"
                            min="0.00000001"
                            name="quantity"
                            x-model="tradeQuantity"
                            required
                            placeholder="0.000"
                            style="width:100%;padding:11px 50px 11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;"
                        >

                        <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:#8b5cf6;" x-text="tradeSymbol"></span>
                    </div>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Harga USD/Koin</label>

                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:12px;color:#7a7485;">$</span>

                        <input
                            type="number"
                            step="any"
                            min="0.00000001"
                            name="price_usd"
                            x-model="tradePrice"
                            required
                            style="width:100%;padding:11px 14px 11px 28px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;"
                        >
                    </div>
                </div>

                <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:10px;padding:12px;display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#7a7485;">Estimasi Total:</span>
                    <span
                        style="color:#f5f3f7;font-weight:700;"
                        x-text="tradePrice && tradeQuantity ? new Intl.NumberFormat('en-US', { style:'currency', currency:'USD' }).format(tradePrice * tradeQuantity) : '$0.00'"
                    ></span>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Tanggal Transaksi</label>

                    <input
                        type="datetime-local"
                        name="transaction_date"
                        required
                        x-model="tradeDate"
                        style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;"
                    >
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Catatan</label>

                    <textarea
                        name="notes"
                        rows="2"
                        placeholder="Opsional..."
                        x-model="tradeNotes"
                        style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;resize:none;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif;"
                    ></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px;">
                    <button
                        type="button"
                        @click="modalOpen = false"
                        style="padding:12px;background:rgba(255,255,255,0.04);color:#a19baf;border:1px solid rgba(255,255,255,0.08);border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        :style="tradeType === 'buy' ? 'background:linear-gradient(180deg,#34d399,#10B981)' : 'background:linear-gradient(180deg,#f87171,#EF4444)'"
                        style="padding:12px;color:#fff;border:none;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;"
                    >
                        <span class="material-symbols-outlined" style="font-size:15px;">check</span>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $marketCoins = collect($livePrices)->map(function ($info, $symbol) {
        return [
            'symbol' => $symbol,
            'name' => $info['name'] ?? $symbol,
            'price' => (float) ($info['price'] ?? 0),
            'change' => (float) ($info['change'] ?? 0),
            'market_cap' => 0,
            'logo' => null,
        ];
    })->values();
@endphp

<script>
function marketApp() {
    return {
        marketPricesUrl: @json(route('market.prices')),

        search: '',
        modalOpen: false,

        tradeType: 'buy',
        tradeSymbol: 'BTC',
        tradeName: 'Bitcoin',
        tradePrice: 0,
        tradeQuantity: '',
        tradeDate: @json(date('Y-m-d\TH:i')),
        tradeNotes: '',

        loading: false,
        countdown: 30,
        lastUpdate: null,
        timer: null,

        coins: @json($marketCoins),

        coinIds: {
            BTC: 'bitcoin',
            ETH: 'ethereum',
            SOL: 'solana',
            BNB: 'binancecoin',
            XRP: 'ripple',
            ADA: 'cardano',
            DOGE: 'dogecoin',
            DOT: 'polkadot',
            AVAX: 'avalanche-2',
            LINK: 'chainlink',
            MATIC: 'matic-network',
            USDT: 'tether',
            USDC: 'usd-coin',
        },

        init() {
            this.lastUpdate = new Date().toLocaleTimeString('id-ID');
            this.fetchMarketMeta();
            this.startCountdown();
        },

        filteredCoins() {
            const q = this.search.trim().toLowerCase();

            if (!q) {
                return this.coins;
            }

            return this.coins.filter((coin) => {
                return coin.symbol.toLowerCase().includes(q)
                    || coin.name.toLowerCase().includes(q);
            });
        },

        formatUsd(value) {
            const num = Number(value || 0);

            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: num < 1 ? 4 : 2,
                maximumFractionDigits: num < 1 ? 8 : 2,
            }).format(num);
        },

        formatMarketCap(value) {
            const num = Number(value || 0);

            if (num >= 1e12) return (num / 1e12).toFixed(2) + 'T';
            if (num >= 1e9) return (num / 1e9).toFixed(2) + 'B';
            if (num >= 1e6) return (num / 1e6).toFixed(2) + 'M';

            return num.toLocaleString('en-US');
        },

        async fetchMarketMeta() {
            const ids = this.coins
                .map((coin) => this.coinIds[coin.symbol])
                .filter(Boolean)
                .join(',');

            if (!ids) return;

            try {
                const response = await fetch(
                    'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=' + encodeURIComponent(ids) + '&order=market_cap_desc&per_page=100&page=1&sparkline=false&price_change_percentage=24h',
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                        cache: 'no-store',
                    }
                );

                if (!response.ok) return;

                const data = await response.json();

                data.forEach((item) => {
                    const symbol = String(item.symbol || '').toUpperCase();
                    const idx = this.coins.findIndex((coin) => coin.symbol === symbol);

                    if (idx === -1) return;

                    this.coins[idx].logo = item.image || null;
                    this.coins[idx].market_cap = Number(item.market_cap || 0);
                });
            } catch (error) {
                console.warn('Gagal fetch market meta:', error);
            }
        },

        async fetchPrices(manual = false) {
            if (this.loading) return;

            this.loading = true;

            try {
                const response = await fetch(this.marketPricesUrl + '?t=' + Date.now(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    cache: 'no-store',
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const data = await response.json();

                data.forEach((updated) => {
                    const idx = this.coins.findIndex((coin) => coin.symbol === updated.symbol);

                    if (idx === -1) return;

                    const oldPrice = Number(this.coins[idx].price || 0);
                    const newPrice = Number(updated.price || 0);

                    this.coins[idx].price = newPrice;
                    this.coins[idx].change = Number(updated.change || 0);

                    this.$nextTick(() => {
                        const row = document.getElementById('row-' + updated.symbol);

                        if (!row) return;

                        row.classList.remove('flash-up', 'flash-down');
                        void row.offsetWidth;

                        if (newPrice > oldPrice) {
                            row.classList.add('flash-up');
                        } else if (newPrice < oldPrice) {
                            row.classList.add('flash-down');
                        }
                    });
                });

                this.lastUpdate = new Date().toLocaleTimeString('id-ID');
                this.countdown = 30;
            } catch (error) {
                console.error('Gagal fetch market prices:', error);

                if (manual) {
                    alert('Gagal memperbarui harga. Cek route /market/prices atau API CoinGecko.');
                }
            } finally {
                this.loading = false;
            }
        },

        startCountdown() {
            if (this.timer) {
                clearInterval(this.timer);
            }

            this.countdown = 30;

            this.timer = setInterval(() => {
                this.countdown--;

                if (this.countdown <= 0) {
                    this.fetchPrices(false);
                }
            }, 1000);
        },

        openTrade(type, coin) {
            this.tradeType = type;
            this.tradeSymbol = coin.symbol;
            this.tradeName = coin.name;
            this.tradePrice = Number(parseFloat(coin.price).toFixed(8));
            this.tradeQuantity = '';
            this.tradeNotes = '';
            this.tradeDate = new Date().toISOString().slice(0, 16);
            this.modalOpen = true;
        },
    };
}
</script>
@endsection