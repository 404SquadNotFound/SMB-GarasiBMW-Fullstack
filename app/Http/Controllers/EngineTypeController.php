<?php

namespace App\Http\Controllers;

use App\Models\EngineType;
use Illuminate\Http\Request;

class EngineTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = EngineType::query();

        // 1. Search Debounce (Nama Mesin atau Silinder)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('cylinders', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filter Bahan Bakar
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        // 3. Filter Konfigurasi Silinder
        if ($request->filled('cylinders')) {
            $query->where('cylinders', $request->cylinders);
        }

        return $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
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
}
