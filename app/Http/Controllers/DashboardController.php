<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = barang::selectRaw("
            COUNT(barangs.id) as total_active,
            SUM(barangs.harga * stoks.qty) as total_inventory_value,
            SUM(CASE WHEN stoks.qty < barangs.min_stok THEN 1 ELSE 0 END) as total_critical_stock,
            SUM(CASE WHEN stoks.qty <= (barangs.min_stok + 10) THEN 1 ELSE 0 END) as total_low_stock
        ")
        ->join('stoks', 'barangs.id', '=', 'stoks.barang_id')
        ->first();

        return view('pages.dashboard', [
            'summary' => $summary
        ]);
    }
}
