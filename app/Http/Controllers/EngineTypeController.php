<?php

namespace App\Http\Controllers;

use App\Models\EngineType;
use Illuminate\Http\Request;
use App\Http\Services\PdfExportService;
use App\Http\services\ExportService;

class EngineTypeController extends Controller
{
private function applyFilters(Request $request)
    {
        $query = EngineType::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('cylinders', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('cylinders')) {
            $query->where('cylinders', $request->cylinders);
        }

        return $query;
    }

    public function index(Request $request)
    {
        return $this->applyFilters($request)
            ->orderBy('created_at', 'desc')
            ->paginate($request->limit ?? 10);
    }

    // Fungsi buat ambil pilihan filter unik dari DB
    public function getFilterOptions()
    {
        $cylinders = EngineType::whereNotNull('cylinders')->distinct()->pluck('cylinders');
        $fuels = EngineType::whereNotNull('fuel_type')->distinct()->pluck('fuel_type');

        return response()->json([
            'status' => 'success',
            'data' => [
                'cylinders' => $cylinders,
                'fuels' => $fuels
            ]
        ]);
    }

    public function show($id)
    {
        $engineType = EngineType::find($id);
        if (!$engineType) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mesin nggak ketemu brok!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $engineType
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cylinders' => 'required|string|max:255',
            'oil_cap' => 'required|numeric',
            'fuel_type' => 'required|in:Bensin,Diesel',
            'engine_cap' => 'required|numeric',
        ]);
        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $engineType = EngineType::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Tipe Mesin ditambahkan', 'data' => $engineType], 201);
    }

    public function update(Request $request, $id)
    {
        $engineType = EngineType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cylinders' => 'required|string|max:255',
            'oil_cap' => 'required|numeric',
            'fuel_type' => 'required|in:Bensin,Diesel',
            'engine_cap' => 'required|numeric',
        ]);
        $validated['edited_by'] = $request->user()->employees_id ?? 1;

        $engineType->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Tipe Mesin diupdate', 'data' => $engineType], 200);
    }

    public function destroy($id)
    {
        EngineType::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Tipe Mesin dihapus'], 200);
    }

public function exportExcel(Request $request, ExportService $exportService)
    {
        $headers = ['Nama Mesin', 'Silinder', 'Kapasitas Oli (L)', 'Tipe BBM', 'Kapasitas Mesin (cc)', 'Tanggal Dibuat'];
        $query = $this->applyFilters($request); // Menggunakan filter yang sama dengan index
        $fileName = 'data_mesin_' . date('Ymd_His') . '.xlsx';

        return $exportService->exportToExcel($fileName, $headers, $query, function ($item) {
            return [
                $item->name,
                $item->cylinders,
                $item->oil_cap,
                $item->fuel_type,
                $item->engine_cap,
                $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function exportPdf(Request $request, PdfExportService $pdfExportService)
    {
        $query = $this->applyFilters($request);
        $fileName = 'data_mesin_' . date('Ymd_His') . '.pdf';

        return $pdfExportService->export(
            $fileName,
            $query,
            fn($item) => [
                'Nama' => $item->name,
                'Silinder' => $item->cylinders,
                'Oli' => $item->oil_cap,
                'BBM' => $item->fuel_type,
                'Kapasitas' => $item->engine_cap,
                'Tanggal' => $item->created_at->format('d-m-Y'),
            ],
            ['title' => 'Laporan Data Tipe Mesin']
        );
    }
}
