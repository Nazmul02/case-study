<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $request->user()->account->transactions()
            ->latest()
            ->paginate(10);

        return response()->json($transactions);
    }

    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $account = $request->user()->account;

        try {
            DB::transaction(function () use ($account, $request) {
                $account->balance += $request->amount;
                $account->save();

                Transaction::create([
                    'account_id' => $account->id,
                    'amount' => $request->amount,
                    'type' => 'deposit',
                ]);
            });

            return response()->json([
                'message' => 'Deposit successful',
                'balance' => $account->balance
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your deposit.'], 500);
        }
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'to_account_id' => 'required',
        ]);

        $fromAccount = $request->user()->account;

        $toAccount = Account::firstOrNew([
            'id' => $request->to_account_id,
            'user_id' => User::factory()->create()->id
        ]);

        if ($fromAccount->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient funds'], 400);
        }

        DB::transaction(function () use ($fromAccount, $toAccount, $request) {
            $fromAccount->balance -= $request->amount;
            $toAccount->balance += $request->amount;

            $fromAccount->save();
            $toAccount->save();

            Transaction::create([
                'account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'amount' => $request->amount,
                'type' => 'transfer',
            ]);
        });

        return response()->json(['message' => 'Transfer successful', 'balance' => $fromAccount->balance]);
    }

    public function getTransactions(Request $request)
    {
        $transactions = $request->user()->account->transactions()
            ->with('toAccount.user')
            ->latest()
            ->paginate(10);

        return response()->json($transactions);
    }
}
