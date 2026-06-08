<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoralisService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://deep-index.moralis.io/api/v2.2';

    public function __construct()
    {
        $this->apiKey = config('services.moralis.key');
    }

    /**
     * Detect chain dari address format
     */
    public function detectChain(string $address): string
    {
        // Solana address: base58, 32-44 karakter, tidak diawali 0x
        if (!str_starts_with($address, '0x') && strlen($address) >= 32 && strlen($address) <= 44) {
            return 'solana';
        }
        // Default EVM
        return 'eth';
    }

    /**
     * Fetch native + token balances untuk EVM wallet
     * Returns: ['native' => [...], 'tokens' => [...], 'total_usd' => float]
     */
    public function getEVMBalances(string $address, string $chain = 'eth'): array
    {
        try {
            // Native balance (ETH, BNB, MATIC, dll)
            $nativeRes = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept'    => 'application/json',
            ])->get("{$this->baseUrl}/{$address}/balance", [
                'chain' => $chain,
            ]);

            // Token balances
            $tokenRes = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept'    => 'application/json',
            ])->get("{$this->baseUrl}/{$address}/erc20", [
                'chain' => $chain,
                'limit' => 20,
            ]);

            if ($nativeRes->failed() || $tokenRes->failed()) {
                return $this->errorResult('Gagal fetch dari Moralis: ' . $nativeRes->status());
            }

            $nativeData = $nativeRes->json();
            $tokenData  = $tokenRes->json();

            $nativeSymbols = [
                'eth'       => ['symbol' => 'ETH',  'name' => 'Ethereum'],
                '0x1'       => ['symbol' => 'ETH',  'name' => 'Ethereum'],
                'bsc'       => ['symbol' => 'BNB',  'name' => 'BNB'],
                '0x38'      => ['symbol' => 'BNB',  'name' => 'BNB'],
                'polygon'   => ['symbol' => 'MATIC','name' => 'Polygon'],
                '0x89'      => ['symbol' => 'MATIC','name' => 'Polygon'],
                'arbitrum'  => ['symbol' => 'ETH',  'name' => 'Ethereum (Arbitrum)'],
                'avalanche' => ['symbol' => 'AVAX', 'name' => 'Avalanche'],
                'base'      => ['symbol' => 'ETH',  'name' => 'Ethereum (Base)'],
            ];

            $nativeInfo   = $nativeSymbols[$chain] ?? ['symbol' => 'ETH', 'name' => 'Ethereum'];
            $nativeAmount = isset($nativeData['balance'])
                ? bcdiv($nativeData['balance'], bcpow('10', '18', 0), 8)
                : '0';

            $balances   = [];
            $totalUsd   = 0.0;

            // Native token
            if ((float) $nativeAmount > 0) {
                $nativePrice = $this->getTokenPrice($nativeInfo['symbol']);
                $nativeUsd   = (float) $nativeAmount * $nativePrice;
                $totalUsd   += $nativeUsd;
                $balances[]  = [
                    'symbol'    => $nativeInfo['symbol'],
                    'name'      => $nativeInfo['name'],
                    'amount'    => (float) $nativeAmount,
                    'usd_value' => round($nativeUsd, 2),
                    'type'      => 'native',
                ];
            }

            // ERC20 tokens
            $tokens = $tokenData['result'] ?? [];
            foreach (array_slice($tokens, 0, 15) as $token) {
                $decimals = (int) ($token['decimals'] ?? 18);
                $rawBal   = $token['balance'] ?? '0';
                $amount   = (float) bcdiv($rawBal, bcpow('10', (string) $decimals, 0), 8);

                if ($amount < 0.000001) continue;

                $symbol   = $token['symbol'] ?? '???';
                $price    = $this->getTokenPrice($symbol);
                $usdVal   = $amount * $price;
                $totalUsd += $usdVal;

                $balances[] = [
                    'symbol'    => $symbol,
                    'name'      => $token['name'] ?? $symbol,
                    'amount'    => $amount,
                    'usd_value' => round($usdVal, 2),
                    'type'      => 'erc20',
                ];
            }

            return [
                'success'   => true,
                'chain'     => $chain,
                'balances'  => $balances,
                'total_usd' => round($totalUsd, 2),
            ];

        } catch (\Exception $e) {
            Log::error('MoralisService EVM error: ' . $e->getMessage());
            return $this->errorResult($e->getMessage());
        }
    }

    /**
     * Fetch Solana wallet balances
     */
    public function getSolanaBalances(string $address): array
    {
        try {
            $res = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept'    => 'application/json',
            ])->get("{$this->baseUrl}/solana/account/{$address}/balance");

            if ($res->failed()) {
                return $this->errorResult('Gagal fetch Solana balance: ' . $res->status());
            }

            $data     = $res->json();
            $solAmount = (float) ($data['solana'] ?? 0);
            $solPrice  = $this->getTokenPrice('SOL');
            $usdVal    = $solAmount * $solPrice;

            $balances = [];
            if ($solAmount > 0) {
                $balances[] = [
                    'symbol'    => 'SOL',
                    'name'      => 'Solana',
                    'amount'    => $solAmount,
                    'usd_value' => round($usdVal, 2),
                    'type'      => 'native',
                ];
            }

            return [
                'success'   => true,
                'chain'     => 'solana',
                'balances'  => $balances,
                'total_usd' => round($usdVal, 2),
            ];

        } catch (\Exception $e) {
            Log::error('MoralisService Solana error: ' . $e->getMessage());
            return $this->errorResult($e->getMessage());
        }
    }

    /**
     * Get token price in USD via Moralis Price API
     */
    public function getTokenPrice(string $symbol): float
    {
        // Fallback prices kalau API price gagal
        $fallback = [
            'ETH'  => 3500.0, 'BTC'  => 65000.0, 'BNB'  => 580.0,
            'SOL'  => 150.0,  'MATIC'=> 0.7,      'AVAX' => 35.0,
            'USDT' => 1.0,    'USDC' => 1.0,      'DAI'  => 1.0,
            'BUSD' => 1.0,    'LINK' => 14.0,     'UNI'  => 8.0,
            'AAVE' => 90.0,   'ARB'  => 1.1,      'OP'   => 2.0,
        ];

        try {
            $res = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept'    => 'application/json',
            ])->get("{$this->baseUrl}/erc20/price", [
                'symbol' => strtoupper($symbol),
                'chain'  => 'eth',
            ]);

            if ($res->ok()) {
                $data = $res->json();
                return (float) ($data['usdPrice'] ?? $fallback[strtoupper($symbol)] ?? 0.0);
            }
        } catch (\Exception $e) {
            // silent fail, pakai fallback
        }

        return $fallback[strtoupper($symbol)] ?? 0.0;
    }

    /**
     * Main entry point — auto detect chain & fetch
     */
    public function fetchWalletData(string $address, ?string $chain = null): array
    {
        $detectedChain = $chain ?? $this->detectChain($address);

        if ($detectedChain === 'solana') {
            return $this->getSolanaBalances($address);
        }

        return $this->getEVMBalances($address, $detectedChain);
    }

    private function errorResult(string $message): array
    {
        return [
            'success'   => false,
            'error'     => $message,
            'balances'  => [],
            'total_usd' => 0.0,
        ];
    }
}
