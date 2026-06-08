<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stok extends Model
{
    use HasFactory;
    protected $table = 'stoks';

    protected $fillable = [
        'barang_id',
        'qty',
    ];

    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
}
