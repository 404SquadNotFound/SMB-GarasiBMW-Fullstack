<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::with(['category', 'supplier', 'carType']);

        // 1. Search (nama, item_code, category)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('item_code', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Filter Supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        return $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
    }

    // Ambil pilihan filter unik (cascading)
    public function getFilterOptions(Request $request)
    {
        // 1. Ambil kategori (tergantung query supplier)
        $catQuery = Sparepart::whereNotNull('category')->where('category', '!=', '');
        if ($request->filled('supplier_id')) {
            $catQuery->where('supplier_id', $request->supplier_id);
        }
        $categories = $catQuery->distinct()->orderBy('category', 'asc')->pluck('category');

        // 2. Ambil supplier (tergantung query category)
        $supQuery = Sparepart::with('supplier')->whereNotNull('supplier_id');
        if ($request->filled('category')) {
            $supQuery->where('category', $request->category);
        }
        $suppliers = $supQuery->distinct('supplier_id')->get()->pluck('supplier.name', 'supplier_id')->filter();

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'suppliers' => $suppliers,
            ]
        ]);
    }

    public function show($id)
    {
        $sparepart = Sparepart::with(['category', 'supplier', 'carType'])->find($id);

        if (!$sparepart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data suku cadang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $sparepart
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_category_id' => 'required|exists:item_categories,category_id',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'car_type_id' => 'nullable|exists:car_types,car_type_id',
            'item_code' => 'required|string|unique:spareparts,item_code',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'cost_off_sell' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
            'date' => 'required|date',
        ]);

        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $sparepart = Sparepart::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Suku cadang berhasil ditambahkan ke inventaris!',
            'data' => $sparepart
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'item_category_id' => 'required|exists:item_categories,category_id',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'car_type_id' => 'nullable|exists:car_types,car_type_id',
            'name' => 'required|string|max:255',
            'cost_off_sell' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
        ]);

        $validated['updated_by'] = $request->user()->employees_id ?? 1;

        $sparepart->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data suku cadang berhasil diupdate!',
            'data' => $sparepart
        ], 200);
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Suku cadang berhasil dihapus!',
        ], 200);
    }

    public function lowStock()
    {
        $spareparts = Sparepart::where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->limit(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $spareparts
        ]);
    }

    public function exportExcel(ExportService $exportService)
    {
        $query = Sparepart::with(['category', 'supplier', 'carType']);
        $headers = ['Kode Barang', 'Nama Suku Cadang', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok'];

        $mapRow = function ($item) {
            $catName = is_object($item->category) ? ($item->category->name ?? '-') : ($item->category ?? '-');
            return [
                $item->item_code,
                $item->name,
                $catName,
                $item->cost_off_sell,
                $item->selling_price,
                $item->quantity,
            ];
        };

        return $exportService->exportToExcel('Data_Suku_Cadang.xlsx', $headers, $query, $mapRow);
    }

    public function exportPdf(PdfExportService $pdfExportService)
    {
        $query = Sparepart::with(['category', 'supplier', 'carType']);
        
        $mapRow = function ($item) {
            $catName = is_object($item->category) ? ($item->category->name ?? '-') : ($item->category ?? '-');
            return [
                'Kode Barang' => $item->item_code,
                'Nama Suku Cadang' => $item->name,
                'Kategori' => $catName,
                'Harga Beli' => 'Rp ' . number_format($item->cost_off_sell, 0, ',', '.'),
                'Harga Jual' => 'Rp ' . number_format($item->selling_price, 0, ',', '.'),
                'Stok' => $item->quantity,
            ];
        };

        return $pdfExportService->export(
            'Laporan_Suku_Cadang_' . date('Ymd_His') . '.pdf',
            $query,
            $mapRow,
            [
                'title' => 'Laporan Data Suku Cadang',
                'paper' => 'a4',
                'orientation' => 'portrait'
            ]
        );
    }
}
