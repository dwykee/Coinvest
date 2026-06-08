@extends('layouts.app')

@section('title', 'Add Wallet')

@section('content')
<style>
    .add-wallet-wrap {
        max-width: 860px;
        margin: 0 auto;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #7a7485;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .back-link:hover { color: #f5f3f7; }

    .add-search-wrap {
        position: relative;
        margin-bottom: 32px;
    }
    .add-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #7a7485;
        pointer-events: none;
    }
    .add-search-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        padding: 14px 20px 14px 50px;
        color: #f5f3f7;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .add-search-input::placeholder { color: #4d4757; }
    .add-search-input:focus {
        border-color: rgba(139,92,246,0.5);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
        background: rgba(139,92,246,0.02);
    }

    .section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #4d4757;
        margin-bottom: 14px;
    }

    .wallet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-bottom: 36px;
    }
    .wallet-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 22px 12px 18px;
        background: rgba(18,17,24,0.5);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        cursor: pointer;
        transition: background 0.18s, border-color 0.18s, transform 0.15s, box-shadow 0.18s;
        position: relative;
        text-decoration: none;
        color: inherit;
        user-select: none;
    }
    .wallet-option:hover {
        background: rgba(139,92,246,0.06);
        border-color: rgba(139,92,246,0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    .wallet-option.selected {
        background: rgba(139,92,246,0.1);
        border-color: rgba(139,92,246,0.55);
        box-shadow: 0 0 0 1px rgba(139,92,246,0.3);
    }
    .check-mark {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #8b5cf6;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .wallet-option.selected .check-mark { display: flex; }

    .opt-logo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        background: transparent;
    }
    .opt-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }
    
    .opt-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        font-family: 'Sora', sans-serif;
        color: #fff;
        border-radius: 12px;
        letter-spacing: -0.5px;
    }

    .opt-name {
        font-size: 13px;
        font-weight: 700;
        color: #f5f3f7;
        font-family: 'Sora', sans-serif;
        text-align: center;
        line-height: 1.3;
    }
    .opt-new-badge {
        position: absolute;
        top: 9px;
        left: 9px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff;
        padding: 2px 6px;
        border-radius: 5px;
        z-index: 10;
    }

    .add-form-panel {
        background: rgba(18,17,24,0.7);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        padding: 28px 32px;
        margin-top: 4px;
    }
    .add-form-panel h3 {
        font-family: 'Sora', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #f5f3f7;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-field { margin-bottom: 16px; }
    .form-field label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #7a7485;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .form-field input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 11px 14px;
        color: #f5f3f7;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .form-field input::placeholder { color: #4d4757; }
    .form-field input:focus {
        border-color: rgba(139,92,246,0.5);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
    }
    .form-hint {
        font-size: 11px;
        color: #4d4757;
        margin-top: 5px;
        line-height: 1.6;
    }

    .no-results {
        text-align: center;
        padding: 48px 24px;
        color: #4d4757;
        font-size: 13px;
        display: none;
    }

    @media (max-width: 640px) {
        .wallet-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 400px) {
        .wallet-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="add-wallet-wrap">
    <a href="{{ route('wallets.index') }}" class="back-link">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to Wallets
    </a>

    <h1 class="font-heading" style="font-size:26px;font-weight:800;color:#f5f3f7;letter-spacing:-0.5px;margin-bottom:6px;">
        Add wallet or exchange
    </h1>
    <p style="font-size:13px;color:#7a7485;margin-bottom:28px;">
        Import your wallet address or connect a CEX with your API key.
    </p>

    <div class="add-search-wrap">
        <span class="material-symbols-outlined add-search-icon">search</span>
        <input type="text" class="add-search-input" id="optionSearch"
               placeholder="Search exchange or wallet..."
               oninput="filterOptions(this.value)">
    </div>

    @php
    $popular = [
        ['name' => 'MetaMask',     'type' => 'wallet',   'bg' => '#E2761B', 'icon' => 'MM',
         'logo' => 'https://assets.coingecko.com/coins/images/28373/large/metamask.png'],
        ['name' => 'Trust Wallet', 'type' => 'wallet',   'bg' => '#3375BB', 'icon' => 'TW',
         'logo' => 'https://assets.coingecko.com/coins/images/4758/large/trust_wallet.png'],
        ['name' => 'Binance',      'type' => 'exchange', 'bg' => '#1a1a2e', 'icon' => 'BN',
         'logo' => 'https://assets.coingecko.com/markets/images/52/large/binance.png'],
        ['name' => 'Indodax',      'type' => 'exchange', 'bg' => '#1A5FFF', 'icon' => 'IDX', 
         'logo' => 'https://assets.coingecko.com/markets/images/63/large/indodax.png'],
        ['name' => 'Coinbase',     'type' => 'exchange', 'bg' => '#0052FF', 'icon' => 'CB',
         'logo' => 'https://assets.coingecko.com/markets/images/23/large/Coinbase_Coin_Primary.png'],
        ['name' => 'KuCoin',       'type' => 'exchange', 'bg' => '#1BAA8B', 'icon' => 'KC',  
         'logo' => 'https://assets.coingecko.com/markets/images/61/large/kucoin.png'],
        ['name' => 'Kraken',       'type' => 'exchange', 'bg' => '#5741D9', 'icon' => 'KR',  
         'logo' => 'https://assets.coingecko.com/markets/images/29/large/kraken.png'],
        ['name' => 'Tokocrypto',   'type' => 'exchange', 'bg' => '#0A1628', 'icon' => 'TC',  
         'logo' => 'https://assets.coingecko.com/coins/images/11079/large/Tokocrypto_Token_Logo.png'],
    ];
    $all = [
        ['name' => 'Bybit',          'type' => 'exchange', 'bg' => '#F7A600', 'icon' => 'BY', 
         'logo' => 'https://assets.coingecko.com/markets/images/698/large/bybit_spot.png'],
        ['name' => 'OKX',            'type' => 'exchange', 'bg' => '#222',    'icon' => 'OK', 
         'logo' => 'https://assets.coingecko.com/markets/images/424/large/okx.png'],
        ['name' => 'Gate.io',        'type' => 'exchange', 'bg' => '#2354E6', 'icon' => 'GT', 
         'logo' => 'https://assets.coingecko.com/markets/images/60/large/gate_io.png'],
        ['name' => 'Bitget',         'type' => 'exchange', 'bg' => '#00C6CF', 'icon' => 'BG', 
         'logo' => 'https://assets.coingecko.com/markets/images/540/large/bitget_2x.png'],
        ['name' => 'Gemini',         'type' => 'exchange', 'bg' => '#05B8B8', 'icon' => 'GM', 
         'logo' => 'https://assets.coingecko.com/markets/images/50/large/gemini.png'],
        ['name' => 'Phantom',        'type' => 'wallet',   'bg' => '#4E44CE', 'icon' => 'PH', 
         'logo' => 'https://assets.coingecko.com/coins/images/33054/large/phantom.png'],
        ['name' => 'Ethereum',       'type' => 'wallet',   'bg' => '#627EEA', 'icon' => 'ETH',
         'logo' => 'https://assets.coingecko.com/coins/images/279/large/ethereum.png'],
        ['name' => 'Bitcoin',        'type' => 'wallet',   'bg' => '#F7931A', 'icon' => 'BTC',
         'logo' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png'],
        ['name' => 'Solana',         'type' => 'wallet',   'bg' => '#9945FF', 'icon' => 'SOL',
         'logo' => 'https://assets.coingecko.com/coins/images/4128/large/solana.png'],
        ['name' => 'Polygon',        'type' => 'wallet',   'bg' => '#8247E5', 'icon' => 'POL',
         'logo' => 'https://assets.coingecko.com/coins/images/4713/large/polygon.png'],
        ['name' => 'BNB Chain',      'type' => 'wallet',   'bg' => '#F0B90B', 'icon' => 'BNB',
         'logo' => 'https://assets.coingecko.com/coins/images/825/large/bnb-icon2_2x.png'],
        ['name' => 'Pindodax',       'type' => 'exchange', 'bg' => '#0073FF', 'icon' => 'PD', 
         'logo' => 'https://assets.coingecko.com/markets/images/63/large/indodax.png', 'new' => true],
    ];
    @endphp

    <div id="popularSection">
        <div class="section-label">Popular</div>
        <div class="wallet-grid" id="popularGrid">
            @foreach($popular as $item)
            <div class="wallet-option"
                 data-name="{{ strtolower($item['name']) }}"
                 onclick="selectWallet('{{ $item['name'] }}', '{{ $item['type'] }}', this)">
                <div class="check-mark">
                    <span class="material-symbols-outlined" style="font-size:11px;color:white;">check</span>
                </div>
                <div class="opt-logo">
                    @if(!empty($item['logo']))
                        <img src="https://images.weserv.nl/?url={{ urlencode($item['logo']) }}" alt="{{ $item['name'] }}" referrerpolicy="no-referrer"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="opt-fallback" style="background:{{ $item['bg'] }}; display:none;">
                            {{ $item['icon'] ?? strtoupper(substr($item['name'],0,2)) }}
                        </div>
                    @else
                        <div class="opt-fallback" style="background:{{ $item['bg'] }};">
                            {{ $item['icon'] ?? strtoupper(substr($item['name'],0,2)) }}
                        </div>
                    @endif
                </div>
                <div class="opt-name">{{ $item['name'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="allSection">
        <div class="section-label">All</div>
        <div class="wallet-grid" id="allGrid">
            @foreach($all as $item)
            <div class="wallet-option"
                 data-name="{{ strtolower($item['name']) }}"
                 onclick="selectWallet('{{ $item['name'] }}', '{{ $item['type'] }}', this)">
                @if(!empty($item['new']))
                    <span class="opt-new-badge">New</span>
                @endif
                <div class="check-mark">
                    <span class="material-symbols-outlined" style="font-size:11px;color:white;">check</span>
                </div>
                <div class="opt-logo">
                    @if(!empty($item['logo']))
                        <img src="https://images.weserv.nl/?url={{ urlencode($item['logo']) }}" alt="{{ $item['name'] }}" referrerpolicy="no-referrer"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="opt-fallback" style="background:{{ $item['bg'] }}; display:none;">
                            {{ $item['icon'] ?? strtoupper(substr($item['name'],0,2)) }}
                        </div>
                    @else
                        <div class="opt-fallback" style="background:{{ $item['bg'] }};">
                            {{ $item['icon'] ?? strtoupper(substr($item['name'],0,2)) }}
                        </div>
                    @endif
                </div>
                <div class="opt-name">{{ $item['name'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="no-results" id="noResults">
        <span class="material-symbols-outlined" style="font-size:36px;opacity:0.3;display:block;margin-bottom:8px;">search_off</span>
        <p>Tidak ada hasil untuk pencarian ini.</p>
    </div>

    <div class="add-form-panel" id="addFormPanel" style="display:none;">
        <h3>
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="font-size:16px;color:#8b5cf6;">link</span>
            </span>
            <span id="formTitle">Connect Wallet</span>
        </h3>

        <form method="POST" action="{{ route('wallets.store') }}">
            @csrf
            <input type="hidden" name="provider" id="formProvider">
            <input type="hidden" name="type" id="formType">

            <div class="form-field" id="addressField">
                <label>Wallet Address</label>
                <input type="text" name="address" placeholder="0x... atau alamat publik wallet kamu">
                <div class="form-hint">Masukkan alamat publik wallet. Kami hanya membaca data, tidak punya akses ke dana kamu.</div>
            </div>

            <div id="apiFields" style="display:none;">
                <div class="form-field">
                    <label>API Key</label>
                    <input type="text" name="api_key" placeholder="API Key dari exchange kamu">
                </div>
                <div class="form-field">
                    <label>API Secret</label>
                    <input type="password" name="api_secret" placeholder="API Secret">
                    <div class="form-hint">Gunakan API key dengan permission <strong style="color:#f5f3f7;">Read Only</strong>. Jangan berikan akses withdraw.</div>
                </div>
            </div>

            <div class="form-field">
                <label>Nickname <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#4d4757;">(opsional)</span></label>
                <input type="text" name="nickname" placeholder="e.g. Hot wallet, Main exchange">
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 14px;background:rgba(16,185,129,0.05);border:1px solid rgba(16,185,129,0.12);border-radius:10px;margin-bottom:22px;">
                <span class="material-symbols-outlined" style="font-size:16px;color:#10B981;flex-shrink:0;margin-top:1px;">verified_user</span>
                <p style="font-size:12px;color:#6b9a7a;line-height:1.6;margin:0;">
                    Coinvest menggunakan koneksi <strong style="color:#10B981;">read-only</strong>. Kami tidak pernah menyimpan private key atau memiliki akses ke dana kamu.
                </p>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
                        style="background:linear-gradient(180deg,#9f7aea 0%,#8b5cf6 100%);box-shadow:0 2px 12px rgba(139,92,246,0.3);">
                    <span class="material-symbols-outlined" style="font-size:16px;">add_link</span>
                    Connect
                </button>
                <button type="button" onclick="closeForm()"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all"
                        style="color:#a19baf;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.03);">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterOptions(query) {
    const q = query.toLowerCase().trim();
    let anyVisible = false;
    document.querySelectorAll('.wallet-option').forEach(el => {
        const match = (el.dataset.name || '').includes(q);
        el.style.display = match ? '' : 'none';
        if (match) anyVisible = true;
    });
    updateSectionVisibility();
    document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';
}

function updateSectionVisibility() {
    ['popularSection', 'allSection'].forEach(id => {
        const section = document.getElementById(id);
        if (!section) return;
        const any = Array.from(section.querySelectorAll('.wallet-option')).some(el => el.style.display !== 'none');
        section.style.display = any ? '' : 'none';
    });
}

function selectWallet(name, type, el) {
    document.querySelectorAll('.wallet-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');

    const panel = document.getElementById('addFormPanel');
    panel.style.display = 'block';
    setTimeout(() => panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 50);

    document.getElementById('formTitle').textContent = 'Connect ' + name;
    document.getElementById('formProvider').value = name;
    document.getElementById('formType').value = type;

    const isExchange = type === 'exchange';
    document.getElementById('addressField').style.display = isExchange ? 'none' : '';
    document.getElementById('apiFields').style.display  = isExchange ? '' : 'none';
}

function closeForm() {
    document.getElementById('addFormPanel').style.display = 'none';
    document.querySelectorAll('.wallet-option').forEach(o => o.classList.remove('selected'));
}
</script>
@endsection