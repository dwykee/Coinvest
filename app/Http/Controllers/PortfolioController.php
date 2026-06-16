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
     * Get real-time crypto prices via CoinGecko API with fallback.
     */
    public static function getLivePrices()
    {
        $defaultPrices = [
            'BTC'   => ['name' => 'Bitcoin',   'price' => 68500.00, 'change' => 0.0],
            'ETH'   => ['name' => 'Ethereum',  'price' => 3850.00,  'change' => 0.0],
            'SOL'   => ['name' => 'Solana',    'price' => 155.20,   'change' => 0.0],
            'BNB'   => ['name' => 'BNB',       'price' => 580.40,   'change' => 0.0],
            'XRP'   => ['name' => 'Ripple',    'price' => 0.52,     'change' => 0.0],
            'ADA'   => ['name' => 'Cardano',   'price' => 0.46,     'change' => 0.0],
            'DOGE'  => ['name' => 'Dogecoin',  'price' => 0.142,    'change' => 0.0],
            'DOT'   => ['name' => 'Polkadot',  'price' => 6.25,     'change' => 0.0],
            'AVAX'  => ['name' => 'Avalanche', 'price' => 32.80,    'change' => 0.0],
            'LINK'  => ['name' => 'Chainlink', 'price' => 16.40,    'change' => 0.0],
            'MATIC' => ['name' => 'Polygon',   'price' => 0.70,     'change' => 0.0],
            'USDT'  => ['name' => 'Tether',    'price' => 1.00,     'change' => 0.0],
            'USDC'  => ['name' => 'USD Coin',  'price' => 1.00,     'change' => 0.0],
        ];

        $coinGeckoIds = [
            'BTC'   => 'bitcoin',
            'ETH'   => 'ethereum',
            'SOL'   => 'solana',
            'BNB'   => 'binancecoin',
            'XRP'   => 'ripple',
            'ADA'   => 'cardano',
            'DOGE'  => 'dogecoin',
            'DOT'   => 'polkadot',
            'AVAX'  => 'avalanche-2',
            'LINK'  => 'chainlink',
            'MATIC' => 'matic-network',
            'USDT'  => 'tether',
            'USDC'  => 'usd-coin',
        ];

        return Cache::remember('live_prices', 30, function () use ($defaultPrices, $coinGeckoIds) {
            try {
                $baseUrl = rtrim(config('services.coingecko.base_url', 'https://api.coingecko.com/api/v3'), '/');
                $apiKey  = config('services.coingecko.api_key');

                $headers = [];

                if (!empty($apiKey)) {
                    if (str_contains($baseUrl, 'pro-api.coingecko.com')) {
                        $headers['x-cg-pro-api-key'] = $apiKey;
                    } else {
                        $headers['x-cg-demo-api-key'] = $apiKey;
                    }
                }

                $response = Http::timeout(10)
                    ->withHeaders($headers)
                    ->get($baseUrl . '/simple/price', [
                        'ids' => implode(',', array_values($coinGeckoIds)),
                        'vs_currencies' => 'usd',
                        'include_24hr_change' => 'true',
                    ]);

                if (!$response->successful()) {
                    return $defaultPrices;
                }

                $data = $response->json();

                foreach ($coinGeckoIds as $symbol => $id) {
                    if (isset($data[$id]['usd'])) {
                        $defaultPrices[$symbol]['price'] = (float) $data[$id]['usd'];
                    }

                    if (isset($data[$id]['usd_24h_change'])) {
                        $defaultPrices[$symbol]['change'] = (float) $data[$id]['usd_24h_change'];
                    }
                }

                return $defaultPrices;
            } catch (\Exception $e) {
                return $defaultPrices;
            }
        });
    }

    public function landing()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }

    public function dashboard()
    {
        $transactions = Auth::user()
            ->transactions()
            ->orderBy('transaction_date', 'asc')
            ->get();

        $livePrices = self::getLivePrices();

        $holdingsRaw = [];

        foreach ($transactions as $tx) {
            $symbol = strtoupper($tx->asset_symbol);

            if (!isset($holdingsRaw[$symbol])) {
                $holdingsRaw[$symbol] = [
                    'symbol'         => $symbol,
                    'name'           => $tx->asset_name,
                    'quantity'       => 0.0,
                    'total_buy_cost' => 0.0,
                    'total_buy_qty'  => 0.0,
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
            if ($data['quantity'] <= 0.00000001) {
                continue;
            }

            $avgBuyPrice    = $data['total_buy_qty'] > 0 ? $data['total_buy_cost'] / $data['total_buy_qty'] : 0.0;
            $currentPrice   = $livePrices[$symbol]['price'] ?? 0.0;
            $priceChange24h = $livePrices[$symbol]['change'] ?? 0.0;
            $currentValue   = $data['quantity'] * $currentPrice;
            $totalCost      = $data['quantity'] * $avgBuyPrice;
            $pnl            = $currentValue - $totalCost;
            $pnlPercent     = $totalCost > 0 ? ($pnl / $totalCost) * 100 : 0.0;

            $activeHoldings[$symbol] = [
                'symbol'           => $symbol,
                'name'             => $data['name'],
                'quantity'         => $data['quantity'],
                'avg_buy_price'    => $avgBuyPrice,
                'current_price'    => $currentPrice,
                'price_change_24h' => $priceChange24h,
                'current_value'    => $currentValue,
                'total_cost'       => $totalCost,
                'pnl'              => $pnl,
                'pnl_percent'      => $pnlPercent,
            ];

            $totalPortfolioValue += $currentValue;
            $totalPortfolioCost  += $totalCost;
        }

        $wallets = Wallet::where('user_id', Auth::id())->get();

        foreach ($wallets as $wallet) {
            $balancesArray = $wallet->balances;

            if (is_string($balancesArray)) {
                $balancesArray = json_decode($balancesArray, true);
            }

            foreach (($balancesArray ?? []) as $bal) {
                $symbol = strtoupper($bal['symbol'] ?? $bal['token_symbol'] ?? '');
                $amount = (float) ($bal['amount'] ?? $bal['balance'] ?? 0);

                if ($symbol === '' || $amount <= 0) {
                    continue;
                }

                $priceSymbol = match ($symbol) {
                    'WETH'   => 'ETH',
                    'WBTC'   => 'BTC',
                    'WMATIC' => 'MATIC',
                    default  => $symbol,
                };

                $moralisUsdValue = (float) ($bal['usd_value'] ?? $bal['value'] ?? 0);
                $livePrice       = $livePrices[$priceSymbol]['price'] ?? 0.0;

                if ($livePrice > 0) {
                    $price    = $livePrice;
                    $usdValue = $amount * $price;
                } else {
                    $usdValue = $moralisUsdValue;
                    $price    = ($amount > 0 && $usdValue > 0) ? $usdValue / $amount : 0.0;
                }

                $name = $bal['name'] ?? ($livePrices[$priceSymbol]['name'] ?? $symbol);
                $change24h = $livePrices[$priceSymbol]['change'] ?? 0.0;

                if (isset($activeHoldings[$symbol])) {
                    $h = $activeHoldings[$symbol];

                    $h['quantity']         += $amount;
                    $h['current_value']    += $usdValue;
                    $h['total_cost']       += $usdValue;
                    $h['current_price']     = $price ?: $h['current_price'];
                    $h['price_change_24h']  = $change24h;
                    $h['pnl']               = $h['current_value'] - $h['total_cost'];
                    $h['pnl_percent']       = $h['total_cost'] > 0 ? ($h['pnl'] / $h['total_cost']) * 100 : 0.0;
                    $h['avg_buy_price']     = $h['quantity'] > 0 ? $h['total_cost'] / $h['quantity'] : 0.0;

                    $activeHoldings[$symbol] = $h;
                } else {
                    $activeHoldings[$symbol] = [
                        'symbol'           => $symbol,
                        'name'             => $name,
                        'quantity'         => $amount,
                        'avg_buy_price'    => $price,
                        'current_price'    => $price,
                        'price_change_24h' => $change24h,
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

        $netPnL        = $totalPortfolioValue - $totalPortfolioCost;
        $netPnLPercent = $totalPortfolioCost > 0 ? ($netPnL / $totalPortfolioCost) * 100 : 0.0;

        $recentTransactions = Auth::user()
            ->transactions()
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();

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

    public function transactions()
    {
        $transactions = Auth::user()
            ->transactions()
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        $livePrices = self::getLivePrices();

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
            $currentQty = Auth::user()
                ->transactions()
                ->where('asset_symbol', $symbol)
                ->get()
                ->reduce(fn ($c, $tx) => $c + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity), 0.0);

            if ($validated['quantity'] > $currentQty) {
                return back()
                    ->withErrors([
                        'quantity' => "Stok tidak mencukupi. Sisa: {$currentQty} {$symbol}",
                    ])
                    ->withInput();
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

        return redirect()
            ->back()
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->type === 'buy') {
            $symbol = $transaction->asset_symbol;

            $netQty = Auth::user()
                ->transactions()
                ->where('asset_symbol', $symbol)
                ->where('id', '!=', $transaction->id)
                ->get()
                ->reduce(fn ($c, $tx) => $c + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity), 0.0);

            if ($netQty < 0) {
                return back()->withErrors([
                    'error' => "Tidak bisa hapus — akan membuat saldo {$symbol} jadi negatif.",
                ]);
            }
        }

        $transaction->delete();

        return redirect()
            ->back()
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function market()
    {
        $livePrices = self::getLivePrices();

        return view('portfolio.market', compact('livePrices'));
    }

    public function marketPrices()
    {
        Cache::forget('live_prices');

        $prices = self::getLivePrices();

        return response()
            ->json(
                collect($prices)->map(fn ($v, $k) => [
                    'symbol' => $k,
                    'name'   => $v['name'],
                    'price'  => $v['price'],
                    'change' => $v['change'],
                ])->values()
            )
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function reports()
    {
        $transactions = Auth::user()
            ->transactions()
            ->orderBy('transaction_date', 'asc')
            ->get();

        $livePrices = self::getLivePrices();

        $holdings        = [];
        $realizedGains   = 0.0;
        $unrealizedGains = 0.0;

        foreach ($transactions as $tx) {
            $symbol = strtoupper($tx->asset_symbol);

            if (!isset($holdings[$symbol])) {
                $holdings[$symbol] = [
                    'quantity'   => 0.0,
                    'total_cost' => 0.0,
                ];
            }

            if ($tx->type === 'buy') {
                $holdings[$symbol]['quantity']   += $tx->quantity;
                $holdings[$symbol]['total_cost'] += $tx->total_usd;
            } else {
                $avg = $holdings[$symbol]['quantity'] > 0
                    ? $holdings[$symbol]['total_cost'] / $holdings[$symbol]['quantity']
                    : 0.0;

                $realizedGains += ($tx->price_usd - $avg) * $tx->quantity;

                $holdings[$symbol]['quantity']   -= $tx->quantity;
                $holdings[$symbol]['total_cost']  = $holdings[$symbol]['quantity'] * $avg;
            }
        }

        $holdingsData        = [];
        $totalPortfolioValue = 0.0;

        foreach ($holdings as $symbol => $data) {
            if ($data['quantity'] <= 0.00000001) {
                continue;
            }

            $currentPrice = $livePrices[$symbol]['price'] ?? 0.0;
            $currentValue = $data['quantity'] * $currentPrice;
            $assetPnL     = $currentValue - $data['total_cost'];

            $unrealizedGains += $assetPnL;

            $holdingsData[] = [
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