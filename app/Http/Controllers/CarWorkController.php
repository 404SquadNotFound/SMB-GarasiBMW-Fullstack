<?php

namespace App\Http\Controllers;

use App\Models\CarWork;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CarWorkController extends Controller
{
    public function index()
    {
        $carWorks = CarWork::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $carWorks], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'vehicle_id' => 'required|exists:vehicles,vehicles_id',
            'sparepart_id' => 'nullable|exists:spareparts,sparepart_id',
            'sparepart_name' => 'nullable|string|max:255',
            'sparepart_used_amount' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        $validated['customer_name'] = $customer->name;
        $validated['phone_number'] = $customer->phone_number;
        $validated['address'] = $customer->address;
        $validated['car_name'] = $vehicle->model;
        $validated['license_plate'] = $vehicle->license_plate;
        $validated['engine_code'] = $vehicle->engine_code;
        $validated['odometer'] = $vehicle->odometer;
        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $carWork = CarWork::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Log pengerjaan dicatat', 'data' => $carWork], 201);
    }

    public function destroy($id)
    {
        CarWork::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Log dihapus'], 200);
    }
}
