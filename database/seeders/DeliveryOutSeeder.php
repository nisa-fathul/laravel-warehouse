<?php

namespace Database\Seeders;

use App\Models\barang;
use App\Models\transaksi;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = array_map(
            'str_getcsv',
            file(database_path('data/delivery_out.csv'))
        );

        array_shift($csv);

        DB::transaction(function () use ($csv) {
            $groups = collect($csv)
                ->groupBy(function ($row) {
                    return trim($row[0]) . '|' . trim($row[1]);
                });

            foreach ($groups as $groupKey => $rows) {
                [$transactionDate, $customer] = explode('|', $groupKey);
                $transaksi = transaksi::create([
                    'kode_transaksi' => 'OUT-' . now()->format('YmdHis') . uniqid(),
                    'user_id' => 1,
                    'nama_customer' => $customer,
                    'tanggal_transaksi' => Carbon::createFromFormat(
                        'd.m.Y',
                        trim($transactionDate)
                    ),
                    'jenis_transaksi' => 1,
                    'keterangan' => null,
                ]);

                foreach ($rows as $row) {
                    $itemName = trim($row[3]);
                    $unit = trim($row[4]);
                    $qty = (int) trim($row[6]);

                    $barang = barang::firstOrCreate(
                        [
                            'nama_barang' => $itemName,
                        ],
                        [
                            'kode_barang' => 'AUTO-' . strtoupper(
                                str_pad(
                                    barang::count() + 1,
                                    5,
                                    '0',
                                    STR_PAD_LEFT
                                )
                            ),
                            'satuan' => $unit ?: 'pcs',
                            'min_stok' => 1000,
                            'harga' => 0.3,
                        ]
                    );

                    if (!$barang->stok()->exists()) {

                        $barang->stok()->create([
                            'qty' => 1500000,
                        ]);
                    }

                    $transaksi->details()->create([
                        'barang_id' => $barang->id,
                        'qty' => $qty,
                    ]);

                    $barang->stok()->decrement(
                        'qty',
                        $qty
                    );
                }
            }
        });
    }
}
