<?php

namespace App\Http\Controllers;

use App\Models\SparepartStock;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartStockController extends Controller
{
    /**
     * Ambil semua stok milik sebuah sparepart
     */
    public function index($sparepartId)
    {
        $stocks = SparepartStock::with('supplier')
            ->where('sparepart_id', $sparepartId)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $stocks,
        ]);
    }

    /**
     * Simpan satu entri stok baru untuk sebuah sparepart
     */
    public function store(Request $request, $sparepartId)
    {
        $sparepart = Sparepart::findOrFail($sparepartId);

        $validated = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,supplier_id',
            'cost_off_sell' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'date'          => 'required|date',
        ]);

        $validated['sparepart_id'] = $sparepart->sparepart_id;
        $validated['created_by']   = $request->user()->employees_id ?? 1;

        $stock = SparepartStock::create($validated);

        // Update agregat quantity di tabel spareparts
        $this->syncSparepartQuantity($sparepart);

        return response()->json([
            'status'  => 'success',
            'message' => 'Stok berhasil ditambahkan!',
            'data'    => $stock->load('supplier'),
        ], 201);
    }

    /**
     * Update satu entri stok
     */
    public function update(Request $request, $sparepartId, $stockId)
    {
        $stock = SparepartStock::where('sparepart_id', $sparepartId)
            ->where('stock_id', $stockId)
            ->firstOrFail();

        $validated = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,supplier_id',
            'cost_off_sell' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'date'          => 'required|date',
        ]);

        $validated['updated_by'] = $request->user()->employees_id ?? 1;
        $stock->update($validated);

        // Update agregat quantity di tabel spareparts
        $sparepart = Sparepart::find($sparepartId);
        if ($sparepart) $this->syncSparepartQuantity($sparepart);

        return response()->json([
            'status'  => 'success',
            'message' => 'Stok berhasil diperbarui!',
            'data'    => $stock->load('supplier'),
        ]);
    }

    /**
     * Hapus satu entri stok
     */
    public function destroy($sparepartId, $stockId)
    {
        $stock = SparepartStock::where('sparepart_id', $sparepartId)
            ->where('stock_id', $stockId)
            ->firstOrFail();

        $stock->delete();

        // Update agregat quantity di tabel spareparts
        $sparepart = Sparepart::find($sparepartId);
        if ($sparepart) $this->syncSparepartQuantity($sparepart);

        return response()->json([
            'status'  => 'success',
            'message' => 'Stok berhasil dihapus!',
        ]);
    }

    /**
     * Simpan banyak stok sekaligus (bulk) — dipakai saat create sparepart baru
     */
    public function bulkStore(Request $request, $sparepartId)
    {
        $sparepart = Sparepart::findOrFail($sparepartId);

        $request->validate([
            'stocks'                => 'required|array|min:1',
            'stocks.*.supplier_id'   => 'nullable|exists:suppliers,supplier_id',
            'stocks.*.cost_off_sell' => 'required|numeric|min:0',
            'stocks.*.selling_price' => 'required|numeric|min:0',
            'stocks.*.quantity'      => 'required|integer|min:0',
            'stocks.*.date'          => 'required|date',
        ]);

        $createdBy = $request->user()->employees_id ?? 1;
        $created = [];

        foreach ($request->stocks as $stockData) {
            $stockData['sparepart_id'] = $sparepart->sparepart_id;
            $stockData['created_by']   = $createdBy;
            $created[] = SparepartStock::create($stockData);
        }

        // Update agregat quantity
        $this->syncSparepartQuantity($sparepart);

        return response()->json([
            'status'  => 'success',
            'message' => count($created) . ' entri stok berhasil disimpan!',
            'data'    => $created,
        ], 201);
    }

    /**
     * Sinkronisasi total quantity di tabel spareparts
     */
    private function syncSparepartQuantity(Sparepart $sparepart): void
    {
        $total = SparepartStock::where('sparepart_id', $sparepart->sparepart_id)->sum('quantity');
        $sparepart->update(['quantity' => $total]);
    }
}
