<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'asset_symbol',
        'asset_name',
        'type',
        'quantity',
        'price_usd',
        'total_usd',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'double',
        'price_usd' => 'double',
        'total_usd' => 'double',
        'transaction_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
