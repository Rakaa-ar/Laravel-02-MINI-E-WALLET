<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function index()
    {
        $dompet = Wallet::first();

        $riwayat = $dompet->transaksi()->latest()->paginate(5);

        return view('wallet.index', compact('dompet', 'riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string'
        ]);

        $dompet = Wallet::first();

        try {
            $response = Http::timeout(5)->post('http://localhost:8080/api/transaksi', [
                'saldo_saat_ini' => (float) $dompet->saldo,
                'jenis' => $request->jenis,
                'jumlah' => (float) $request->jumlah
            ]);

            $result = $response->json();

            if (!$result['status']) {
                return back()->with('error', $result['pesan']); 
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke Microservice Go gagal, pastikan server Go menyala!');
        }

        $dompet->transaksi()->create([
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        $dompet->saldo = $result['sisa_saldo'];
        $dompet->save();

        return redirect('/dompet')->with('success', 'Transaksi sukses diproses via Go Microservice!');
    }
}
