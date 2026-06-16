<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Wallet;
use App\Services\MoralisService;

class WalletController extends Controller
{
    protected MoralisService $moralis;

    public function __construct(MoralisService $moralis)
    {
        $this->moralis = $moralis;
    }

    // GET /wallets
    public function index()
    {
        $livePrices = PortfolioController::getLivePrices();

        $wallets = Wallet::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($wallet) use ($livePrices) {
                $balancesArray = $wallet->balances;

                if (is_string($balancesArray)) {
                    $balancesArray = json_decode($balancesArray, true);
                }

                $liveTotalValue = 0.0;
                $hasBalances = false;

                foreach (($balancesArray ?? []) as $bal) {
                    $symbol = strtoupper($bal['symbol'] ?? $bal['token_symbol'] ?? '');
                    $amount = (float) ($bal['amount'] ?? $bal['balance'] ?? 0);

                    if ($symbol === '' || $amount <= 0) {
                        continue;
                    }

                    $hasBalances = true;

                    $priceSymbol = match ($symbol) {
                        'WETH'   => 'ETH',
                        'WBTC'   => 'BTC',
                        'WMATIC' => 'MATIC',
                        default  => $symbol,
                    };

                    $livePrice = $livePrices[$priceSymbol]['price'] ?? 0.0;

                    if ($livePrice > 0) {
                        $liveTotalValue += $amount * $livePrice;
                    }
                }

                if ($hasBalances) {
                    // Override hanya untuk tampilan, tidak save ke database
                    $wallet->total_value = $liveTotalValue;
                }

                return $wallet;
            });

        return view('wallets.index', compact('wallets', 'livePrices'));
    }

    // GET /wallets/select
    public function select()
    {
        return view('wallets.select');
    }

    // POST /wallets
    public function store(Request $request)
    {
        $request->validate([
            'provider'   => 'required|string|max:100',
            'type'       => 'required|in:wallet,exchange',
            'address'    => 'nullable|string|max:255',
            'api_key'    => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'nickname'   => 'nullable|string|max:100',
            'chain'      => 'nullable|string|max:50',
        ]);

        $totalValue   = 0.0;
        $balances     = [];
        $syncedAt     = null;
        $syncError    = null;

        // ── Wallet address → fetch via Moralis ──
        if ($request->type === 'wallet' && $request->filled('address')) {
            $result = $this->moralis->fetchWalletData(
                trim($request->address),
                $request->chain ?: null
            );

            if ($result['success']) {
                $totalValue = $result['total_usd'];
                $balances   = $result['balances'] ?? [];
                $syncedAt   = now();
            } else {
                $syncError = $result['error'];
            }
        }

        // ── CEX exchange → simpan key, fetch nanti (placeholder) ──
        // TODO: tambah CexService per exchange (Binance, Indodax, dll)

        $wallet = Wallet::create([
            'user_id'        => Auth::id(),
            'name'           => $request->nickname ?: $request->provider,
            'provider'       => $request->provider,
            'type'           => $request->type,
            'address'        => $request->address,
            'balances'       => $balances,
            'api_key'        => $request->api_key,
            'api_secret'     => $request->api_secret
                                    ? encrypt($request->api_secret)
                                    : null,
            'total_value'    => $totalValue,
            'last_synced_at' => $syncedAt,
        ]);

        $message = 'Wallet "' . $wallet->name . '" berhasil ditambahkan!';
        if ($totalValue > 0) {
            $message .= ' Balance: $' . number_format($totalValue, 2);
        }
        if ($syncError) {
            $message .= ' (Gagal fetch balance: ' . $syncError . ')';
        }

        return redirect()->route('wallets.index')->with('success', $message);
    }

    // POST /wallets/{id}/sync
    public function sync($id)
    {
        $wallet = Wallet::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($wallet->type === 'wallet' && $wallet->address) {
            $result = $this->moralis->fetchWalletData($wallet->address);

            if ($result['success']) {
                $wallet->update([
                    'total_value'    => $result['total_usd'],
                    'balances'       => $result['balances'] ?? [],
                    'last_synced_at' => now(),
                ]);
                return redirect()->route('wallets.index')
                    ->with('success', '"' . $wallet->name . '" synced! Balance: $' . number_format($result['total_usd'], 2));
            }

            return redirect()->route('wallets.index')
                ->with('error', 'Sync gagal: ' . $result['error']);
        }

        return redirect()->route('wallets.index')
            ->with('error', 'Sync belum tersedia untuk tipe ini.');
    }

    // GET /wallets/{id}/edit
    public function edit($id)
    {
        $wallet = Wallet::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('wallets.edit', compact('wallet'));
    }

    // PUT/PATCH /wallets/{id}
    public function update(Request $request, $id)
    {
        $wallet = Wallet::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($wallet->profile_picture && Storage::disk('public')->exists($wallet->profile_picture)) {
                Storage::disk('public')->delete($wallet->profile_picture);
            }

            // Store new picture
            $path = $request->file('profile_picture')->store('wallets', 'public');
            $data['profile_picture'] = $path;
        }

        $wallet->update($data);

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet "' . $wallet->name . '" berhasil diperbarui!');
    }

    // DELETE /wallets/{id}/photo
    public function deletePhoto($id)
    {
        $wallet = Wallet::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($wallet->profile_picture && Storage::disk('public')->exists($wallet->profile_picture)) {
            Storage::disk('public')->delete($wallet->profile_picture);
            $wallet->update(['profile_picture' => null]);

            return redirect()->route('wallets.edit', $id)
                ->with('success', 'Profile picture berhasil dihapus!');
        }

        return redirect()->route('wallets.edit', $id)
            ->with('error', 'Tidak ada profile picture untuk dihapus.');
    }

    // DELETE /wallets/{id}
    public function destroy($id)
    {
        $wallet = Wallet::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wallet->delete();

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet "' . $wallet->name . '" berhasil dihapus.');
    }
}