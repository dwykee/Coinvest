@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white font-heading">Manajemen Transaksi</h1>
        <p class="text-on-surface-variant text-sm mt-1">Kelola dan catat setiap pembelian atau penjualan cryptocurrency Anda.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{
    prices: {{ json_encode(array_map(fn($item) => $item['price'], $livePrices)) }},
    selectedSymbol: 'BTC',
    quantity: '',
    price: '',
    type: 'buy',
    updatePrice() {
        if(this.prices[this.selectedSymbol]) {
            this.price = this.prices[this.selectedSymbol];
        }
    },
    init() {
        this.$watch('selectedSymbol', () => this.updatePrice());
        this.updatePrice();
    }
}">
    <!-- Add Transaction Column -->
    <div class="lg:col-span-1">
        <div class="glass-panel rounded-2xl p-6 shadow-lg sticky top-28">
            <h3 class="text-lg font-bold text-white font-heading mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span>
                Tambah Transaksi
            </h3>

            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                @csrf

                <!-- Type (Buy / Sell) -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Tipe Transaksi</label>
                    <div class="grid grid-cols-2 gap-2 bg-surface-dim p-1 rounded-xl border border-white/5">
                        <button type="button" @click="type = 'buy'" 
                                :class="type === 'buy' ? 'bg-success-green/20 text-success-green border border-success-green/30' : 'text-on-surface-variant border-transparent'"
                                class="py-2.5 rounded-lg text-xs font-bold transition-all border text-center">
                            BELI
                        </button>
                        <button type="button" @click="type = 'sell'" 
                                :class="type === 'sell' ? 'bg-error-red/20 text-error-red border border-error-red/30' : 'text-on-surface-variant border-transparent'"
                                class="py-2.5 rounded-lg text-xs font-bold transition-all border text-center">
                            JUAL
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="type">
                </div>

                <!-- Asset Symbol -->
                <div>
                    <label for="asset_symbol" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Pilih Koin</label>
                    <select id="asset_symbol" name="asset_symbol" x-model="selectedSymbol"
                            class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm">
                        @foreach($livePrices as $symbol => $info)
                            <option value="{{ $symbol }}">{{ $symbol }} - {{ $info['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Jumlah Aset</label>
                    <div class="relative">
                        <input id="quantity" type="number" step="any" min="0.00000001" name="quantity" x-model="quantity" required placeholder="Contoh: 0.125"
                               class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-primary" x-text="selectedSymbol"></span>
                    </div>
                </div>

                <!-- Price USD -->
                <div>
                    <label for="price_usd" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Harga Per Koin (USD)</label>
                    <div class="relative flex gap-2">
                        <div class="relative flex-grow">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant">$</span>
                            <input id="price_usd" type="number" step="any" min="0.00000001" name="price_usd" x-model="price" required placeholder="Contoh: 68500"
                                   class="w-full pl-8 pr-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                        </div>
                        <button type="button" @click="updatePrice()" 
                                class="px-3.5 bg-primary/10 hover:bg-primary/20 text-primary border border-primary/20 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1 shrink-0" 
                                title="Gunakan harga pasar sekarang">
                            <span class="material-symbols-outlined text-sm">sync</span>
                            Live Price
                        </button>
                    </div>
                </div>

                <!-- Total Value (Read-only Preview) -->
                <div class="bg-white/[0.02] border border-white/5 rounded-xl p-3 text-xs flex justify-between items-center">
                    <span class="text-on-surface-variant">Estimasi Total Nilai:</span>
                    <span class="text-white font-bold" x-text="price && quantity ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price * quantity) : '$0.00'"></span>
                </div>

                <!-- Transaction Date -->
                <div>
                    <label for="transaction_date" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Tanggal & Waktu Transaksi</label>
                    <input id="transaction_date" type="datetime-local" name="transaction_date" required value="{{ date('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm"/>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Platform pembelian, fee, dll."
                              class="w-full px-4 py-3 bg-surface-dim border border-white/10 rounded-xl text-white placeholder:text-white/20 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors text-sm resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary text-white font-medium py-3 rounded-xl shadow-[0_4px_14px_rgba(139,92,246,0.3)] hover:shadow-[0_6px_20px_rgba(139,92,246,0.4)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm mt-2 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>

    <!-- Transaction List Column -->
    <div class="lg:col-span-2">
        <div class="glass-panel rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold text-white font-heading mb-6">Daftar Riwayat Transaksi</h3>

            @if(count($transactions) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 text-xs text-on-surface-variant font-semibold">
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Tipe</th>
                                <th class="pb-3">Aset</th>
                                <th class="pb-3 text-right">Jumlah</th>
                                <th class="pb-3 text-right">Harga Beli/Jual</th>
                                <th class="pb-3 text-right">Total Transaksi</th>
                                <th class="pb-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-white/[0.01] transition-colors">
                                    <td class="py-3.5 text-on-surface-variant text-xs">
                                        {{ $tx->transaction_date->format('d M Y') }}
                                        <span class="block text-[10px] opacity-70">{{ $tx->transaction_date->format('H:i') }}</span>
                                    </td>
                                    <td class="py-3.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tx->type === 'buy' ? 'bg-success-green/10 text-success-green border border-success-green/20' : 'bg-error-red/10 text-error-red border border-error-red/20' }} uppercase">
                                            {{ $tx->type === 'buy' ? 'Beli' : 'Jual' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        <span class="font-bold text-white">{{ $tx->asset_symbol }}</span>
                                        <span class="text-xs text-on-surface-variant block">{{ $tx->asset_name }}</span>
                                    </td>
                                    <td class="py-3.5 text-right font-medium text-white">{{ number_format($tx->quantity, 6) }}</td>
                                    <td class="py-3.5 text-right text-on-surface-variant">${{ number_format($tx->price_usd, 2) }}</td>
                                    <td class="py-3.5 text-right text-white font-semibold">${{ number_format($tx->total_usd, 2) }}</td>
                                    <td class="py-3.5 text-center">
                                        <form method="POST" action="{{ route('transactions.destroy', $tx->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-error-red/10 text-error-red hover:bg-error-red hover:text-white transition-colors flex items-center justify-center" title="Hapus Transaksi">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @if($tx->notes)
                                    <tr class="bg-white/[0.005] border-t-0">
                                        <td colspan="7" class="px-4 py-1.5 text-xs text-on-surface-variant/80 border-b border-white/5">
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">notes</span>
                                                <span>{{ $tx->notes }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Laravel Pagination Link styling wrapper -->
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-16 text-on-surface-variant flex flex-col items-center">
                    <span class="material-symbols-outlined text-5xl mb-4 text-white/10">currency_exchange</span>
                    <p class="text-sm">Belum ada transaksi tercatat.</p>
                    <p class="text-xs text-on-surface-variant/70 mt-1">Gunakan panel di sebelah kiri untuk menambah transaksi pertama Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
