<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\CustomerService;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $query = Customer::with('vehicles');
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('phone_number', 'LIKE', "%{$search}%");
        }
        return $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
    }

    public function store(Request $request, CustomerService $customerService)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'cars' => 'required|array|min:1',
            'cars.*.car_type_id' => 'required|exists:car_types,car_type_id',
            'cars.*.license_plate' => 'required|string',
            'cars.*.km_reading' => 'nullable',
            'cars.*.year' => 'nullable',
            'cars.*.engine_name' => 'nullable|string',
        ]);
        
        $validationResult = $customerService->formatAndValidate($validated['cars']);

        if (!$validationResult['success']) {
            return response()->json([
                'message' => $validationResult['message']
            ], 422);
        }

        $formattedCars = $validationResult['data'];

        // MULAI TRANSAKSI
        DB::beginTransaction();

        try {
            // 1. Simpan Pelanggan
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'created_by' => $request->user()->employees_id ?? 1
            ]);

            // 2. Simpan Mobil menggunakan $formattedCars
            foreach ($formattedCars as $carData) {
                $carType = CarType::find($carData['car_type_id']);
                $modelName = $carType ? $carType->name : 'Unknown Model';

                $customer->vehicles()->create([
                    'car_type_id' => $carData['car_type_id'],
                    'model' => $modelName,
                    'license_plate' => $carData['license_plate'], // Sudah Rapi!
                    'odometer' => $carData['km_reading'] ?? 0,
                    'production_code' => $carData['year'] ?? null,
                    'engine_code' => $carData['engine_name'] ?? null,
                    'created_by' => $request->user()->employees_id ?? 1
                ]);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Data tersimpan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal simpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $customer = Customer::with(['vehicles', 'creator'])->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Data pelanggan nggak ketemu brok'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ], 200);
    }

    public function update(Request $request, $id, CustomerService $customerService)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'cars' => 'required|array|min:1',
            'cars.*.car_type_id' => 'required|exists:car_types,car_type_id',
            'cars.*.license_plate' => 'required|string',
            'cars.*.km_reading' => 'nullable',
            'cars.*.year' => 'nullable',
            'cars.*.engine_name' => 'nullable|string',
        ]);

        $validationResult = $customerService->formatAndValidate($validated['cars']);

        if (!$validationResult['success']) {
            return response()->json([
                'message' => $validationResult['message']
            ], 422);
        }

        $formattedCars = $validationResult['data'];

        DB::beginTransaction();

        try {
            $customer->update([
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'edited_by' => $request->user()->employees_id ?? 1
            ]);

            // Menghapus vehicle lama yang sudah tidak ada
            $customer->vehicles()->delete();

            // Insert ulang semua vehicle
            foreach ($formattedCars as $carData) {
                $carType = CarType::find($carData['car_type_id']);
                $modelName = $carType ? $carType->name : 'Unknown Model';

                $customer->vehicles()->create([
                    'car_type_id' => $carData['car_type_id'],
                    'model' => $modelName,
                    'license_plate' => $carData['license_plate'],
                    'odometer' => $carData['km_reading'] ?? 0,
                    'production_code' => $carData['year'] ?? null,
                    'engine_code' => $carData['engine_name'] ?? null,
                    'created_by' => $request->user()->employees_id ?? 1
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Pelanggan berhasil diupdate!',
                'data' => $customer->load('vehicles')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal update data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pelanggan berhasil dihapus!',
        ], 200);
    }

    public function exportExcel(CustomerService $customerService)
    {
        // Controller lalu menyuruh Service untuk bekerja
        return $customerService->downloadExcel();
    }

    public function exportPdf(CustomerService $customerService)
    {
        return $customerService->downloadPdf();
    }

    /**
     * List pelanggan + kendaraan mereka untuk dropdown di antrian pengerjaan
     */
    public function listForAntrian(Request $request)
    {
        $query = Customer::with(['vehicles']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->limit(30)->get()->map(function ($c) {
            return [
                'id'           => $c->customer_id,
                'nama'         => $c->name,
                'telepon'      => $c->phone_number,
                'alamat'       => $c->address,
                'vehicles'     => $c->vehicles->map(function ($v) {
                    return [
                        'id'            => $v->vehicles_id,
                        'model'         => $v->model,
                        'license_plate' => $v->license_plate,
                        'engine_code'   => $v->engine_code,
                        'odometer'      => $v->odometer,
                        'label'         => $v->model . ' — ' . $v->license_plate,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $customers,
        ]);
    }
}
