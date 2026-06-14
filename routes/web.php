<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\WalletController;

Route::get('/market/prices', [PortfolioController::class, 'marketPrices'])->name('market.prices');

// Debug Google OAuth Config
Route::get('/debug/oauth', function () {
    if (app()->environment('production')) {
        abort(404);
    }
    $clientId = env('GOOGLE_CLIENT_ID');
    $clientSecret = env('GOOGLE_CLIENT_SECRET');
    $redirectUri = env('GOOGLE_REDIRECT_URI');
    
    return response()->json([
        'GOOGLE_CLIENT_ID' => [
            'set' => !empty($clientId),
            'value' => $clientId ? (substr($clientId, 0, 10) . '...') : 'NOT SET',
            'length' => strlen($clientId ?? ''),
        ],
        'GOOGLE_CLIENT_SECRET' => [
            'set' => !empty($clientSecret),
            'value' => $clientSecret ? (substr($clientSecret, 0, 10) . '...') : 'NOT SET',
            'length' => strlen($clientSecret ?? ''),
        ],
        'GOOGLE_REDIRECT_URI' => $redirectUri,
        'APP_URL' => env('APP_URL'),
        'config_services_google' => config('services.google'),
    ], 200, [], JSON_PRETTY_PRINT);
});

// Public Landing Page
Route::get('/', [PortfolioController::class, 'landing'])->name('landing');
Route::get('/demo', [AuthController::class, 'demoLogin'])->name('demo');

//wallets route
Route::prefix('wallets')->name('wallets.')->middleware('auth')->group(function () {
    Route::get('/',        [WalletController::class, 'index'])->name('index');
    Route::get('/select',  [WalletController::class, 'select'])->name('select');
    Route::post('/',       [WalletController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [WalletController::class, 'edit'])->name('edit');
    Route::put('/{id}',    [WalletController::class, 'update'])->name('update');
    Route::post('/{id}/sync', [WalletController::class, 'sync'])->name('sync');
    Route::delete('/{id}/photo', [WalletController::class, 'deletePhoto'])->name('deletePhoto');
    Route::delete('/{id}', [WalletController::class, 'destroy'])->name('destroy');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Google OAuth Redirect (user belum login)
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');
});

// Google OAuth Callback (tanpa middleware, karena proses login ada di sini)
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Portfolio Dashboard
    Route::get('/dashboard', [PortfolioController::class, 'dashboard'])->name('dashboard');
    
    // Transactions
    Route::get('/transactions', [PortfolioController::class, 'transactions'])->name('transactions.index');
    Route::post('/transactions', [PortfolioController::class, 'storeTransaction'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [PortfolioController::class, 'destroyTransaction'])->name('transactions.destroy');
    
    // Market Ticker & Ticker Simulation
    Route::get('/market', [PortfolioController::class, 'market'])->name('market');
    
    // Analysis & Tax Reports
    Route::get('/reports', [PortfolioController::class, 'reports'])->name('reports');
});
