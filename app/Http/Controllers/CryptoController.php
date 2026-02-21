<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptoBalanceService;
use Illuminate\Support\Str;
use App\Models\User;

class CryptoController extends Controller
{
     public function deposit(Request $request, CryptoBalanceService $service)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.00000001',
            'tx_hash' => 'required|string',
        ]);
        $user = User::first(); 
        return $service->deposit(
            // auth()->user(),
            Str::uuid(),
            $user,
            $request->amount,
            $request->tx_hash
        );
    }

    public function withdraw(Request $request, CryptoBalanceService $service)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.00000001',
        ]);
        $user = User::first(); 
        return $service->withdraw(
            // auth()->user(),
            Str::uuid(),
            $request->amount
        );
    
}
}