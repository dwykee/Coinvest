<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'profile_picture',
        'provider',
        'type',
        'address',
        'api_key',
        'api_secret',
        'last_synced_at',
        'total_value',
        'balances',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'total_value'    => 'float',
        'balances'       => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        // Siap disambung ke Transaction model nanti
        return $this->hasMany(Transaction::class);
    }
}
