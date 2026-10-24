<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index()
    {
        $dompet = Wallet::first();

        $riwayat = $dompet->transaksi()->latest()->get();

        return view('wallet.index', compact('dompet', 'riwayat'));
    }
}
