<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'saldo'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
