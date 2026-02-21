<?php

namespace App\Services;

use App\Models\User;
use App\Models\CryptoTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class CryptoBalanceService
{
    public function deposit(User $user, float $amount, string $txHash, string $key)
    {
        return DB::transaction(function () use ($user, $amount, $txHash, $key) {

            if (CryptoTransaction::where('idempotency_key', $key)->exists()) {
                throw new Exception('Duplicate transaction');
            }

            $user = User::where('id', $user->id)
                        ->lockForUpdate()
                        ->first();

            $user->crypto_balance += $amount;
            $user->save();

            return CryptoTransaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
                'tx_hash' => $txHash,
                'status' => 'confirmed',
                'idempotency_key' => $key,
            ]);
        });
    }

    public function withdraw(User $user, float $amount, string $key)
    {
        return DB::transaction(function () use ($user, $amount, $key) {

            if ($amount <= 0) {
                throw new Exception('Invalid amount');
            }

            if (CryptoTransaction::where('idempotency_key', $key)->exists()) {
                throw new Exception('Duplicate withdrawal');
            }

            $user = User::where('id', $user->id)
                        ->lockForUpdate()
                        ->first();

            if ($user->crypto_balance < $amount) {
                throw new Exception('Insufficient funds');
            }

            $user->crypto_balance -= $amount;
            $user->save();

            return CryptoTransaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'status' => 'pending',
                'idempotency_key' => $key,
            ]);
        });
    }
}
