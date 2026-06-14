<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PortfolioController extends Controller
{
    /**
     * Get real-time crypto prices via Binance API with fallback.
     */
    public static function getLivePrices()
    {
        $defaultPrices = [
            'BTC'  => ['name' => 'Bitcoin',   'price' => 68500.00, 'change' => 2.45],
            'ETH'  => ['name' => 'Ethereum',  'price' => 3850.00,  'change' => -1.20],
            'SOL'  => ['name' => 'Solana',    'price' => 155.20,   'change' => 5.67],
            'BNB'  => ['name' => 'BNB',       'price' => 580.40,   'change' => 0.85],
            'XRP'  => ['name' => 'Ripple',    'price' => 0.52,     'change' => -0.45],
            'ADA'  => ['name' => 'Cardano',   'price' => 0.46,     'change' => 1.15],
            'DOGE' => ['name' => 'Dogecoin',  'price' => 0.142,    'change' => -3.20],
            'DOT'  => ['name' => 'Polkadot',  'price' => 6.25,     'change' => 0.50],
            'AVAX' => ['name' => 'Avalanche', 'price' => 32.80,    'change' => 4.10],
            'LINK' => ['name' => 'Chainlink', 'price' => 16.40,    'change' => 2.80],
        ];

        // Cache 30 detik biar ga spam Binance tiap request
        return Cache::remember('live_prices', 30, function () use ($defaultPrices) {
            try {
                $response = Http::timeout(4)->get('https://api.binance.com/api/v3/ticker/24hr');
                if ($response->successful()) {
                    foreach ($response->json() as $ticker) {
                        $symbol = $ticker['symbol'];
                        if (str_ends_with($symbol, 'USDT')) {
                            $base = substr($symbol, 0, -4);
                            if (array_key_exists($base, $defaultPrices)) {
                                $defaultPrices[$base]['price']  = (float) $ticker['lastPrice'];
                                $defaultPrices[$base]['change'] = (float) $ticker['priceChangePercent'];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Pakai fallback
            }
            return $defaultPrices;
        });
    }

    // ─────────────────────────────────────────────
    // PUBLIC LANDING
    // ─────────────────────────────────────────────
    public function landing()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('welcome');
    }

    // ─────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────
    public function dashboard()
    {
        $transactions = Auth::user()->transactions()->orderBy('transaction_date', 'asc')->get();
        $livePrices   = self::getLivePrices();

        // ── Build holdings dari transaksi manual ──
        $holdingsRaw = [];
        foreach ($transactions as $tx) {
            $symbol = $tx->asset_symbol;
            if (!isset($holdingsRaw[$symbol])) {
                $holdingsRaw[$symbol] = [
                    'symbol'        => $symbol,
                    'name'          => $tx->asset_name,
                    'quantity'      => 0.0,
                    'total_buy_cost'=> 0.0,
                    'total_buy_qty' => 0.0,
                ];
            }
            if ($tx->type === 'buy') {
                $holdingsRaw[$symbol]['quantity']       += $tx->quantity;
                $holdingsRaw[$symbol]['total_buy_cost'] += $tx->total_usd;
                $holdingsRaw[$symbol]['total_buy_qty']  += $tx->quantity;
            } else {
                $holdingsRaw[$symbol]['quantity'] -= $tx->quantity;
            }
        }

        $activeHoldings      = [];
        $totalPortfolioValue = 0.0;
        $totalPortfolioCost  = 0.0;

        foreach ($holdingsRaw as $symbol => $data) {
            if ($data['quantity'] <= 0.00000001) continue;

            $avgBuyPrice    = $data['total_buy_qty'] > 0 ? $data['total_buy_cost'] / $data['total_buy_qty'] : 0.0;
            $currentPrice   = $livePrices[$symbol]['price']  ?? 0.0;
            $priceChange24h = $livePrices[$symbol]['change'] ?? 0.0;
            $currentValue   = $data['quantity'] * $currentPrice;
            $totalCost      = $data['quantity'] * $avgBuyPrice;
            $pnl            = $currentValue - $totalCost;
            $pnlPercent     = $totalCost > 0 ? ($pnl / $totalCost) * 100 : 0.0;

            $activeHoldings[$symbol] = [
                'symbol'         => $symbol,
                'name'           => $data['name'],
                'quantity'       => $data['quantity'],
                'avg_buy_price'  => $avgBuyPrice,
                'current_price'  => $currentPrice,
                'price_change_24h' => $priceChange24h,
                'current_value'  => $currentValue,
                'total_cost'     => $totalCost,
                'pnl'            => $pnl,
                'pnl_percent'    => $pnlPercent,
            ];

            $totalPortfolioValue += $currentValue;
            $totalPortfolioCost  += $totalCost;
        }

        // ── Merge saldo wallet dari Moralis ──
        $wallets = Wallet::where('user_id', Auth::id())->get();
        foreach ($wallets as $wallet) {
            $hasBalances   = false;
            $balancesArray = $wallet->balances;

            if (is_string($balancesArray)) {
                $balancesArray = json_decode($balancesArray, true);
            }

            foreach (($balancesArray ?? []) as $bal) {
                $symbol = strtoupper($bal['symbol'] ?? $bal['token_symbol'] ?? '');
                $amount = (float) ($bal['amount'] ?? $bal['balance'] ?? 0);
                if ($symbol === '' || $amount <= 0) continue;

                $hasBalances = true;
                $usdValue    = (float) ($bal['usd_value'] ?? $bal['value'] ?? 0);
                $price       = ($amount > 0 && $usdValue > 0)
                    ? $usdValue / $amount
                    : ($livePrices[$symbol]['price'] ?? 0.0);
                if ($usdValue <= 0) $usdValue = $amount * $price;

                if (isset($activeHoldings[$symbol])) {
                    $h = $activeHoldings[$symbol];
                    $h['quantity']      += $amount;
                    $h['current_value'] += $usdValue;
                    $h['total_cost']    += $usdValue;
                    $h['current_price']  = $price ?: $h['current_price'];
                    $h['pnl']            = $h['current_value'] - $h['total_cost'];
                    $h['pnl_percent']    = $h['total_cost'] > 0 ? ($h['pnl'] / $h['total_cost']) * 100 : 0.0;
                    $h['avg_buy_price']  = $h['quantity'] > 0 ? $h['total_cost'] / $h['quantity'] : 0.0;
                    $activeHoldings[$symbol] = $h;
                } else {
                    $activeHoldings[$symbol] = [
                        'symbol'           => $symbol,
                        'name'             => $bal['name'] ?? $symbol,
                        'quantity'         => $amount,
                        'avg_buy_price'    => $price,
                        'current_price'    => $price,
                        'price_change_24h' => $livePrices[$symbol]['change'] ?? 0.0,
                        'current_value'    => $usdValue,
                        'total_cost'       => $usdValue,
                        'pnl'              => 0.0,
                        'pnl_percent'      => 0.0,
                    ];
                }
                $totalPortfolioValue += $usdValue;
                $totalPortfolioCost  += $usdValue;
            }

            // Fallback: wallet punya total_value tapi belum ada balances detail
            if (!$hasBalances && $wallet->total_value > 0) {
                $symbol   = 'ETH';
                $usdValue = (float) $wallet->total_value;
                $price    = $livePrices[$symbol]['price'] ?? 3850.00;
                $amount   = $price > 0 ? $usdValue / $price : 0.0;

                if (isset($activeHoldings[$symbol])) {
                    $activeHoldings[$symbol]['quantity']      += $amount;
                    $activeHoldings[$symbol]['current_value'] += $usdValue;
                    $activeHoldings[$symbol]['total_cost']    += $usdValue;
                } else {
                    $activeHoldings[$symbol] = [
                        'symbol'           => $symbol,
                        'name'             => 'Ethereum (On-Chain)',
                        'quantity'         => $amount,
                        'avg_buy_price'    => $price,
                        'current_price'    => $price,
                        'price_change_24h' => $livePrices[$symbol]['change'] ?? 0.0,
                        'current_value'    => $usdValue,
                        'total_cost'       => $usdValue,
                        'pnl'              => 0.0,
                        'pnl_percent'      => 0.0,
                    ];
                }
                $totalPortfolioValue += $usdValue;
                $totalPortfolioCost  += $usdValue;
            }
        }

        $activeHoldings = array_values($activeHoldings);
        $netPnL         = $totalPortfolioValue - $totalPortfolioCost;
        $netPnLPercent  = $totalPortfolioCost > 0 ? ($netPnL / $totalPortfolioCost) * 100 : 0.0;
        $recentTransactions = Auth::user()->transactions()->orderBy('transaction_date', 'desc')->take(5)->get();

        return view('portfolio.dashboard', [
            'holdings'           => $activeHoldings,
            'totalValue'         => $totalPortfolioValue,
            'totalCost'          => $totalPortfolioCost,
            'netPnL'             => $netPnL,
            'netPnLPercent'      => $netPnLPercent,
            'recentTransactions' => $recentTransactions,
            'livePrices'         => $livePrices,
        ]);
    }

    // ─────────────────────────────────────────────
    // TRANSACTIONS
    // ─────────────────────────────────────────────
    public function transactions()
    {
        $transactions = Auth::user()->transactions()->orderBy('transaction_date', 'desc')->paginate(15);
        $livePrices   = self::getLivePrices();

        return view('portfolio.transactions', [
            'transactions' => $transactions,
            'livePrices'   => $livePrices,
        ]);
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'asset_symbol'     => 'required|string|max:10',
            'type'             => 'required|in:buy,sell',
            'quantity'         => 'required|numeric|gt:0',
            'price_usd'        => 'required|numeric|gt:0',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        $livePrices = self::getLivePrices();
        $symbol     = strtoupper($validated['asset_symbol']);
        $assetName  = $livePrices[$symbol]['name'] ?? $symbol;

        if ($validated['type'] === 'sell') {
            $currentQty = Auth::user()->transactions()
                ->where('asset_symbol', $symbol)->get()
                ->reduce(fn($c, $tx) => $c + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity), 0.0);

            if ($validated['quantity'] > $currentQty) {
                return back()->withErrors([
                    'quantity' => "Stok tidak mencukupi. Sisa: {$currentQty} {$symbol}",
                ])->withInput();
            }
        }

        Auth::user()->transactions()->create([
            'asset_symbol'     => $symbol,
            'asset_name'       => $assetName,
            'type'             => $validated['type'],
            'quantity'         => $validated['quantity'],
            'price_usd'        => $validated['price_usd'],
            'total_usd'        => $validated['quantity'] * $validated['price_usd'],
            'transaction_date' => $validated['transaction_date'],
            'notes'            => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);

        if ($transaction->type === 'buy') {
            $symbol = $transaction->asset_symbol;
            $netQty = Auth::user()->transactions()
                ->where('asset_symbol', $symbol)
                ->where('id', '!=', $transaction->id)->get()
                ->reduce(fn($c, $tx) => $c + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity), 0.0);

            if ($netQty < 0) {
                return back()->withErrors([
                    'error' => "Tidak bisa hapus — akan membuat saldo {$symbol} jadi negatif.",
                ]);
            }
        }

        $transaction->delete();
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }

    // ─────────────────────────────────────────────
    // MARKET
    // ─────────────────────────────────────────────
    public function market()
    {
        $livePrices = self::getLivePrices();
        return view('portfolio.market', compact('livePrices'));
    }

    /**
     * JSON endpoint — dipoll frontend tiap 30 detik
     * GET /market/prices
     */
    public function marketPrices()
    {
        // Paksa refresh cache (bypass 30s cache biar dapat data terbaru)
        Cache::forget('live_prices');
        $prices = self::getLivePrices();

        return response()->json(
            collect($prices)->map(fn($v, $k) => [
                'symbol' => $k,
                'name'   => $v['name'],
                'price'  => $v['price'],
                'change' => $v['change'],
            ])->values()
        );
    }

    // ─────────────────────────────────────────────
    // REPORTS
    // ─────────────────────────────────────────────
    public function reports()
    {
        $transactions    = Auth::user()->transactions()->orderBy('transaction_date', 'asc')->get();
        $livePrices      = self::getLivePrices();
        $holdings        = [];
        $realizedGains   = 0.0;
        $unrealizedGains = 0.0;

        foreach ($transactions as $tx) {
            $symbol = $tx->asset_symbol;
            if (!isset($holdings[$symbol])) {
                $holdings[$symbol] = ['quantity' => 0.0, 'total_cost' => 0.0];
            }

            if ($tx->type === 'buy') {
                $holdings[$symbol]['quantity']   += $tx->quantity;
                $holdings[$symbol]['total_cost'] += $tx->total_usd;
            } else {
                $avg = $holdings[$symbol]['quantity'] > 0
                    ? $holdings[$symbol]['total_cost'] / $holdings[$symbol]['quantity']
                    : 0.0;
                $realizedGains                   += ($tx->price_usd - $avg) * $tx->quantity;
                $holdings[$symbol]['quantity']   -= $tx->quantity;
                $holdings[$symbol]['total_cost']  = $holdings[$symbol]['quantity'] * $avg;
            }
        }

        $holdingsData        = [];
        $totalPortfolioValue = 0.0;

        foreach ($holdings as $symbol => $data) {
            if ($data['quantity'] <= 0.00000001) continue;

            $currentPrice = $livePrices[$symbol]['price'] ?? 0.0;
            $currentValue = $data['quantity'] * $currentPrice;
            $assetPnL     = $currentValue - $data['total_cost'];
            $unrealizedGains += $assetPnL;

            $holdingsData[]       = [
                'symbol' => $symbol,
                'name'   => $livePrices[$symbol]['name'] ?? $symbol,
                'value'  => $currentValue,
                'pnl'    => $assetPnL,
            ];
            $totalPortfolioValue += $currentValue;
        }

        $exchangeDistribution = [
            'Binance'         => $totalPortfolioValue * 0.55,
            'Coinbase'        => $totalPortfolioValue * 0.25,
            'MetaMask Wallet' => $totalPortfolioValue * 0.20,
        ];

        return view('portfolio.reports', [
            'realizedGains'   => $realizedGains,
            'unrealizedGains' => $unrealizedGains,
            'holdings'        => $holdingsData,
            'totalValue'      => $totalPortfolioValue,
            'exchanges'       => $exchangeDistribution,
        ]);
    }
}