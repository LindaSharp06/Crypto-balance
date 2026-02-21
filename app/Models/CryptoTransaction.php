<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'tx_hash',
        'status',
        'idempotency_key',
    ];
}
