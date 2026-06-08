<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksiDetail extends Model
{
    protected $table = 'transaksi_details';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'qty',
    ];

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class,'transaksi_id');
    }

    public function barang()
    {
        return $this->belongsTo(barang::class,'barang_id');
    }
}
