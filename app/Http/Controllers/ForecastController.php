<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\transaksiDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    // public function index(Request $request)
    // {
    //     $idBarang = $request->input('item_id');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $startDateCarbon = Carbon::parse($startDate);
    //     $endDateCarbon = Carbon::parse($endDate);

    //     if ($startDateCarbon->diffInMonths($endDateCarbon) > 3) {
    //         return redirect()
    //             ->back()
    //             ->with([
    //                 'notifikasi' => 'Forecast period cannot exceed 3 months.',
    //                 'type' => 'error',
    //             ]);
    //     }

    //     $dataForecast = collect();

    //     if ($startDate && $endDate && $idBarang) {
    //         $selectedBarang = barang::with('stok')
    //             ->findOrFail($idBarang);

    //         /*
    //         Ambil histori 3 hari sebelum start date
    //         agar forecast hari pertama bisa dihitung
    //         */
    //         $historyStart = $startDateCarbon->copy()->subDays(5);

    //         $sales = transaksiDetail::query()
    //             ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
    //             ->where('transaksis.jenis_transaksi', 1)
    //             ->where('transaksi_details.barang_id', $idBarang)
    //             ->where('transaksis.tanggal_transaksi', '<=', $endDateCarbon->copy()->endOfDay())
    //             ->selectRaw("
    //                 DATE(transaksis.tanggal_transaksi) as tanggal,
    //                 SUM(transaksi_details.qty) as qty
    //             ")
    //             ->groupByRaw("DATE(transaksis.tanggal_transaksi)")
    //             ->orderBy('tanggal')
    //             ->get();

    //         $salesByDate = $sales->keyBy('tanggal');

    //         $period = CarbonPeriod::create($startDateCarbon, $endDateCarbon);

    //         $historicalData = $sales
    //             ->where('tanggal', '<', $startDateCarbon->format('Y-m-d'))
    //             ->pluck('qty')
    //             ->values()
    //             ->toArray();

    //         foreach ($period as $date) {

    //             $forecastDate = $date->format('Y-m-d');

    //             $actual = $salesByDate[$forecastDate]->qty ?? 0;

    //             $lastFive = collect($historicalData)
    //                 ->take(-5);

    //             $forecast = $lastFive->count()
    //                 ? round($lastFive->avg(), 2)
    //                 : 0;

    //             $mape = 0;

    //             if ($actual > 0) {
    //                 $mape = abs(($actual - $forecast) / $actual) * 100;
    //             }

    //             $dataForecast->push([
    //                 'periode' => $forecastDate,
    //                 'nama_barang' => $selectedBarang->nama_barang,
    //                 'bulan' => $date->translatedFormat('F Y'),
    //                 'total_penjualan_aktual' => $actual,
    //                 'forecast' => $forecast,
    //                 'mape' => round($mape, 2),
    //                 'stok_saat_ini' => $selectedBarang->stok->qty,
    //             ]);

    //             /*
    //              * Jika ada actual gunakan actual
    //              * Jika tidak ada actual gunakan forecast
    //              * supaya forecast berikutnya tetap bisa dihitung
    //              */

    //             $historicalData[] = $actual > 0
    //                 ? $actual
    //                 : $forecast;
    //         }

    //         $chartData = $dataForecast->map(function ($item) {
    //             return [
    //                 'date' => $item['periode'],
    //                 'sales_actual' => $item['total_penjualan_aktual'],
    //                 'forecast' => $item['forecast'],
    //             ];
    //         });
    //     }

    //     $barang = barang::orderBy('kode_barang', 'ASC')->get();

    //     return view('pages.forecast', [
    //         'dataBarang' => $barang,
    //         'dataForecast' => $dataForecast,
    //         'chartData' => $chartData ?? collect(),
    //     ]);
    // }

    public function index(Request $request)
    {
        $idBarang = $request->input('item_id');
        $year = $request->input('year', now()->year);

        $dataForecast = collect();
        $chartData = collect();

        if ($idBarang) {
            $selectedBarang = barang::with('stok')->findOrFail($idBarang);

            /*
            |--------------------------------------------------------------------------
            | Ambil data mulai 5 bulan sebelum Januari tahun terpilih
            |--------------------------------------------------------------------------
            */
            $startForecast = Carbon::create($year, 1, 1);
            $historyStart = $startForecast->copy()->subMonths(5)->startOfMonth();
            $historyEnd = Carbon::create($year, 12, 31)->endOfMonth();

            $sales = transaksiDetail::query()
                ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
                ->where('transaksis.jenis_transaksi', 1)
                ->where('transaksi_details.barang_id', $idBarang)
                ->whereBetween('transaksis.tanggal_transaksi', [$historyStart, $historyEnd])
                ->selectRaw("
                    YEAR(transaksis.tanggal_transaksi) as tahun,
                    MONTH(transaksis.tanggal_transaksi) as bulan,
                    SUM(transaksi_details.qty) as qty
                ")
                ->groupByRaw("
                    YEAR(transaksis.tanggal_transaksi),
                    MONTH(transaksis.tanggal_transaksi)
                ")
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Ubah hasil query jadi format key YYYY-MM => qty
            |--------------------------------------------------------------------------
            */
            $salesByMonth = $sales->mapWithKeys(function ($item) {
                $key = $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                return [$key => $item->qty];
            });

            /*
            |--------------------------------------------------------------------------
            | Histori awal = 5 bulan sebelum Januari tahun terpilih
            |--------------------------------------------------------------------------
            */
            $historicalData = [];

            for ($i = 5; $i >= 1; $i--) {
                $month = $startForecast->copy()->subMonths($i);
                $monthKey = $month->format('Y-m');

                $historicalData[] = $salesByMonth[$monthKey] ?? 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Loop Januari - Desember pada tahun terpilih
            |--------------------------------------------------------------------------
            */
            for ($month = 1; $month <= 12; $month++) {
                $currentMonth = Carbon::create($year, $month, 1);
                $monthKey = $currentMonth->format('Y-m');

                $actual = $salesByMonth[$monthKey] ?? 0;

                /*
                Forecast = rata-rata 5 histori terakhir
                */
                $lastFive = collect($historicalData)->take(-5);

                $forecast = round($lastFive->avg(), 2);

                $mape = 0;
                if ($actual > 0) {
                    $mape = abs(($actual - $forecast) / $actual) * 100;
                }

                $dataForecast->push([
                    'periode' => $monthKey,
                    'nama_barang' => $selectedBarang->nama_barang,
                    'bulan' => $currentMonth->translatedFormat('F'),
                    'tahun' => $year,
                    'total_penjualan_aktual' => $actual,
                    'forecast' => $forecast,
                    'mape' => round($mape, 2),
                    'stok_saat_ini' => $selectedBarang->stok->qty,
                ]);

                /*
                Tambahkan actual jika ada,
                kalau tidak ada pakai forecast
                supaya forecast bulan berikutnya tetap bisa dihitung
                */
                $historicalData[] = $actual > 0 ? $actual : $forecast;
            }

            $chartData = $dataForecast->map(function ($item) {
                return [
                    'date' => $item['bulan'],
                    'sales_actual' => $item['total_penjualan_aktual'],
                    'forecast' => $item['forecast'],
                ];
            });
        }

        $barang = barang::orderBy('kode_barang', 'ASC')->get();

        return view('pages.forecast', [
            'dataBarang' => $barang,
            'dataForecast' => $dataForecast,
            'chartData' => $chartData,
            'currentYear' => now()->year,
            'selectedYear' => $year,
        ]);
    }
}
