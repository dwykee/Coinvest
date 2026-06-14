@extends('layouts.app')

@section('title', 'Pasar Live')

@section('content')
<style>
    .price-up   { color: #10B981; }
    .price-down { color: #EF4444; }

    /* Flash animation saat harga update */
    @keyframes flashGreen {
        0%   { background: rgba(16,185,129,0.15); }
        100% { background: transparent; }
    }
    @keyframes flashRed {
        0%   { background: rgba(239,68,68,0.15); }
        100% { background: transparent; }
    }
    .flash-up   { animation: flashGreen 0.8s ease-out; }
    .flash-down { animation: flashRed 0.8s ease-out; }

    .coin-row { transition: background 0.15s; }
    .coin-row:hover { background: rgba(255,255,255,0.015); }

    .coin-logo {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: contain;
        background: rgba(255,255,255,0.05);
        flex-shrink: 0;
    }
    .coin-logo-fallback {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(139,92,246,0.15);
        border: 1px solid rgba(139,92,246,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #8b5cf6;
        font-family: 'Sora', sans-serif;
        flex-shrink: 0;
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
    .change-up   { color: #10B981; background: rgba(16,185,129,0.08);  border-color: rgba(16,185,129,0.2); }
    .change-down { color: #EF4444; background: rgba(239,68,68,0.08);   border-color: rgba(239,68,68,0.2); }

    .last-update {
        font-size: 11px;
        color: #4d4757;
        margin-top: 2px;
    }

    /* Countdown ring */
    .refresh-ring {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 6px rgba(16,185,129,0.7);
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(0.8); }
    }
    .refresh-ring.loading {
        background: #f59e0b;
        box-shadow: 0 0 6px rgba(245,158,11,0.7);
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
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

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <div class="relative" style="max-width:360px;width:100%;">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size:18px;">search</span>
            <input type="text" x-model="search" placeholder="Cari nama koin atau simbol..."
                   style="width:100%;padding:10px 14px 10px 42px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;color:#f5f3f7;font-size:13px;outline:none;transition:border-color 0.2s;"
                   onfocus="this.style.borderColor='rgba(139,92,246,0.5)'"
                   onblur="this.style.borderColor='rgba(255,255,255,0.08)'"
                   placeholder="Cari nama koin atau simbol...">
        </div>
        <div class="flex items-center gap-3">
            <!-- Countdown -->
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

    <!-- Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-lg">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                        <th style="padding:14px 20px;text-align:center;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;width:60px;">#</th>
                        <th style="padding:14px 20px;text-align:left;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Koin</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Harga (USD)</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">24h</th>
                        <th style="padding:14px 20px;text-align:right;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;">Market Cap</th>
                        <th style="padding:14px 20px;text-align:center;font-size:11px;font-weight:700;color:#4d4757;text-transform:uppercase;letter-spacing:0.8px;width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(coin, index) in filteredCoins()" :key="coin.symbol">
                        <tr class="coin-row" :id="'row-' + coin.symbol" style="border-bottom:1px solid rgba(255,255,255,0.03);">
                            <!-- Rank -->
                            <td style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#4d4757;" x-text="index + 1"></td>

                            <!-- Coin -->
                            <td style="padding:14px 20px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <!-- Logo -->
                                    <template x-if="coin.logo">
                                        <img :src="coin.logo" :alt="coin.symbol" class="coin-logo"
                                             @error="$el.style.display='none'; $el.nextElementSibling.style.display='flex'">
                                    </template>
                                    <div class="coin-logo-fallback" :style="coin.logo ? 'display:none' : 'display:flex'" x-text="coin.symbol.substring(0,2)"></div>

                                    <div>
                                        <div style="font-size:14px;font-weight:700;color:#f5f3f7;font-family:'Sora',sans-serif;" x-text="coin.symbol"></div>
                                        <div style="font-size:11px;color:#7a7485;margin-top:1px;" x-text="coin.name"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td style="padding:14px 20px;text-align:right;">
                                <span style="font-size:14px;font-weight:700;color:#f5f3f7;font-family:'Sora',sans-serif;"
                                      x-text="'$' + coin.price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: coin.price < 1 ? 6 : 2 })">
                                </span>
                            </td>

                            <!-- Change -->
                            <td style="padding:14px 20px;text-align:right;">
                                <span class="change-badge" :class="coin.change >= 0 ? 'change-up' : 'change-down'">
                                    <span class="material-symbols-outlined" style="font-size:11px;" x-text="coin.change >= 0 ? 'arrow_upward' : 'arrow_downward'"></span>
                                    <span x-text="Math.abs(coin.change).toFixed(2) + '%'"></span>
                                </span>
                            </td>

                            <!-- Market Cap -->
                            <td style="padding:14px 20px;text-align:right;font-size:12px;color:#a19baf;font-weight:600;">
                                <span x-text="coin.market_cap ? '$' + formatMarketCap(coin.market_cap) : '—'"></span>
                            </td>

                            <!-- Actions -->
                            <td style="padding:14px 20px;">
                                <div style="display:flex;justify-content:center;gap:8px;">
                                    <button @click="openTrade('buy', coin.symbol, coin.name, coin.price)"
                                            style="padding:6px 14px;background:rgba(16,185,129,0.08);color:#10B981;border:1px solid rgba(16,185,129,0.2);border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.15s;"
                                            onmouseover="this.style.background='#10B981';this.style.color='#fff'"
                                            onmouseout="this.style.background='rgba(16,185,129,0.08)';this.style.color='#10B981'">
                                        <span class="material-symbols-outlined" style="font-size:13px;">add_shopping_cart</span> Beli
                                    </button>
                                    <button @click="openTrade('sell', coin.symbol, coin.name, coin.price)"
                                            style="padding:6px 14px;background:rgba(239,68,68,0.08);color:#EF4444;border:1px solid rgba(239,68,68,0.2);border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.15s;"
                                            onmouseover="this.style.background='#EF4444';this.style.color='#fff'"
                                            onmouseout="this.style.background='rgba(239,68,68,0.08)';this.style.color='#EF4444'">
                                        <span class="material-symbols-outlined" style="font-size:13px;">sell</span> Jual
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty state -->
                    <tr x-show="filteredCoins().length === 0 && !loading">
                        <td colspan="6" style="padding:48px;text-align:center;color:#4d4757;font-size:13px;">
                            <span class="material-symbols-outlined" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;">search_off</span>
                            Tidak ada koin yang cocok.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Trade Modal -->
    <div x-show="modalOpen" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(10,10,11,0.85);backdrop-filter:blur(8px);"
         x-transition>
        <div @click.away="modalOpen = false"
             class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl relative">
            <button @click="modalOpen = false"
                    style="position:absolute;top:16px;right:16px;background:none;border:none;color:#7a7485;cursor:pointer;transition:color 0.15s;"
                    onmouseover="this.style.color='#f5f3f7'" onmouseout="this.style.color='#7a7485'">
                <span class="material-symbols-outlined">close</span>
            </button>

            <h3 class="font-heading" style="font-size:17px;font-weight:700;color:#f5f3f7;margin-bottom:22px;display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined"
                      :style="tradeType === 'buy' ? 'color:#10B981' : 'color:#EF4444'"
                      x-text="tradeType === 'buy' ? 'add_shopping_cart' : 'sell'"></span>
                Quick <span x-text="tradeType === 'buy' ? 'Beli' : 'Jual'" style="margin-left:4px;"></span>&nbsp;<span x-text="tradeSymbol"></span>
            </h3>

            <form method="POST" action="{{ route('transactions.store') }}" style="display:flex;flex-direction:column;gap:14px;">
                @csrf
                <input type="hidden" name="type" :value="tradeType">
                <input type="hidden" name="asset_symbol" :value="tradeSymbol">

                <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:#f5f3f7;" x-text="tradeName"></div>
                        <div style="font-size:11px;color:#7a7485;margin-top:2px;" x-text="'Harga pasar: $' + tradePrice.toLocaleString('en-US', {minimumFractionDigits:2})"></div>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:0.5px;"
                          :style="tradeType === 'buy' ? 'background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2)' : 'background:rgba(239,68,68,0.1);color:#EF4444;border:1px solid rgba(239,68,68,0.2)'"
                          x-text="tradeType === 'buy' ? 'BELI' : 'JUAL'"></span>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Jumlah Koin</label>
                    <div style="position:relative;">
                        <input type="number" step="any" min="0.00000001" name="quantity" x-model="tradeQuantity" required placeholder="0.000"
                               style="width:100%;padding:11px 50px 11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;">
                        <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:700;color:#8b5cf6;" x-text="tradeSymbol"></span>
                    </div>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Harga (USD/Koin)</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:12px;color:#7a7485;">$</span>
                        <input type="number" step="any" name="price_usd" x-model="tradePrice" required
                               style="width:100%;padding:11px 14px 11px 28px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;">
                    </div>
                </div>

                <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:10px;padding:12px;display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#7a7485;">Estimasi Total:</span>
                    <span style="color:#f5f3f7;font-weight:700;" x-text="tradePrice && tradeQuantity ? new Intl.NumberFormat('en-US',{style:'currency',currency:'USD'}).format(tradePrice * tradeQuantity) : '$0.00'"></span>
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Tanggal Transaksi</label>
                    <input type="datetime-local" name="transaction_date" required x-model="tradeDate"
                           style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;box-sizing:border-box;">
                </div>

                <div>
                    <label style="font-size:11px;font-weight:700;color:#7a7485;text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Opsional..." x-model="tradeNotes"
                              style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:#f5f3f7;font-size:14px;outline:none;resize:none;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif;"></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px;">
                    <button type="button" @click="modalOpen = false"
                            style="padding:12px;background:rgba(255,255,255,0.04);color:#a19baf;border:1px solid rgba(255,255,255,0.08);border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            :style="tradeType === 'buy' ? 'background:linear-gradient(180deg,#34d399,#10B981)' : 'background:linear-gradient(180deg,#f87171,#EF4444)'"
                            style="padding:12px;color:#fff;border:none;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <span class="material-symbols-outlined" style="font-size:15px;">check</span>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// CoinGecko ID mapping
const COINGECKO_IDS = {
    BTC: 'bitcoin', ETH: 'ethereum', SOL: 'solana', BNB: 'binancecoin',
    XRP: 'ripple', ADA: 'cardano', DOGE: 'dogecoin', DOT: 'polkadot',
    AVAX: 'avalanche-2', LINK: 'chainlink', MATIC: 'matic-network',
    UNI: 'uniswap', ATOM: 'cosmos', LTC: 'litecoin', TRX: 'tron',
};

// CoinGecko logo cache
const LOGO_CACHE = {};

function marketApp() {
    return {
        search: '',
        modalOpen: false,
        tradeType: 'buy',
        tradeSymbol: 'BTC',
        tradeName: 'Bitcoin',
        tradePrice: 0.0,
        tradeQuantity: '',
        tradeDate: '{{ date('Y-m-d\TH:i') }}',
        tradeNotes: '',
        loading: false,
        countdown: 30,
        lastUpdate: null,
        coins: @php echo json_encode(array_values(array_map(function($sym, $info) {
            return ['symbol'=>$sym,'name'=>$info['name'],'price'=>$info['price'],'change'=>$info['change'],'market_cap'=>0,'logo'=>null];
        }, array_keys($livePrices), array_values($livePrices)))); @endphp,

        init() {
            this.fetchLogos();
            this.startCountdown();
        },

        filteredCoins() {
            const q = this.search.toLowerCase();
            return this.coins.filter(c =>
                c.symbol.toLowerCase().includes(q) ||
                c.name.toLowerCase().includes(q)
            );
        },

        formatMarketCap(val) {
            if (val >= 1e12) return (val / 1e12).toFixed(2) + 'T';
            if (val >= 1e9)  return (val / 1e9).toFixed(2) + 'B';
            if (val >= 1e6)  return (val / 1e6).toFixed(2) + 'M';
            return val.toLocaleString();
        },

        async fetchLogos() {
            const ids = this.coins
                .map(c => COINGECKO_IDS[c.symbol])
                .filter(Boolean)
                .join(',');
            if (!ids) return;
            try {
                const res = await fetch(`https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=${ids}&per_page=50&sparkline=false`, {
                    headers: { 'x-cg-demo-api-key': '{{ config('services.coingecko.key') }}' }
                });
                if (!res.ok) return;
                const data = await res.json();
                data.forEach(coin => {
                    const symbol = coin.symbol.toUpperCase();
                    const idx = this.coins.findIndex(c => c.symbol === symbol);
                    if (idx !== -1) {
                        this.coins[idx].logo       = coin.image;
                        this.coins[idx].market_cap = coin.market_cap ?? 0;
                    }
                });
            } catch(e) { /* silent */ }
        },

        async fetchPrices() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('market.prices') }}');
                if (!res.ok) throw new Error();
                const data = await res.json();

                data.forEach(updated => {
                    const idx = this.coins.findIndex(c => c.symbol === updated.symbol);
                    if (idx === -1) return;

                    const old = this.coins[idx].price;
                    this.coins[idx].price  = updated.price;
                    this.coins[idx].change = updated.change;

                    // Flash row
                    this.$nextTick(() => {
                        const row = document.getElementById('row-' + updated.symbol);
                        if (!row) return;
                        row.classList.remove('flash-up','flash-down');
                        void row.offsetWidth;
                        if (updated.price > old)      row.classList.add('flash-up');
                        else if (updated.price < old) row.classList.add('flash-down');
                    });
                });

                const now = new Date();
                this.lastUpdate = now.toLocaleTimeString('id-ID');
            } catch(e) { /* silent */ }
            finally { this.loading = false; }
        },

        startCountdown() {
            this.countdown = 30;
            setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    this.countdown = 30;
                    this.fetchPrices();
                }
            }, 1000);
        },

        openTrade(type, symbol, name, price) {
            this.tradeType     = type;
            this.tradeSymbol   = symbol;
            this.tradeName     = name;
            this.tradePrice    = price;
            this.tradeQuantity = '';
            this.tradeNotes    = '';
            this.modalOpen     = true;
        }
    }
}
</script>
@endsection
