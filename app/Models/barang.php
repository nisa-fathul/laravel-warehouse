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
        'kategori',
    ];

    public function stok()
    {
        return $this->hasOne(stok::class, 'barang_id');
    }
}
