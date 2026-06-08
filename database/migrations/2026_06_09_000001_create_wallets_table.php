<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('provider');                  // e.g. MetaMask, Binance
            $table->enum('type', ['wallet', 'exchange']);
            $table->string('address')->nullable();       // untuk wallet/blockchain
            $table->string('api_key')->nullable();       // untuk CEX
            $table->text('api_secret')->nullable();      // encrypted
            $table->timestamp('last_synced_at')->nullable();
            $table->decimal('total_value', 20, 8)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
