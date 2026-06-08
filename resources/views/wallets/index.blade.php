@extends('layouts.app')

@section('title', 'Wallets')

@section('content')
<style>
    /* ── Wallet page styles ── */
    .wallet-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .wallet-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .wallet-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(139,92,246,0.12);
        border: 1px solid rgba(139,92,246,0.25);
        color: #8b5cf6;
        font-size: 11px;
        font-weight: 700;
    }
    .wallet-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Filter & search bar */
    .wallet-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .search-field {
        position: relative;
        width: 280px;
    }
    .search-field .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #7a7485;
        pointer-events: none;
    }
    .search-field input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px;
        padding: 9px 14px 9px 38px;
        color: #f5f3f7;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-field input::placeholder { color: #7a7485; }
    .search-field input:focus {
        border-color: rgba(139,92,246,0.5);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
    }
    .sort-dropdown {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px;
        padding: 9px 14px;
        color: #a19baf;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .sort-dropdown:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.12);
    }

    /* ── Wallet card list ── */
    .wallet-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .wallet-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(18, 17, 24, 0.6);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 16px 20px;
        transition: background 0.18s, border-color 0.18s;
        cursor: pointer;
        position: relative;
        gap: 12px;
    }
    .wallet-card:hover {
        background: rgba(139,92,246,0.04);
        border-color: rgba(139,92,246,0.18);
    }
    .wallet-card-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        flex: 1;
    }
    .wallet-logo {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .wallet-logo img {
        width: 26px;
        height: 26px;
        object-fit: contain;
    }
    .wallet-logo-fallback {
        font-size: 13px;
        font-weight: 800;
        font-family: 'Sora', sans-serif;
        color: #8b5cf6;
    }
    .wallet-info {
        min-width: 0;
    }
    .wallet-name {
        font-size: 14px;
        font-weight: 700;
        color: #f5f3f7;
        font-family: 'Sora', sans-serif;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .wallet-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-top: 3px;
        padding: 2px 7px;
        border-radius: 5px;
    }
    .badge-wallet {
        background: rgba(16,185,129,0.1);
        color: #10B981;
        border: 1px solid rgba(16,185,129,0.2);
    }
    .badge-exchange {
        background: rgba(59,130,246,0.1);
        color: #60a5fa;
        border: 1px solid rgba(59,130,246,0.2);
    }
    .wallet-card-center {
        flex: 1;
        text-align: center;
        padding: 0 16px;
    }
    .wallet-tx-count {
        font-size: 13px;
        color: #8b5cf6;
        font-weight: 600;
        transition: color 0.2s;
    }
    .wallet-tx-count:hover { text-decoration: underline; color: #a78bfa; }
    .wallet-card-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }
    .wallet-value {
        text-align: right;
    }
    .wallet-value-amount {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        font-size: 15px;
        font-weight: 700;
        color: #f5f3f7;
        font-family: 'Sora', sans-serif;
    }
    .wallet-value-label {
        font-size: 11px;
        color: #7a7485;
        margin-top: 2px;
    }
    .wallet-menu-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #7a7485;
        transition: background 0.15s, color 0.15s;
        opacity: 0;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    .wallet-card:hover .wallet-menu-btn {
        opacity: 1;
    }
    .wallet-menu-btn:hover {
        background: rgba(255,255,255,0.06);
        color: #f5f3f7;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 72px 24px;
        background: rgba(18,17,24,0.4);
        border: 1px dashed rgba(255,255,255,0.08);
        border-radius: 16px;
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: rgba(139,92,246,0.08);
        border: 1px solid rgba(139,92,246,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    /* ── Sync status dot ── */
    .sync-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 6px rgba(16,185,129,0.6);
        flex-shrink: 0;
    }
    .sync-dot.stale { background: #f59e0b; box-shadow: 0 0 6px rgba(245,158,11,0.6); }

    /* Responsive */
    @media (max-width: 640px) {
        .wallet-card-center { display: none; }
        .search-field { width: 100%; }
        .wallet-toolbar { flex-direction: column; align-items: stretch; }
    }
</style>

<!-- Page Header -->
<div class="wallet-header">
    <div class="wallet-title-group">
        <h1 class="font-heading" style="font-size:26px;font-weight:800;color:#f5f3f7;letter-spacing:-0.5px;">Wallets</h1>
        <span class="wallet-count-badge">{{ count($wallets ?? []) }}</span>
    </div>
    <div class="wallet-actions">
        <a href="{{ route('wallets.select') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all"
           style="background: linear-gradient(180deg, #9f7aea 0%, #8b5cf6 100%); box-shadow: 0 2px 12px rgba(139,92,246,0.35);">
            <span class="material-symbols-outlined" style="font-size:17px;">add</span>
            Add wallet / exchange
        </a>
        <button class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-on-surface-variant border border-white/8 bg-white/3 hover:bg-white/5 transition-all">
            <span class="material-symbols-outlined" style="font-size:17px;">sync</span>
            Sync all
        </button>
        <button class="flex items-center justify-center w-10 h-10 rounded-xl text-on-surface-variant border border-white/8 bg-white/3 hover:bg-white/5 transition-all">
            <span class="material-symbols-outlined" style="font-size:17px;">more_horiz</span>
        </button>
    </div>
</div>

<!-- Toolbar -->
<div class="wallet-toolbar">
    <div class="search-field">
        <span class="material-symbols-outlined search-icon">search</span>
        <input type="text" placeholder="Find wallet..." id="walletSearch" oninput="filterWallets(this.value)">
    </div>
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="sort-dropdown">
            <span class="material-symbols-outlined" style="font-size:15px;opacity:0.6;">swap_vert</span>
            Sort by Date Added
            <span class="material-symbols-outlined" style="font-size:15px;" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
        </button>
        <div x-show="open" @click.away="open = false" x-transition
             class="absolute right-0 mt-2 w-44 rounded-xl border border-white/10 shadow-2xl p-1 z-30"
             style="background:#121114;display:none;">
            <button class="w-full text-left px-3 py-2 text-sm text-on-surface-variant hover:bg-white/5 hover:text-white rounded-lg transition-colors">Date Added</button>
            <button class="w-full text-left px-3 py-2 text-sm text-on-surface-variant hover:bg-white/5 hover:text-white rounded-lg transition-colors">Name A–Z</button>
            <button class="w-full text-left px-3 py-2 text-sm text-on-surface-variant hover:bg-white/5 hover:text-white rounded-lg transition-colors">Total Value</button>
            <button class="w-full text-left px-3 py-2 text-sm text-on-surface-variant hover:bg-white/5 hover:text-white rounded-lg transition-colors">Transactions</button>
        </div>
    </div>
</div>

<!-- Wallet List -->
<div class="wallet-list" id="walletList">
    @forelse($wallets ?? [] as $wallet)
    <div class="wallet-card" data-name="{{ strtolower($wallet->name) }}">
        <div class="wallet-card-left">
            <div class="wallet-logo">
                @if(!empty($wallet->logo_url))
                    <img src="{{ $wallet->logo_url }}" alt="{{ $wallet->name }}">
                @else
                    <span class="wallet-logo-fallback">{{ strtoupper(substr($wallet->name, 0, 2)) }}</span>
                @endif
            </div>
            <div class="wallet-info">
                <div class="wallet-name">{{ $wallet->name }}</div>
                <span class="wallet-type-badge {{ $wallet->type === 'wallet' ? 'badge-wallet' : 'badge-exchange' }}">
                    <span class="material-symbols-outlined" style="font-size:10px;">{{ $wallet->type === 'wallet' ? 'account_balance_wallet' : 'swap_horiz' }}</span>
                    {{ $wallet->type === 'wallet' ? 'Wallet' : 'Exchange' }}
                </span>
            </div>
        </div>

        <div class="wallet-card-center">
            <a href="#" class="wallet-tx-count">{{ $wallet->transactions_count ?? 0 }} transactions</a>
        </div>

        <div class="wallet-card-right">
            {{-- Sync dot: green = synced dalam 24 jam, kuning = stale/belum pernah sync --}}
            <div class="sync-dot {{ $wallet->last_synced_at && now()->diffInHours($wallet->last_synced_at) < 24 ? '' : 'stale' }}"
                 title="{{ $wallet->last_synced_at ? 'Last sync: ' . $wallet->last_synced_at->diffForHumans() : 'Belum pernah sync' }}">
            </div>

            <div class="wallet-value">
                <div class="wallet-value-amount">
                    @php $val = (float) ($wallet->total_value ?? 0); @endphp
                    <span style="{{ $val > 0 ? 'color:#f5f3f7' : 'color:#4d4757' }}">
                        ${{ $val > 0 ? number_format($val, 2) : '—' }}
                    </span>
                </div>
                <div class="wallet-value-label">
                    {{ $wallet->last_synced_at ? $wallet->last_synced_at->diffForHumans() : 'Belum di-sync' }}
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click.stop="open = !open" class="wallet-menu-btn">
                    <span class="material-symbols-outlined" style="font-size:18px;">more_vert</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute right-0 mt-1 w-44 rounded-xl border border-white/10 shadow-2xl p-1 z-30"
                     style="background:#121114;display:none;">

                    {{-- Sync --}}
                    <form method="POST" action="{{ route('wallets.sync', $wallet->id) }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-on-surface-variant hover:bg-white/5 hover:text-white rounded-lg transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size:14px;">sync</span> Sync
                        </button>
                    </form>

                    {{-- Hapus --}}
                    <form method="POST" action="{{ route('wallets.destroy', $wallet->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Hapus wallet {{ addslashes($wallet->name) }}?')"
                                class="w-full text-left px-3 py-2 text-sm text-error hover:bg-error/10 rounded-lg transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size:14px;">delete</span> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <span class="material-symbols-outlined" style="font-size:28px;color:#8b5cf6;">account_balance_wallet</span>
        </div>
        <h3 class="font-heading" style="font-size:18px;font-weight:700;color:#f5f3f7;margin-bottom:8px;">Belum ada wallet</h3>
        <p style="font-size:13px;color:#7a7485;margin-bottom:24px;max-width:320px;margin-left:auto;margin-right:auto;">
            Tambahkan wallet atau exchange pertamamu untuk mulai melacak portofolio crypto kamu.
        </p>
        <a href="{{ route('wallets.select') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
           style="background: linear-gradient(180deg, #9f7aea 0%, #8b5cf6 100%); box-shadow: 0 2px 12px rgba(139,92,246,0.3);">
            <span class="material-symbols-outlined" style="font-size:17px;">add</span>
            Tambah Wallet Pertama
        </a>
    </div>
    @endforelse
</div>

<script>
function filterWallets(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('#walletList .wallet-card').forEach(card => {
        const name = card.dataset.name || '';
        card.style.display = name.includes(q) ? '' : 'none';
    });
}
</script>
@endsection