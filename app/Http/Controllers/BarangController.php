<?php

namespace App\Http\Controllers;

use App\Models\barang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = barang::with('stok')->latest()->get();
        return view('pages.inventory', [
            'dataBarang' => $barangs,
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'min_stok' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $barang = barang::create([
                    'kode_barang' => $validated['kode_barang'],
                    'nama_barang' => $validated['nama_barang'],
                    'satuan' => $validated['satuan'],
                    'min_stok' => $validated['min_stok'],
                    'harga' => $validated['harga'],
                ]);

                $barang->stok()->create([
                    'qty' => $validated['stok'],
                ]);
            });

            return redirect()
                ->route('inventory.index')
                ->with([
                    'notifikasi' => 'Barang berhasil ditambahkan!',
                    'type' => 'success',
                ]);

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'notifikasi' => 'Barang gagal ditambahkan!',
                    'type' => 'danger',
                ]);
        }
    }
}
