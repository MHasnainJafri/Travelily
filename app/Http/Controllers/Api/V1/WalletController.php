<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function getBalance()
    {
        $wallet = DB::table('wallets')
            ->where('holder_id', auth()->id())
            ->where('holder_type', 'App\\Models\\User')
            ->first();

        $transactions = DB::table('transactions')
            ->where('payable_id', auth()->id())
            ->where('payable_type', 'App\\Models\\User')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'balance' => $wallet->balance ?? 0,
                'currency' => 'USD',
                'recent_transactions' => $transactions
            ]
        ]);
    }
}
