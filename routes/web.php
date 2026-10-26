<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;

Route::get('/dompet', [WalletController::class, 'index']);
Route::post('/dompet/transaksi', [WalletController::class, 'store']);
