<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['wallet_id', 'jenis', 'jumlah', 'keterangan', 'kode_transaksi'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
