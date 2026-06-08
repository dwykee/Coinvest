@extends('layouts.app')

@section('title', 'Pasar Live')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white font-heading">Pasar Crypto Real-Time</h1>
        <p class="text-on-surface-variant text-sm mt-1">Pantau pergerakan harga aset crypto utama secara langsung.</p>
    </div>
</div>

<div x-data="{
    search: '',
    modalOpen: false,
    tradeType: 'buy',
    tradeSymbol: 'BTC',
    tradeName: 'Bitcoin',
    tradePrice: 0.0,
    tradeQuantity: '',
    tradeDate: '{{ date('Y-m-d\TH:i') }}',
    tradeNotes: '',
    coins: [
        @foreach($livePrices as $symbol => $info)
        {
            symbol: '{{ $symbol }}',
            name: '{{ $info['name'] }}',
            price: {{ $info['price'] }},
            change: {{ $info['change'] }}
        },
        @endforeach
    ],
    filteredCoins() {
        return this.coins.filter(c => 
            c.symbol.toLowerCase().includes(this.search.toLowerCase()) || 
            c.name.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    openTrade(type, symbol, name, price) {
        this.tradeType = type;
        this.tradeSymbol = symbol;
        this.tradeName = name;
        this.tradePrice = price;
        this.tradeQuantity = '';
        this.tradeNotes = '';
        this.modalOpen = true;
    }
}">
    <!-- Search Bar & Statistics -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <div class="relative max-w-md w-full">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input type="text" x-model="search" placeholder="Cari nama koin atau simbol..." 
                   class="w-full pl-11 pr-4 py-2.5 bg-surface-card border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
        </div>
        <div class="flex items-center gap-2 text-xs text-on-surface-variant bg-white/[0.02] border border-white/5 px-4 py-2 rounded-xl">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-success-green animate-pulse"></span>
            Terkoneksi ke live Binance API ticker.
        </div>
    </div>

    <!-- Ticker Table -->
    <div class="glass-panel rounded-2xl p-6 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-xs text-on-surface-variant font-semibold">
                        <th class="pb-3 w-12 text-center">Rank</th>
                        <th class="pb-3">Koin</th>
                        <th class="pb-3 text-right">Harga (USD)</th>
                        <th class="pb-3 text-right">Perubahan 24h</th>
                        <th class="pb-3 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <template x-for="(coin, index) in filteredCoins()" :key="coin.symbol">
                        <tr class="hover:bg-white/[0.01] transition-colors">
                            <td class="py-4 text-center text-on-surface-variant text-xs font-semibold" x-text="index + 1"></td>
                            <td class="py-4 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase" x-text="coin.symbol.substring(0,2)"></span>
                                <div>
                                    <span class="font-bold text-white block" x-text="coin.symbol"></span>
                                    <span class="text-xs text-on-surface-variant" x-text="coin.name"></span>
                                </div>
                            </td>
                            <td class="py-4 text-right font-bold text-white" x-text="'$' + coin.price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 })"></td>
                            <td class="py-4 text-right">
                                <span :class="coin.change >= 0 ? 'text-success-green bg-success-green/10 border-success-green/20' : 'text-error-red bg-error-red/10 border-error-red/20'" 
                                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border">
                                    <span class="material-symbols-outlined text-xs" x-text="coin.change >= 0 ? 'arrow_upward' : 'arrow_downward'"></span>
                                    <span x-text="coin.change.toFixed(2) + '%'"></span>
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex justify-center gap-2">
                                    <button @click="openTrade('buy', coin.symbol, coin.name, coin.price)" 
                                            class="px-3.5 py-1.5 bg-success-green/10 hover:bg-success-green hover:text-white text-success-green rounded-lg text-xs font-bold border border-success-green/20 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">add_shopping_cart</span> Beli
                                    </button>
                                    <button @click="openTrade('sell', coin.symbol, coin.name, coin.price)" 
                                            class="px-3.5 py-1.5 bg-error-red/10 hover:bg-error-red hover:text-white text-error-red rounded-lg text-xs font-bold border border-error-red/20 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">sell</span> Jual
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredCoins().length === 0">
                        <td colspan="5" class="py-12 text-center text-on-surface-variant text-sm">
                            Tidak ada koin yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Trade Modal -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm" style="display: none;" x-transition>
        <div @click.away="modalOpen = false" class="glass-panel w-full max-w-md rounded-2xl p-6 shadow-2xl relative">
            <button @click="modalOpen = false" class="absolute top-4 right-4 text-on-surface-variant hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>

            <!-- Modal Title -->
            <h3 class="text-lg font-bold text-white font-heading mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined" :class="tradeType === 'buy' ? 'text-success-green' : 'text-error-red'" x-text="tradeType === 'buy' ? 'add_shopping_cart' : 'sell'"></span>
                Quick <span x-text="tradeType === 'buy' ? 'Beli' : 'Jual'"></span> <span x-text="tradeSymbol"></span>
            </h3>

            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="type" :value="tradeType">
                <input type="hidden" name="asset_symbol" :value="tradeSymbol">

                <!-- Asset Symbol & Name Info -->
                <div class="bg-white/[0.02] border border-white/5 rounded-xl p-4 flex justify-between items-center text-sm">
                    <div>
                        <span class="text-white font-bold block" x-text="tradeName"></span>
                        <span class="text-xs text-on-surface-variant" x-text="'Harga pasar: $' + tradePrice.toLocaleString('en-US')"></span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase" :class="tradeType === 'buy' ? 'bg-success-green/10 text-success-green border border-success-green/20' : 'bg-error-red/10 text-error-red border border-error-red/20'" x-text="tradeType === 'buy' ? 'BELI' : 'JUAL'"></span>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="modal_quantity" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Jumlah Koin</label>
                    <div class="relative">
                        <input id="modal_quantity" type="number" step="any" min="0.00000001" name="quantity" x-model="tradeQuantity" required placeholder="0.000"
                               class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-primary" x-text="tradeSymbol"></span>
                    </div>
                </div>

                <!-- Price USD (Prefilled and editable) -->
                <div>
                    <label for="modal_price_usd" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Harga Transaksi (USD/Koin)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant">$</span>
                        <input id="modal_price_usd" type="number" step="any" min="0.00000001" name="price_usd" x-model="tradePrice" required 
                               class="w-full pl-8 pr-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                    </div>
                </div>

                <!-- Total Value Preview -->
                <div class="bg-white/[0.02] border border-white/5 rounded-xl p-3 text-xs flex justify-between items-center">
                    <span class="text-on-surface-variant">Estimasi Total Transaksi:</span>
                    <span class="text-white font-bold" x-text="tradePrice && tradeQuantity ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(tradePrice * tradeQuantity) : '$0.00'"></span>
                </div>

                <!-- Transaction Date -->
                <div>
                    <label for="modal_date" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Waktu Transaksi</label>
                    <input id="modal_date" type="datetime-local" name="transaction_date" required x-model="tradeDate"
                           class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                </div>

                <!-- Notes -->
                <div>
                    <label for="modal_notes" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Catatan</label>
                    <textarea id="modal_notes" name="notes" rows="2" placeholder="Contoh: Beli lewat Quick Trade Pasar" x-model="tradeNotes"
                              class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm resize-none"></textarea>
                </div>

                <!-- Submit -->
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button type="button" @click="modalOpen = false" class="py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl border border-white/5 text-sm font-semibold transition-colors text-center">
                        Batal
                    </button>
                    <button type="submit" :class="tradeType === 'buy' ? 'btn-primary' : 'bg-error-red hover:bg-red-600'" 
                            class="py-3 text-white rounded-xl text-sm font-semibold transition-colors text-center flex items-center justify-center gap-1 shadow-lg">
                        <span class="material-symbols-outlined text-sm">check</span>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
