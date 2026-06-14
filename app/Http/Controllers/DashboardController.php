<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\transaksi;
use App\Models\transaksiDetail;
use Carbon\Carbon;

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

        $transaksiOut = transaksi::query()
            ->select('id')
            ->where('jenis_transaksi', 1)
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year);

        $averageTransaction = transaksiDetail::query()
            ->joinSub($transaksiOut, 'trx', function ($join) {
                $join->on('transaksi_details.transaksi_id', '=', 'trx.id');
            })
            ->join('barangs', 'transaksi_details.barang_id', '=', 'barangs.id')
            ->selectRaw("
                COUNT(transaksi_details.id) as total_transaksi,
                SUM(transaksi_details.qty * barangs.harga) as total_value_out,
                SUM(transaksi_details.qty * barangs.harga) / COUNT(transaksi_details.id) as average_monthly
            ")
            ->first();

        $topBarang = barang::query()
            ->withSum([
                'transaksiDetails as total_terjual' => function ($query) {
                    $query->whereHas('transaksi', function ($q) {
                        $q->where('jenis_transaksi', 1);
                    });
                }
            ], 'qty')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('pages.dashboard', [
            'summary' => $summary,
            'average' => $averageTransaction,
            'topBarang' => $topBarang,
        ]);
    }
}
