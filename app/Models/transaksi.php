<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'nama_customer',
        'tanggal_transaksi',
        'jenis_transaksi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(transaksiDetail::class,'transaksi_id');
    }
}
