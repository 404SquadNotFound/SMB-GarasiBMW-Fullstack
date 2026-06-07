<?php

namespace App\Http\Controllers;

use App\Models\ServiceTransaction;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceTransaction::with([
            'vehicle.customer',
            'vehicle.carType',
            'items.sparepart',
            'creator',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle.customer', function ($cq) use ($search) {
                    $cq->where('name', 'LIKE', "%{$search}%")
                       ->orWhere('phone_number', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('vehicle', function ($vq) use ($search) {
                    $vq->where('license_plate', 'LIKE', "%{$search}%")
                       ->orWhere('model', 'LIKE', "%{$search}%");
                })
                ->orWhere('invoice_number', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_service', $request->status);
        }

        return $query->orderBy('created_at', 'desc')
                     ->paginate($request->limit ?? 10);
    }

    public function show($id)
    {
        $transaction = ServiceTransaction::with([
            'vehicle.customer',
            'vehicle.carType',
            'items.sparepart',
            'creator',
        ])->find($id);

        if (!$transaction) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data transaksi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $transaction,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => 'nullable|exists:customers,customer_id',
            'vehicle_id'    => 'nullable|exists:vehicles,vehicles_id',
            'customer_name' => 'nullable|string',
            'phone_number'  => 'nullable|string',
            'address'       => 'nullable|string',
            'license_plate' => 'nullable|string',
            'car_model'     => 'nullable|string',
            'engine_code'   => 'nullable|str    ing',
            'km_masuk'      => 'nullable|numeric',
            'items'         => 'nullable|array',
            'items.*.sparepart_id' => 'nullable|exists:spareparts,sparepart_id',
            'items.*.quantity'     => 'nullable|integer|min:1',
            'status_payment'       => 'nullable|in:unpaid,dp,paid',
            'dp_amount'            => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // 1. Resolve Customer
            if ($request->filled('customer_id')) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                if (!$request->filled('customer_name') || !$request->filled('phone_number')) {
                    return response()->json(['status' => 'error', 'message' => 'Nama dan nomor telepon pelanggan wajib diisi!'], 422);
                }
                $customer = Customer::firstOrCreate(
                    ['phone_number' => $request->phone_number],
                    [
                        'name'       => $request->customer_name,
                        'address'    => $request->address ?? '-',
                        'created_by' => $request->user()->employees_id ?? 1,
                    ]
                );
            }

            // 2. Resolve Vehicle
            if ($request->filled('vehicle_id')) {
                $vehicle = Vehicle::findOrFail($request->vehicle_id);
            } else {
                $licensePlate = $request->license_plate ?? '-';
                $vehicle = Vehicle::firstOrCreate(
                    ['license_plate' => $licensePlate, 'customer_id' => $customer->customer_id],
                    [
                        'car_type_id'     => null,
                        'model'           => $request->car_model ?? '-',
                        'engine_code'     => $request->engine_code ?? '-',
                        'production_code' => '-',
                        'odometer'        => (int) preg_replace('/[^0-9]/', '', $request->km_masuk ?? '0'),
                        'created_by'      => $request->user()->employees_id ?? 1,
                    ]
                );
            }

            // 3. Generate nomor invoice
            $dateCode    = date('Ymd');
            $countToday  = ServiceTransaction::whereDate('created_at', date('Y-m-d'))->count() + 1;
            $invoiceNumber = 'INV-PP-' . $dateCode . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // 4. Buat transaksi
            $transaction = ServiceTransaction::create([
                'vehicle_id'     => $vehicle->vehicles_id,
                'invoice_number' => $invoiceNumber,
                'branch'         => 'PELAJAR_PEJUANG',
                'odometer'       => (int) preg_replace('/[^0-9]/', '', $request->km_masuk ?? '0'),
                'status_service' => 'pengecekan',
                'status_payment' => $request->status_payment ?? 'unpaid',
                'dp_amount'      => $request->dp_amount ?? null,
                'created_by'     => $request->user()->employees_id ?? 1,
            ]);

            // 5. Simpan items (suku cadang) jika ada
            if ($request->filled('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    if (!empty($item['sparepart_id'])) {
                        $sparepart = \App\Models\Sparepart::find($item['sparepart_id']);
                        $qty       = $item['quantity'] ?? 1;
                        $price     = $sparepart?->selling_price ?? 0;

                        TransactionItem::create([
                            'transaction_id' => $transaction->transaction_id,
                            'spare_part_id'  => $item['sparepart_id'],
                            'item_name'      => $sparepart?->name ?? 'Unknown',
                            'item_type'      => 'Parts',
                            'qty'            => $qty,
                            'price'          => $price,
                            'subtotal'       => $price * $qty,
                        ]);

                        // Kurangi stok
                        if ($sparepart) {
                            $sparepart->decrement('quantity', $qty);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Antrian berhasil ditambahkan!',
                'data'    => $transaction->load(['vehicle.customer', 'vehicle.carType', 'items.sparepart', 'creator']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = ServiceTransaction::with(['vehicle.customer'])->findOrFail($id);

        $request->validate([
            'customer_id'   => 'nullable|exists:customers,customer_id',
            'vehicle_id'    => 'nullable|exists:vehicles,vehicles_id',
            'customer_name' => 'nullable|string',
            'phone_number'  => 'nullable|string',
            'address'       => 'nullable|string',
            'car_model'     => 'nullable|string',
            'engine_code'   => 'nullable|string',
            'km_masuk'      => 'nullable|numeric',
            'license_plate' => 'nullable|string',
            'items'         => 'nullable|array',
            'items.*.sparepart_id' => 'nullable|exists:spareparts,sparepart_id',
            'items.*.quantity'     => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Resolve Customer
            if ($request->filled('customer_id')) {
                $customer = Customer::findOrFail($request->customer_id);
            } else if ($request->filled('phone_number') && $request->filled('customer_name')) {
                $customer = Customer::firstOrCreate(
                    ['phone_number' => $request->phone_number],
                    [
                        'name'       => $request->customer_name,
                        'address'    => $request->address ?? '-',
                        'created_by' => $request->user()->employees_id ?? 1,
                    ]
                );
            } else {
                $customer = $transaction->vehicle?->customer;
            }

            // 2. Resolve Vehicle
            $vehicle = null;
            if ($request->filled('vehicle_id')) {
                $vehicle = Vehicle::findOrFail($request->vehicle_id);
            } else if ($customer && $request->filled('license_plate')) {
                $licensePlate = $request->license_plate;
                $vehicle = Vehicle::firstOrCreate(
                    ['license_plate' => $licensePlate, 'customer_id' => $customer->customer_id],
                    [
                        'car_type_id'     => null,
                        'model'           => $request->car_model ?? '-',
                        'engine_code'     => $request->engine_code ?? '-',
                        'production_code' => '-',
                        'odometer'        => (int) preg_replace('/[^0-9]/', '', $request->km_masuk ?? '0'),
                        'created_by'      => $request->user()->employees_id ?? 1,
                    ]
                );
            } else {
                $vehicle = $transaction->vehicle;
            }

            // Update items jika dikirim
            if ($request->has('items')) {
                // Kembalikan stok lama sebelum diganti yang baru
                foreach ($transaction->items as $oldItem) {
                    $oldSparepart = \App\Models\Sparepart::find($oldItem->spare_part_id);
                    if ($oldSparepart) {
                        $oldSparepart->increment('quantity', $oldItem->qty);
                    }
                }

                $transaction->items()->delete();
                foreach ($request->items as $item) {
                    if (!empty($item['sparepart_id'])) {
                        $sparepart = \App\Models\Sparepart::find($item['sparepart_id']);
                        $qty       = $item['quantity'] ?? 1;
                        $price     = $sparepart?->selling_price ?? 0;

                        TransactionItem::create([
                            'transaction_id' => $transaction->transaction_id,
                            'spare_part_id'  => $item['sparepart_id'],
                            'item_name'      => $sparepart?->name ?? 'Unknown',
                            'item_type'      => 'Parts',
                            'qty'            => $qty,
                            'price'          => $price,
                            'subtotal'       => $price * $qty,
                        ]);

                        // Kurangi stok dengan yang baru
                        if ($sparepart) {
                            $sparepart->decrement('quantity', $qty);
                        }
                    }
                }
            }

            $transaction->update([
                'vehicle_id' => $vehicle ? $vehicle->vehicles_id : $transaction->vehicle_id,
                'odometer'   => $request->filled('km_masuk') ? (int) preg_replace('/[^0-9]/', '', $request->km_masuk) : $transaction->odometer,
                'edited_by'  => $request->user()->employees_id ?? 1,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data antrian berhasil diupdate!',
                'data'    => $transaction->fresh(['vehicle.customer', 'vehicle.carType', 'items.sparepart', 'creator']),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal update data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = ServiceTransaction::findOrFail($id);

        $request->validate([
            'status_service' => 'required|in:menunggu,pengecekan,dikerjakan,dibatalkan,selesai',
        ]);

        $transaction->update([
            'status_service' => $request->status_service,
            'edited_by'      => $request->user()->employees_id ?? 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Status pengerjaan berhasil diupdate!',
            'data'    => $transaction,
        ], 200);
    }

    public function destroy($id)
    {
        $transaction = ServiceTransaction::findOrFail($id);

        // Kembalikan stok barang yang dipakai
        foreach ($transaction->items as $item) {
            $sparepart = \App\Models\Sparepart::find($item->spare_part_id);
            if ($sparepart) {
                $sparepart->increment('quantity', $item->qty);
            }
        }

        $transaction->items()->delete();
        $transaction->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data antrian berhasil dihapus!',
        ], 200);
    }

    /**
     * Return status counts for dashboard stat cards.
     * GET /api/transactions/status-summary
     */
    public function statusSummary()
    {
        $counts = ServiceTransaction::select('status_service', DB::raw('count(*) as total'))
            ->groupBy('status_service')
            ->pluck('total', 'status_service');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'menunggu'    => $counts['menunggu']    ?? 0,
                'pengecekan'  => $counts['pengecekan']  ?? 0,
                'dikerjakan'  => $counts['dikerjakan']  ?? 0,
                'selesai'     => $counts['selesai']     ?? 0,
                'dibatalkan'  => $counts['dibatalkan']  ?? 0,
                'total'       => ServiceTransaction::count(),
            ],
        ]);
    }
}
