<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class forecast extends Model
{
    protected $table = 'forecasts';
    protected $fillable = [
        'barang_id',
        'periode',
        'nilai_forecast',
        'nilai_aktual',
    ];

    protected $casts = [
        'periode' => 'date'
    ];

    public function barang()
    {
        return $this->belongsTo(barang::class,'barang_id');
    }
}
