<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $account = $user->account;
        $transactions = $account->transactions();

        return response()->json([
            'balance' => $account->balance,
            'totalDeposits' => $user->account->transactions()->where('type', 'deposit')->sum('amount'),
            'totalTransfers' => $user->account->transactions()->where('type', 'transfer')->sum('amount'),
            'lastTransaction' => $user->account->transactions()->latest()->first(),
        ]);
    }

    public function getBalance(Request $request)
    {
        $balance = $request->user()->account->balance;
        return response()->json(['balance' => $balance]);
    }

    public function balance(Request $request)
    {
        $balance = $request->user()->account->balance;
        return response()->json(['balance' => $balance]);
    }
}
