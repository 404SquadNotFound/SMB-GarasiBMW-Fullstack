<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemCategory::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);

        return response()->json($categories, 200);
    }

    public function show($id)
    {
        $category = ItemCategory::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kategori berhasil ditarik',
            'data' => $category
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'descriptions' => 'nullable|string',
        ]);

        $validated['employee_id'] = $request->user()->employees_id ?? 1;
        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $category = ItemCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori baru berhasil ditambahkan!',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = ItemCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $id . ',category_id',
            'descriptions' => 'nullable|string',
        ]);

        $validated['edited_by'] = $request->user()->employees_id ?? 1;

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate!',
            'data' => $category
        ], 200);
    }


    public function exportExcel(ExportService $exportService)
    {
        $headers = ['ID', 'Nama Kategori', 'Deskripsi', 'Jumlah Suku Cadang', 'Dibuat Oleh'];
        $query = ItemCategory::withCount('spareparts')->with('creator');
        $fileName = 'data_kategori_barang_' . date('Ymd') . '.xlsx';

        return $exportService->exportToExcel($fileName, $headers, $query, function ($item) {
            return [
                $item->category_id,
                $item->name,
                $item->descriptions ?? '-',
                $item->spareparts_count ?? 0,
                $item->creator ? $item->creator->name : '-',
            ];
        });
    }

    public function exportPdf(PdfExportService $pdfExportService)
    {
        $query = ItemCategory::withCount('spareparts')->with('creator');
        $fileName = 'laporan_kategori_barang_' . date('Ymd') . '.pdf';

        return $pdfExportService->export(
            $fileName,
            $query,
            fn($item) => [
                'ID'             => $item->category_id,
                'Nama Kategori'  => $item->name,
                'Deskripsi'      => $item->descriptions ?? '-',
                'Jumlah Suku Cadang' => $item->spareparts_count ?? 0,
                'Dibuat Oleh'    => $item->creator ? $item->creator->name : '-',
            ],
            ['title' => 'Laporan Master Data Kategori Barang']
        );
    }
}