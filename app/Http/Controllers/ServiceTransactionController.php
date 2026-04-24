<?php

namespace App\Http\Controllers;

use App\Models\ServiceTransaction;
use Illuminate\Http\Request;

class ServiceTransactionController extends Controller
{
    public function index()
    {
        $transactions = ServiceTransaction::with('vehicle')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data transaksi servis berhasil ditarik',
            'data' => $transactions
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,vehicles_id',
            'branch' => 'required|in:AHMAD_YANI,PELAJAR_PEJUANG',
            'odometer' => 'required|integer',
        ]);

        // Logic Nomor Invoice Otomatis
        $branchCode = $request->branch == 'AHMAD_YANI' ? 'AY' : 'PP';
        $dateCode = date('Ymd');
        $countToday = ServiceTransaction::whereDate('created_at', date('Y-m-d'))->count() + 1;
        $invoiceNumber = 'INV-' . $branchCode . '-' . $dateCode . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $transaction = ServiceTransaction::create([
            'vehicle_id' => $request->vehicle_id,
            'invoice_number' => $invoiceNumber,
            'branch' => $request->branch,
            'odometer' => $request->odometer,
            'status_service' => 'pengecekan',
            'status_payment' => 'unpaid',
            'created_by' => $request->user()->employees_id ?? 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mobil berhasil didaftarkan untuk servis!',
            'data' => $transaction
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = ServiceTransaction::findOrFail($id);

        $request->validate([
            'status_service' => 'required|in:menunggu,pengecekan,dikerjakan,dibatalkan,selesai'
        ]);

        $transaction->update([
            'status_service' => $request->status_service,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pengerjaan berhasil diupdate!',
            'data' => $transaction
        ], 200);
    }
}
