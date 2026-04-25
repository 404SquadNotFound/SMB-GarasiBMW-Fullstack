<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

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

    public function store(Request $request)
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
        
        
        $validPrefixes = [
            'A', 'B', 'D', 'E', 'F', 'T', 'Z', 'G', 'H', 'K', 'R', 'AA', 'AB', 'AD', 'L', 'M', 'N', 'P', 'S', 'W', 'AE', 'AG',
            'BL', 'BB', 'BK', 'BA', 'BM', 'BP', 'BG', 'BN', 'BE', 'BD', 'BH',
            'DK', 'DR', 'EA', 'DH', 'EB', 'ED',
            'KB', 'DA', 'KH', 'KT', 'KU',
            'DB', 'DL', 'DM', 'DN', 'DT', 'DD', 'DC', 'DP',
            'DE', 'DG', 'PA', 'PB', 'PD', 'PE', 'PG', 'PS', 'PT'
        ];

        $formattedCars = [];

        foreach ($request->cars as $index => $car) {
            $rawPlate = preg_replace('/[^A-Z0-9]/i', '', strtoupper($car['license_plate']));

            if (preg_match('/^([A-Z]{1,2})(\d{1,4})([A-Z]{0,3})$/', $rawPlate, $matches)) {
                
                $prefix = $matches[1]; 

                if (!in_array($prefix, $validPrefixes)) {
                    return response()->json([
                        'message' => "Kode wilayah '{$prefix}' pada plat '{$car['license_plate']}' tidak dikenali di Indonesia. Silakan periksa kembali!"
                    ], 422);
                }
                
                $formattedPlate = trim($matches[1] . ' ' . $matches[2] . ' ' . $matches[3]);
                $car['license_plate'] = $formattedPlate;
                $formattedCars[] = $car;

            } else {
                return response()->json([
                    'message' => "Format nomor polisi '" . $car['license_plate'] . "' tidak valid! Gunakan format standar (Contoh: B 1020 JAW)."
                ], 422);
            }
        }

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

            // 2. Simpan Mobil 
            foreach ($formattedCars as $carData) {
                $carType = \App\Models\CarType::find($carData['car_type_id']);
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

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $validated['edited_by'] = $request->user()->employees_id ?? 1;

        $customer->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pelanggan berhasil diupdate!',
            'data' => $customer
        ], 200);
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

    public function exportExcel(ExportService $exportService)
    {
        $headers = ['ID', 'Nama', 'Nomor Telepon', 'Alamat', 'Daftar Kendaraan', 'Didaftarkan Oleh'];
        $query = Customer::with(['creator', 'vehicles']); 
        $fileName = 'data_pelanggan_' . date('Ymd') . '.xlsx';

        return $exportService->exportToExcel($fileName, $headers, $query, function ($item) {

            $daftarKendaraan = $item->vehicles->isNotEmpty() 
                ? $item->vehicles->map(function ($vehicle) {
                    return $vehicle->license_plate . ' (' . $vehicle->model . ')';
                })->implode(', ') 
                : 'Belum ada kendaraan';

            return [
                $item->customer_id,
                $item->name,
                $item->phone_number,
                $item->address,
                $daftarKendaraan,
                $item->creator ? $item->creator->name : '-',
            ];
        });
    }

    public function exportPdf(PdfExportService $pdfExportService){
        $query = Customer::with(['creator', 'vehicles']);
        $fileName = 'laporan_pelanggan_' . date('Ymd') . '.pdf';

        return $pdfExportService->export(
            $fileName,
            $query,
            fn($item) => [
                'ID' => $item->customer_id,
                'Nama' => $item->name,
                'telepon' => $item->phone_number,
                'Alamat' => $item->address,
                'Kendaraan' => $item->vehicles->isNotEmpty() 
                    ? $item->vehicles->map(fn($v) => $v->license_plate)->implode(', ') 
                    : '-',
                'Pendaftar' => $item->creator ? $item->creator->name : '-',
            ],
            ['title' => 'Laporan Data Pelanggan GarasiBMW']
        );
    }
}
