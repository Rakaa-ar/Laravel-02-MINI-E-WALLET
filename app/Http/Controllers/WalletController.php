<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        // Auto-generate 6 digit ID User jika belum punya
        if (!$user->account_number) {
            do {
                $code = rand(100000, 999999);
            } while (User::where('account_number', $code)->exists());

            User::where('id', $user->id)->update([
                'account_number' => $code
            ]);

            $user->account_number = $code;
        }

        $dompet = Wallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['saldo' => 0]
        );

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

        $dompet = Wallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['saldo' => 0]
        );

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

    public function transfer(Request $request)
    {
        // 1. Validasi Input (Wajib 6 digit angka & harus ada di database)
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|numeric|digits:6|exists:users,account_number',
            'jumlah'         => 'required|numeric|min:1000',
            'keterangan'     => 'nullable|string|max:255',
        ], [
            'account_number.required' => 'ID User tujuan wajib diisi.',
            'account_number.digits'   => 'ID User harus 6 digit angka.',
            'account_number.exists'   => 'ID User penerima tidak ditemukan!',
            'jumlah.min'              => 'Nominal transfer minimal Rp 1.000.',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $pengirimUser   = Auth::user();
        $pengirimWallet = Wallet::where('user_id', $pengirimUser->id)->first();

        //Cegah transfer ke ID sendiri
        if ((string)$pengirimUser->account_number === (string)$request->account_number) {
            return back()->with('error', 'engga bisa transfer ke ID User sendiri!');
        }

        //Cari User & Wallet Penerima berdasarkan 6 Digit ID
        $penerimaUser   = User::where('account_number', $request->account_number)->first();
        $penerimaWallet = Wallet::firstOrCreate(
            ['user_id' => $penerimaUser->id],
            ['saldo'   => 0]
        );

        //Cek Kecukupan Saldo ke Go Microservice
        try {
            $response = Http::timeout(5)->post('http://localhost:8080/api/transaksi', [
                'saldo_saat_ini' => (float) $pengirimWallet->saldo,
                'jenis'          => 'keluar',
                'jumlah'         => (float) $request->jumlah
            ]);

            $result = $response->json();

            if (!$result['status']) {
                return back()->with('error', 'Transfer gagal: ' . $result['pesan']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke Go Microservice gagal!');
        }

        //Transaksi DB Atomik
        try {
            DB::transaction(function () use ($pengirimWallet, $penerimaWallet, $pengirimUser, $penerimaUser, $request, $result) {
                // Potong Saldo Pengirim
                $pengirimWallet->saldo = $result['sisa_saldo'];
                $pengirimWallet->save();

                // Catat Riwayat Pengirim
                $pengirimWallet->transaksi()->create([
                    'jenis'      => 'keluar',
                    'jumlah'     => $request->jumlah,
                    'keterangan' => 'Transfer ke ' . $penerimaUser->name . ($request->keterangan ? ' - ' . $request->keterangan : '')
                ]);

                // Tambah Saldo Penerima
                $penerimaWallet->saldo += $request->jumlah;
                $penerimaWallet->save();

                // Catat Riwayat Penerima
                $penerimaWallet->transaksi()->create([
                    'jenis'      => 'masuk',
                    'jumlah'     => $request->jumlah,
                    'keterangan' => 'Transfer dari ' . $pengirimUser->name . ($request->keterangan ? ' - ' . $request->keterangan : '')
                ]);
            });

            return redirect('/dompet')->with('success', 'Transfer Rp ' . number_format($request->jumlah, 0, ',', '.') . ' ke ' . $penerimaUser->name . ' (' . $penerimaUser->account_number . ') berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses transaksi database.');
        }
    }
}
