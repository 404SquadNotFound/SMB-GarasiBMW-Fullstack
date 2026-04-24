<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = CarType::with('engineType');

        // 1. Filter Pencarian Umum (Nama, Sasis, atau Kode Mesin)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('chassis_number', 'LIKE', "%{$search}%")
                    ->orWhere('engine_code', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filter Spesifik Seri
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }

        // 3. Cari teks nama mesin di kolom engine_code
        if ($request->filled('engine_type_id')) {
            $engine = \App\Models\EngineType::find($request->engine_type_id);

            if ($engine) {
                $engineName = $engine->name;
                $query->where('engine_code', 'LIKE', "%{$engineName}%");
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
    }

    public function show($id)
    {
        $carType = CarType::with('engineType')->find($id);

        if (!$carType) {
            return response()->json(['message' => 'Data mobil gak ketemu'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $carType], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chassis_number' => 'required|string',
            'name'           => 'required|string',
            'series'         => 'required|string',
            'engine_ids'     => 'required|array',
            'engine_ids.*'   => 'exists:engine_types,engine_type_id',
        ]);

        // 1. Ambil nama-nama mesin buat engine_code
        $engineNames = \App\Models\EngineType::whereIn('engine_type_id', $request->engine_ids)
            ->pluck('name')
            ->toArray();

        // 2. Set data yang mau masuk ke database
        $dataToSave = [
            'chassis_number' => $validated['chassis_number'],
            'name'           => $validated['name'],
            'series'         => $validated['series'],
            'engine_code'    => implode(', ', $engineNames),
            'engine_type_id' => $request->engine_ids[0], // Ambil yang pertama
            'created_by'     => $request->user()->employees_id ?? 1,
        ];

        // 3. Simpan pakai $dataToSave, JANGAN pakai $validated
        $carType = CarType::create($dataToSave);

        return response()->json(['status' => 'success', 'data' => $carType], 201);
    }

    public function update(Request $request, $id)
    {
        $carType = CarType::findOrFail($id);
        $validated = $request->validate([
            'chassis_number' => 'required|string',
            'name'           => 'required|string',
            'series'         => 'required|string',
            'engine_ids'     => 'required|array',
        ]);

        $engineNames = \App\Models\EngineType::whereIn('engine_type_id', $request->engine_ids)
            ->pluck('name')
            ->toArray();

        $dataToUpdate = [
            'chassis_number' => $validated['chassis_number'],
            'name'           => $validated['name'],
            'series'         => $validated['series'],
            'engine_code'    => implode(', ', $engineNames),
            'engine_type_id' => $request->engine_ids[0],
            'edited_by'      => $request->user()->employees_id ?? 1,
        ];

        $carType->update($dataToUpdate);

        return response()->json(['status' => 'success', 'message' => 'Data terupdate'], 200);
    }

    public function destroy($id)
    {
        CarType::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Tipe Mobil dihapus'], 200);
    }
    public function getUniqueSeries()
    {
        // AMBIL DARI KOLOM 'series', BUKAN 'name'
        $series = \App\Models\CarType::whereNotNull('series')
            ->where('series', '!=', '')
            ->distinct()
            ->orderBy('series', 'asc')
            ->pluck('series'); // Pastiin ini 'series' brok!

        return response()->json([
            'status' => 'success',
            'data' => $series
        ], 200);
    }
}
