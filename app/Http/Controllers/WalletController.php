<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $wallets = Wallet::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wallets.index', compact('wallets'));
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