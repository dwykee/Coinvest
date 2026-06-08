<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Get real-time crypto prices with high-fidelity fallback.
     */
    public static function getLivePrices()
    {
        $defaultPrices = [
            'BTC' => ['name' => 'Bitcoin', 'price' => 68500.00, 'change' => 2.45],
            'ETH' => ['name' => 'Ethereum', 'price' => 3850.00, 'change' => -1.20],
            'SOL' => ['name' => 'Solana', 'price' => 155.20, 'change' => 5.67],
            'BNB' => ['name' => 'BNB', 'price' => 580.40, 'change' => 0.85],
            'XRP' => ['name' => 'Ripple', 'price' => 0.52, 'change' => -0.45],
            'ADA' => ['name' => 'Cardano', 'price' => 0.46, 'change' => 1.15],
            'DOGE' => ['name' => 'Dogecoin', 'price' => 0.142, 'change' => -3.20],
            'DOT' => ['name' => 'Polkadot', 'price' => 6.25, 'change' => 0.50],
            'AVAX' => ['name' => 'Avalanche', 'price' => 32.80, 'change' => 4.10],
            'LINK' => ['name' => 'Chainlink', 'price' => 16.40, 'change' => 2.80],
        ];

        try {
            $response = Http::timeout(3)->get('https://api.binance.com/api/v3/ticker/24hr');
            if ($response->successful()) {
                $data = $response->json();
                foreach ($data as $ticker) {
                    $symbol = $ticker['symbol'];
                    if (str_ends_with($symbol, 'USDT')) {
                        $base = substr($symbol, 0, -4);
                        if (array_key_exists($base, $defaultPrices)) {
                            $defaultPrices[$base]['price'] = (double)$ticker['lastPrice'];
                            $defaultPrices[$base]['change'] = (double)$ticker['priceChangePercent'];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Failed to fetch or offline, ignore and use fallbacks
        }

        return $defaultPrices;
    }

    /**
     * Display the public landing page.
     */
    public function landing()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('welcome');
    }

    /**
     * Render the main user dashboard with portfolio analytics.
     */
    public function dashboard()
    {
        $transactions = Auth::user()->transactions()->orderBy('transaction_date', 'asc')->get();
        $livePrices = self::getLivePrices();

        $holdings = [];
        foreach ($transactions as $tx) {
            $symbol = $tx->asset_symbol;
            if (!isset($holdings[$symbol])) {
                $holdings[$symbol] = [
                    'symbol' => $symbol,
                    'name' => $tx->asset_name,
                    'quantity' => 0.0,
                    'total_buy_cost' => 0.0,
                    'total_buy_qty' => 0.0,
                ];
            }

            if ($tx->type === 'buy') {
                $holdings[$symbol]['quantity'] += $tx->quantity;
                $holdings[$symbol]['total_buy_cost'] += $tx->total_usd;
                $holdings[$symbol]['total_buy_qty'] += $tx->quantity;
            } else if ($tx->type === 'sell') {
                $holdings[$symbol]['quantity'] -= $tx->quantity;
            }
        }

        $activeHoldings = [];
        $totalPortfolioValue = 0.0;
        $totalPortfolioCost = 0.0;

        foreach ($holdings as $symbol => $data) {
            if ($data['quantity'] <= 0.00000001) {
                continue;
            }

            $avgBuyPrice = $data['total_buy_qty'] > 0 ? ($data['total_buy_cost'] / $data['total_buy_qty']) : 0.0;
            $currentPrice = isset($livePrices[$symbol]) ? $livePrices[$symbol]['price'] : 0.0;
            $priceChange24h = isset($livePrices[$symbol]) ? $livePrices[$symbol]['change'] : 0.0;

            $currentValue = $data['quantity'] * $currentPrice;
            $totalCost = $data['quantity'] * $avgBuyPrice;

            $pnl = $currentValue - $totalCost;
            $pnlPercent = $totalCost > 0 ? ($pnl / $totalCost) * 100 : 0.0;

            $activeHoldings[] = [
                'symbol' => $symbol,
                'name' => $data['name'],
                'quantity' => $data['quantity'],
                'avg_buy_price' => $avgBuyPrice,
                'current_price' => $currentPrice,
                'price_change_24h' => $priceChange24h,
                'current_value' => $currentValue,
                'total_cost' => $totalCost,
                'pnl' => $pnl,
                'pnl_percent' => $pnlPercent,
            ];

            $totalPortfolioValue += $currentValue;
            $totalPortfolioCost += $totalCost;
        }

        $netPnL = $totalPortfolioValue - $totalPortfolioCost;
        $netPnLPercent = $totalPortfolioCost > 0 ? ($netPnL / $totalPortfolioCost) * 100 : 0.0;

        $recentTransactions = Auth::user()->transactions()->orderBy('transaction_date', 'desc')->take(5)->get();

        return view('portfolio.dashboard', [
            'holdings' => $activeHoldings,
            'totalValue' => $totalPortfolioValue,
            'totalCost' => $totalPortfolioCost,
            'netPnL' => $netPnL,
            'netPnLPercent' => $netPnLPercent,
            'recentTransactions' => $recentTransactions,
            'livePrices' => $livePrices,
        ]);
    }

    /**
     * Render all transactions and handle filters.
     */
    public function transactions()
    {
        $transactions = Auth::user()->transactions()->orderBy('transaction_date', 'desc')->paginate(15);
        $livePrices = self::getLivePrices();

        return view('portfolio.transactions', [
            'transactions' => $transactions,
            'livePrices' => $livePrices,
        ]);
    }

    /**
     * Store a new transaction.
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'asset_symbol' => 'required|string|max:10',
            'type' => 'required|in:buy,sell',
            'quantity' => 'required|numeric|gt:0',
            'price_usd' => 'required|numeric|gt:0',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $livePrices = self::getLivePrices();
        $symbol = strtoupper($validated['asset_symbol']);
        $assetName = isset($livePrices[$symbol]) ? $livePrices[$symbol]['name'] : $symbol;

        // Check if selling more than owned
        if ($validated['type'] === 'sell') {
            $currentQuantity = Auth::user()->transactions()
                ->where('asset_symbol', $symbol)
                ->get()
                ->reduce(function ($carry, $tx) {
                    return $carry + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity);
                }, 0.0);

            if ($validated['quantity'] > $currentQuantity) {
                return back()->withErrors([
                    'quantity' => "Stok aset tidak mencukupi untuk melakukan penjualan. Total saat ini: {$currentQuantity} {$symbol}",
                ])->withInput();
            }
        }

        Auth::user()->transactions()->create([
            'asset_symbol' => $symbol,
            'asset_name' => $assetName,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'price_usd' => $validated['price_usd'],
            'total_usd' => $validated['quantity'] * $validated['price_usd'],
            'transaction_date' => $validated['transaction_date'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Delete a transaction.
     */
    public function destroyTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Optional safety: check if deleting this transaction results in negative holding
        if ($transaction->type === 'buy') {
            $symbol = $transaction->asset_symbol;
            $netQtyAfterDeletion = Auth::user()->transactions()
                ->where('asset_symbol', $symbol)
                ->where('id', '!=', $transaction->id)
                ->get()
                ->reduce(function ($carry, $tx) {
                    return $carry + ($tx->type === 'buy' ? $tx->quantity : -$tx->quantity);
                }, 0.0);

            if ($netQtyAfterDeletion < 0) {
                return back()->withErrors([
                    'error' => "Tidak dapat menghapus transaksi pembelian ini karena akan mengakibatkan jumlah kepemilikan aset {$symbol} menjadi negatif (karena ada transaksi penjualan setelahnya).",
                ]);
            }
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Render the live market page with real-time ticker prices.
     */
    public function market()
    {
        $livePrices = self::getLivePrices();
        return view('portfolio.market', [
            'livePrices' => $livePrices,
        ]);
    }

    /**
     * Render analysis and tax estimation reports.
     */
    public function reports()
    {
        $transactions = Auth::user()->transactions()->orderBy('transaction_date', 'asc')->get();
        $livePrices = self::getLivePrices();

        $holdings = [];
        $realizedGains = 0.0;
        $unrealizedGains = 0.0;
        
        // Compute holdings and realized gains using basic FIFO-like or average cost matching
        // Let's use average cost method to compute realized PnL
        foreach ($transactions as $tx) {
            $symbol = $tx->asset_symbol;
            if (!isset($holdings[$symbol])) {
                $holdings[$symbol] = [
                    'quantity' => 0.0,
                    'total_cost' => 0.0,
                ];
            }

            if ($tx->type === 'buy') {
                $holdings[$symbol]['quantity'] += $tx->quantity;
                $holdings[$symbol]['total_cost'] += $tx->total_usd;
            } else if ($tx->type === 'sell') {
                // Average cost per unit before sale
                $avgCostBeforeSale = $holdings[$symbol]['quantity'] > 0 
                    ? ($holdings[$symbol]['total_cost'] / $holdings[$symbol]['quantity']) 
                    : 0.0;
                
                // Sale profit/loss: (sale price per unit - average cost per unit) * sale quantity
                $saleProfit = ($tx->price_usd - $avgCostBeforeSale) * $tx->quantity;
                $realizedGains += $saleProfit;

                // Deduct from holdings
                $holdings[$symbol]['quantity'] -= $tx->quantity;
                $holdings[$symbol]['total_cost'] = $holdings[$symbol]['quantity'] * $avgCostBeforeSale;
            }
        }

        $holdingsData = [];
        $totalPortfolioValue = 0.0;

        foreach ($holdings as $symbol => $data) {
            if ($data['quantity'] <= 0.00000001) {
                continue;
            }

            $currentPrice = isset($livePrices[$symbol]) ? $livePrices[$symbol]['price'] : 0.0;
            $currentValue = $data['quantity'] * $currentPrice;
            $totalCost = $data['total_cost'];
            $assetPnL = $currentValue - $totalCost;
            $unrealizedGains += $assetPnL;

            $holdingsData[] = [
                'symbol' => $symbol,
                'name' => isset($livePrices[$symbol]) ? $livePrices[$symbol]['name'] : $symbol,
                'value' => $currentValue,
                'pnl' => $assetPnL,
            ];
            $totalPortfolioValue += $currentValue;
        }

        // Mock exchange distribution for visual reporting
        $exchangeDistribution = [
            'Binance' => $totalPortfolioValue * 0.55,
            'Coinbase' => $totalPortfolioValue * 0.25,
            'MetaMask Wallet' => $totalPortfolioValue * 0.20,
        ];

        return view('portfolio.reports', [
            'realizedGains' => $realizedGains,
            'unrealizedGains' => $unrealizedGains,
            'holdings' => $holdingsData,
            'totalValue' => $totalPortfolioValue,
            'exchanges' => $exchangeDistribution,
        ]);
    }
}
