<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuthController;


Route::middleware('guest')->group(function () {
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister']);

// Route Form OTP (Wajib ada ->name('otp.verify'))
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'processResetPassword'])->name('password.update');

Route::get('/forgot-password/verify-otp', [AuthController::class, 'showResetOtpForm'])->name('password.otp.form');
Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyResetOtp'])->name('password.otp.verify');
});


Route::middleware('auth')->group(function () {
Route::get('/dompet', [WalletController::class, 'index']);
Route::post('/dompet/transaksi', [WalletController::class, 'store']);
Route::post('dompet/transfer', [WalletController::class, 'transfer'])->name('transfer');
Route::post('/dompet/set-pin', [WalletController::class, 'setPin'])->name('set.pin');

Route::get('/dompet/cek-penerima/{account_number}', [WalletController::class, 'cekPenerima'])->name('cek.penerima');
Route::get('dompet/struk/{kode_transaksi}', [WalletController::class, 'cetakStruk'])->name('cetak.struk');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
