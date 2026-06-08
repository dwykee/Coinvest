<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the demo user
        $user = User::create([
            'name' => 'Rizki Coinvest',
            'email' => 'demo@coinvest.com',
            'password' => Hash::make('password'),
        ]);

        // Seed portfolio transactions for the demo user
        $transactions = [
            [
                'asset_symbol' => 'BTC',
                'asset_name' => 'Bitcoin',
                'type' => 'buy',
                'quantity' => 0.45,
                'price_usd' => 45000.00,
                'transaction_date' => Carbon::now()->subMonths(5),
                'notes' => 'Pembelian pertama BTC, ditransfer ke cold storage.',
            ],
            [
                'asset_symbol' => 'ETH',
                'asset_name' => 'Ethereum',
                'type' => 'buy',
                'quantity' => 3.50,
                'price_usd' => 2200.00,
                'transaction_date' => Carbon::now()->subMonths(4),
                'notes' => 'Beli di Binance saat dip.',
            ],
            [
                'asset_symbol' => 'SOL',
                'asset_name' => 'Solana',
                'type' => 'buy',
                'quantity' => 25.00,
                'price_usd' => 98.50,
                'transaction_date' => Carbon::now()->subMonths(3),
                'notes' => 'Beli di Phantom Wallet untuk DeFi.',
            ],
            [
                'asset_symbol' => 'BTC',
                'asset_name' => 'Bitcoin',
                'type' => 'buy',
                'quantity' => 0.15,
                'price_usd' => 58200.00,
                'transaction_date' => Carbon::now()->subMonths(2),
                'notes' => 'Dollar-cost averaging (DCA).',
            ],
            [
                'asset_symbol' => 'ETH',
                'asset_name' => 'Ethereum',
                'type' => 'sell',
                'quantity' => 1.20,
                'price_usd' => 3450.00,
                'transaction_date' => Carbon::now()->subMonths(1)->subDays(10),
                'notes' => 'Taking profit untuk keperluan UAS.',
            ],
            [
                'asset_symbol' => 'DOGE',
                'asset_name' => 'Dogecoin',
                'type' => 'buy',
                'quantity' => 15000.00,
                'price_usd' => 0.085,
                'transaction_date' => Carbon::now()->subDays(20),
                'notes' => 'Meme coin allocation, fun play.',
            ],
            [
                'asset_symbol' => 'SOL',
                'asset_name' => 'Solana',
                'type' => 'buy',
                'quantity' => 10.00,
                'price_usd' => 135.00,
                'transaction_date' => Carbon::now()->subDays(5),
                'notes' => 'Menambah porsi SOL sebelum breakout.',
            ]
        ];

        foreach ($transactions as $tx) {
            $user->transactions()->create([
                'asset_symbol' => $tx['asset_symbol'],
                'asset_name' => $tx['asset_name'],
                'type' => $tx['type'],
                'quantity' => $tx['quantity'],
                'price_usd' => $tx['price_usd'],
                'total_usd' => $tx['quantity'] * $tx['price_usd'],
                'transaction_date' => $tx['transaction_date'],
                'notes' => $tx['notes'],
            ]);
        }
    }
}
