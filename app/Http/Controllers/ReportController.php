<?php

namespace App\Http\Controllers;

use App\Models\transaksi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input(
            'start_date',
            now()->format('Y-m-d')
        );

        $endDate = $request->input(
            'end_date',
            now()->addDays(6)->format('Y-m-d')
        );

        $year = $request->input(
            'years',
            now()->year
        );

        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        if ($startDateCarbon->diffInDays($endDateCarbon) > 365) {
            return redirect()
                ->back()
                ->with([
                    'type' => 'error',
                    'notifikasi' => 'Maximum report period is 365 days.',
                ]);
        }

        $dailyDataRaw = transaksi::query()
            ->join('transaksi_details', 'transaksis.id', '=', 'transaksi_details.transaksi_id')
            ->join('barangs', 'transaksi_details.barang_id', '=', 'barangs.id')
            ->where('jenis_transaksi', 1)
            ->whereBetween('tanggal_transaksi', [
                $startDateCarbon->startOfDay(),
                $endDateCarbon->endOfDay(),
            ])
            ->selectRaw("
                DATE(tanggal_transaksi) as period,
                SUM(transaksi_details.qty) as delivery_qty,
                SUM(transaksi_details.qty * barangs.harga) as total_revenue
            ")
            ->groupByRaw("DATE(tanggal_transaksi)")
            ->get();

        $dailyDataArr = collect();
        foreach ($dailyDataRaw as $data) {
            $dailyDataArr[$data->period] = $data;
        }

        $period = CarbonPeriod::create(
            $startDateCarbon,
            $endDateCarbon
        );

        $dailyData = collect();
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            $data = $dailyDataArr[$dateString] ?? 0;

            $dailyData->push([
                'period' => $dateString,
                'month' => $date->translatedFormat('F'),
                'year' => $date->year,
                'sales_actual' => $data->total_revenue ?? 0,
                'delivery_qty' => $data->delivery_qty ?? 0,
            ]);
        }


        $monthlyDataRaw = transaksi::query()
            ->join('transaksi_details', 'transaksis.id', '=', 'transaksi_details.transaksi_id')
            ->join('barangs', 'transaksi_details.barang_id', '=', 'barangs.id')
            ->where('jenis_transaksi', 1)
            ->whereYear('transaksis.tanggal_transaksi', $year)
            ->selectRaw("
                MONTH(tanggal_transaksi) as period,
                SUM(transaksi_details.qty) as delivery_qty,
                SUM(transaksi_details.qty * barangs.harga) as total_revenue
            ")
            ->groupByRaw("MONTH(tanggal_transaksi)")
            ->get();

        $monthlyDataArr = collect();
        foreach ($monthlyDataRaw as $data) {
            $monthlyDataArr[$data->period] = $data;
        }

        $monthlyData = collect();
        for ($month = 1; $month <= 12; $month++) {

            $data = $monthlyDataArr[$month] ?? 0;

            $monthlyData->push([
                'period' => Carbon::create()->month($month)->translatedFormat('F'),
                'year' => $year,
                'sales_actual' => $data->total_revenue ?? 0,
                'delivery_qty' => $data->delivery_qty ?? 0,
            ]);
        }

        $dailyChart = [
            'period' => $dailyData->pluck('period'),
            'sales_actual' => $dailyData->pluck('sales_actual'),
            'delivery_qty' => $dailyData->pluck('delivery_qty'),
        ];

        $monthlyChart = [
            'period' => $monthlyData->pluck('period'),
            'sales_actual' => $monthlyData->pluck('sales_actual'),
            'delivery_qty' => $monthlyData->pluck('delivery_qty'),
        ];

        return view('pages.report', [
            'dailyData' => $dailyData,
            'monthlyData' => $monthlyData,
            'dailyChart' => $dailyChart,
            'monthlyChart' => $monthlyChart,
        ]);
    }
}
