<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\transaksiDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        $idBarang = $request->input('item_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        if ($startDateCarbon->diffInMonths($endDateCarbon) > 3) {
            return redirect()
                ->back()
                ->with([
                    'notifikasi' => 'Forecast period cannot exceed 3 months.',
                    'type' => 'error',
                ]);
        }

        $dataForecast = collect();

        if ($startDate && $endDate && $idBarang) {
            $selectedBarang = barang::with('stok')
                ->findOrFail($idBarang);

            /*
            Ambil histori 3 hari sebelum start date
            agar forecast hari pertama bisa dihitung
            */
            $historyStart = $startDateCarbon->copy()->subDays(3);

            $sales = transaksiDetail::query()
                ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
                ->where('transaksis.jenis_transaksi', 1)
                ->where('transaksi_details.barang_id', $idBarang)
                ->whereBetween(
                    'transaksis.tanggal_transaksi',
                    [
                        $historyStart->startOfDay(),
                        $endDateCarbon->copy()->endOfDay()
                    ]
                )
                ->selectRaw("
                    DATE(transaksis.tanggal_transaksi) as tanggal,
                    SUM(transaksi_details.qty) as qty
                ")
                ->groupByRaw("DATE(transaksis.tanggal_transaksi), qty")
                ->pluck('qty', 'tanggal');

            $period = CarbonPeriod::create($startDateCarbon, $endDateCarbon);

            foreach ($period as $date) {

                $forecastDate = $date->format('Y-m-d');

                $actual = $sales[$forecastDate] ?? 0;

                $previous1 = $date->copy()->subDay()->format('Y-m-d');
                $previous2 = $date->copy()->subDays(2)->format('Y-m-d');
                $previous3 = $date->copy()->subDays(3)->format('Y-m-d');

                $qty1 = $sales[$previous1] ?? 0;
                $qty2 = $sales[$previous2] ?? 0;
                $qty3 = $sales[$previous3] ?? 0;

                // $movingAverage = ($qty1 + $qty2 + $qty3) / 3;

                $forecast = round(
                    ($qty1 + $qty2 + $qty3) / 3,
                    2
                );
                $mape = 0;

                if ($actual > 0) {
                    $mape = abs(($actual - $forecast) / $actual) * 100;
                }

                $dataForecast->push([
                    'periode' => $forecastDate,
                    'nama_barang' => $selectedBarang->nama_barang,
                    'bulan' => $date->translatedFormat('F Y'),
                    'total_penjualan_aktual' => $actual,
                    // 'ma' => round(num: $movingAverage, 2),
                    'forecast' => $forecast,
                    'mape' => round($mape, 2),
                    'stok_saat_ini' => $selectedBarang->stok->qty,
                ]);
            }

            $chartData = $dataForecast->map(function ($item) {
                return [
                    'date' => $item['periode'],
                    'sales_actual' => $item['total_penjualan_aktual'],
                    'forecast' => $item['forecast'],
                ];
            });
        }

        $barang = barang::orderBy('kode_barang', 'ASC')->get();

        return view('pages.forecast', [
            'dataBarang' => $barang,
            'dataForecast' => $dataForecast,
            'chartData' => $chartData ?? collect(),
        ]);
    }
}
