<?php

namespace App\Http\Controllers;


use App\Models\barang;
use App\Models\stok;
use App\Models\transaksi;
use App\Models\transaksiDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected array $action;
    protected array $type_transaksi;

    public function __construct()
    {
        $this->action = ['detail', 'update'];
        $this->type_transaksi = [
            'in' => 0,
            'out' => 1
        ];
    }

    private function generateTransactionCode(string $type): string
    {
        return $type . '-' . now()->format('Ymd') . '-' . str_pad(
            transaksi::whereDate('created_at', today())->count() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    public function in(string $type)
    {
        if (!isset($this->type_transaksi[$type])) {
            return redirect()->back()->with([
                'notifikasi' => 'Invalid Transaction Type',
                'type' => 'error',
            ]);
        }

        $card = transaksi::selectRaw('count(detail.id) as total_received, sum((detail.qty * barangs.harga)) as total_value')
            ->join('transaksi_details as detail', 'transaksis.id', '=', 'detail.transaksi_id')
            ->join('barangs', 'detail.barang_id', '=', 'barangs.id')
            ->where('jenis_transaksi', $this->type_transaksi[$type])
            ->first();

        $barang = barang::orderBy('kode_barang', 'ASC')->get();

        $transaksi = transaksi::where('jenis_transaksi', $this->type_transaksi[$type])
            ->orderBy('kode_transaksi', 'DESC')
            ->get();

        return view('pages.delivery', [
            'dataBarang' => $barang,
            'dataCard' => $card,
            'dataTransaksi' => $transaksi,
            'type' => $type,
        ]);
    }

    public function detail(string $type, string $action = 'detail', int $id)
    {
        if (!in_array($action, $this->action)) {
            return redirect()->back()->with([
                'type' => 'error',
                'notifikasi' => 'Invalid Action',
            ]);
        }

        if (!isset($this->type_transaksi[$type])) {
            return redirect()->back()->with([
                'notifikasi' => 'Invalid Transaction Type',
                'type' => 'error',
            ]);
        }

        $transaksi = transaksi::with([
            'user',
            'details.barang.stok',
        ])->where('jenis_transaksi', $this->type_transaksi[$type])->findOrFail($id);

        if(empty($transaksi)) {
            return redirect()->back()->with([
                'type' => 'error',
                'notifikasi' => 'Transaction details not found',
            ]);
        }

        return view('pages.delivery_detail', [
            'dataTransaksi' => $transaksi,
            'action' => $action,
            'type' => $type,
        ]);
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => [
                'required',
                'date',
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'item_id' => [
                'required',
                'array',
                'min:1',
            ],

            'item_id.*' => [
                'required',
                'exists:barangs,id',
            ],

            'qty' => [
                'required',
                'array',
                'min:1',
            ],

            'qty.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $transaksi = transaksi::create([
                    'kode_transaksi' => $this->generateTransactionCode('IN'),
                    'user_id' => Auth::id(),
                    'nama_customer' => $validated['supplier_name'],
                    'tanggal_transaksi' => $validated['transaction_date'],
                    'jenis_transaksi' => 0,
                    'keterangan' => $validated['notes'] ?? null,
                ]);

                $detailData = [];
                $stokUpdates = [];

                foreach ($validated['item_id'] as $index => $barangId) {

                    $qty = $validated['qty'][$index];

                    $detailData[] = [
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $barangId,
                        'qty' => $qty,
                    ];

                    $stokUpdates[$barangId] = ($stokUpdates[$barangId] ?? 0) + $qty;
                }

                transaksiDetail::insert($detailData);

                foreach ($stokUpdates as $barangId => $qty) {
                    stok::where('barang_id', $barangId)
                        ->increment('qty', $qty);
                }
            });

            return redirect()
                ->route('transaction.index' , parameters: ['type' => 'in'])
                ->with([
                    'notifikasi' => 'Receipt successfully created!',
                    'type' => 'success',
                ]);
        } catch (Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'notifikasi' => 'Failed to create receipt!',
                    'type' => 'danger',
                ]);
        }
    }

    public function updateIn(Request $request, int $id_transaksi)
    {
        $validated = $request->validate([
            'transaction_date' => [
                'required',
                'date',
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'qty' => [
                'required',
                'array',
                'min:1',
            ],

            'qty.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        try {

            DB::transaction(function () use ($request, $validated, $id_transaksi) {

                $transaksi = transaksi::where(
                    'jenis_transaksi',
                    0
                )->findOrFail($id_transaksi);

                $transaksi->update([
                    'tanggal_transaksi' => $validated['transaction_date'],
                    'nama_customer' => $validated['supplier_name'],
                    'keterangan' => $validated['notes'] ?? null,
                ]);

                // Ambil seluruh detail sekaligus
                $details = $transaksi->details->keyBy('id');

                // Ambil seluruh stok sekaligus
                $stoks = stok::whereIn(
                    'barang_id',
                    $details->pluck('barang_id')
                )->get()->keyBy('barang_id');

                foreach ($request->detail_id as $index => $detailId) {

                    $detail = $details[$detailId];

                    $oldQty = $detail->qty;
                    $newQty = $validated['qty'][$index];

                    if ($oldQty == $newQty) {
                        continue;
                    }

                    $differenceQty = $newQty - $oldQty;

                    $detail->update([
                        'qty' => $newQty,
                    ]);

                    $stok = $stoks[$detail->barang_id];

                    $stok->increment(
                        'qty',
                        $differenceQty
                    );
                }

            });

            return redirect()
                ->back()
                ->with([
                    'notifikasi' => 'Transaction successfully updated!',
                    'type' => 'success',
                ]);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'notifikasi' => 'Failed to update receipt!',
                    'type' => 'danger',
                ]);
        }
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => [
                'required',
                'date',
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'item_id' => [
                'required',
                'array',
                'min:1',
            ],

            'item_id.*' => [
                'required',
                'exists:barangs,id',
            ],

            'qty' => [
                'required',
                'array',
                'min:1',
            ],

            'qty.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $transaksi = transaksi::create([
                    'kode_transaksi' => $this->generateTransactionCode('OUT'),
                    'user_id' => Auth::id(),
                    'nama_customer' => $validated['supplier_name'],
                    'tanggal_transaksi' => $validated['transaction_date'],
                    'jenis_transaksi' => 1,
                    'keterangan' => $validated['notes'] ?? null,
                ]);

                $detailData = [];
                $stokUpdates = [];

                foreach ($validated['item_id'] as $index => $barangId) {

                    $qty = $validated['qty'][$index];

                    $detailData[] = [
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $barangId,
                        'qty' => $qty,
                    ];

                    $stokUpdates[$barangId] = ($stokUpdates[$barangId] ?? 0) + $qty;
                }

                transaksiDetail::insert($detailData);

                foreach ($stokUpdates as $barangId => $qty) {
                    stok::where('barang_id', $barangId)
                        ->decrement('qty', $qty);

                }
            });

            return redirect()
                ->route('transaction.index' , ['type' => 'out'])
                ->with([
                    'notifikasi' => 'Receipt successfully created!',
                    'type' => 'success',
                ]);

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'notifikasi' => 'Failed to create receipt!',
                    'type' => 'danger',
                ]);
        }
    }

    public function updateOut(Request $request, int $id_transaksi)
    {
        $validated = $request->validate([
            'transaction_date' => [
                'required',
                'date',
            ],

            'supplier_name' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'qty' => [
                'required',
                'array',
                'min:1',
            ],

            'qty.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        try {

            DB::transaction(function () use ($request, $validated, $id_transaksi) {

                $transaksi = transaksi::where(
                    'jenis_transaksi',
                    1
                )->findOrFail($id_transaksi);

                $transaksi->update([
                    'tanggal_transaksi' => $validated['transaction_date'],
                    'nama_customer' => $validated['supplier_name'],
                    'keterangan' => $validated['notes'] ?? null,
                ]);

                // Ambil seluruh detail sekaligus
                $details = $transaksi->details->keyBy('id');

                // Ambil seluruh stok sekaligus
                $stoks = stok::whereIn(
                    'barang_id',
                    $details->pluck('barang_id')
                )->get()->keyBy('barang_id');

                foreach ($request->detail_id as $index => $detailId) {

                    $detail = $details[$detailId];

                    $oldQty = $detail->qty;
                    $newQty = $validated['qty'][$index];

                    if ($oldQty == $newQty) {
                        continue;
                    }

                    $differenceQty = $newQty - $oldQty;

                    $detail->update([
                        'qty' => $newQty,
                    ]);

                    $stok = $stoks[$detail->barang_id];

                    $stok->decrement(
                        'qty',
                        $differenceQty
                    );
                }

            });

            return redirect()
                ->back()
                ->with([
                    'notifikasi' => 'Transaction successfully updated!',
                    'type' => 'success',
                ]);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'notifikasi' => 'Failed to update receipt!',
                    'type' => 'danger',
                ]);
        }
    }
}
