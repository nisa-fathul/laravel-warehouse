<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'min_stok',
        'harga',
    ];

    public function stok()
    {
        return $this->hasOne(stok::class, 'barang_id');
    }

    public function hargaFormat(string $currency = 'SGD'): string
    {
        return match (strtoupper($currency)) {
            'IDR' => 'Rp ' . number_format($this->harga, 0, ',', '.'),
            'SGD' => 'SGD ' . number_format($this->harga, 2, '.', ','),
            default => 'SGD ' . number_format($this->harga, 2, '.', ','),
        };
    }

    public function transaksiDetails()
    {
        return $this->hasMany(transaksiDetail::class, 'barang_id');
    }
}
