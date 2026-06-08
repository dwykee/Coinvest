<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\WalletController;

// Public Landing Page
Route::get('/', [PortfolioController::class, 'landing'])->name('landing');
Route::get('/demo', [AuthController::class, 'demoLogin'])->name('demo');

//wallets route
Route::prefix('wallets')->name('wallets.')->middleware('auth')->group(function () {
    Route::get('/',        [WalletController::class, 'index'])->name('index');
    Route::get('/select',  [WalletController::class, 'select'])->name('select');
    Route::post('/',       [WalletController::class, 'store'])->name('store');
    Route::post('/{id}/sync', [WalletController::class, 'sync'])->name('sync');  // ← tambah ini
    Route::delete('/{id}', [WalletController::class, 'destroy'])->name('destroy');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

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
