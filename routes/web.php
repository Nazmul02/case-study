<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('api/register', [AuthController::class, 'register']);
Route::post('api/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('api/dashboard', [AccountController::class, 'dashboard']);
    Route::post('api/logout', [AuthController::class, 'logout']);
    Route::get('api/user', [AuthController::class, 'user']);

    Route::get('api/account/balance', [AccountController::class, 'getBalance']);

    Route::post('api/deposit', [TransactionController::class, 'deposit']);
    Route::post('api/transfer', [TransactionController::class, 'transfer']);
    Route::get('api/transactions', [TransactionController::class, 'getTransactions']);


});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


