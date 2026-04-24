<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['customer', 'carType'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kendaraan berhasil ditarik',
            'data' => $vehicles
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'car_type_id' => 'required|exists:car_types,car_type_id',
            'model' => 'required|string|max:255',
            'engine_code' => 'required|string|max:255',
            'production_code' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
            'odometer' => 'required|integer|min:0',
        ]);

        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Kendaraan berhasil ditambahkan!',
            'data' => $vehicle
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'engine_code' => 'required|string|max:255',
            'production_code' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $id . ',vehicles_id',
            'odometer' => 'required|integer|min:0',
        ]);

        $validated['edited_by'] = $request->user()->employees_id ?? 1;

        $vehicle->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Kendaraan berhasil diupdate!',
            'data' => $vehicle
        ], 200);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Kendaraan berhasil dihapus!',
        ], 200);
    }
}
