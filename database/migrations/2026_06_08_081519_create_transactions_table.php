<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('asset_symbol'); // e.g. BTC, ETH
            $table->string('asset_name');   // e.g. Bitcoin, Ethereum
            $table->string('type');         // buy, sell
            $table->decimal('quantity', 24, 8); // Support decimal quantity
            $table->decimal('price_usd', 24, 8); // Price per coin in USD
            $table->decimal('total_usd', 24, 8); // Total transaction value
            $table->timestamp('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
