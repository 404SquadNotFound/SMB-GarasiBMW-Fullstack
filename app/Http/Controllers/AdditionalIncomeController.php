<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncome;
use Illuminate\Http\Request;

class AdditionalIncomeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_id' => 'required|exists:payrolls,payroll_id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,deduction,savings',
            'amount' => 'required|numeric|min:0',
            'disbursement_method' => 'required|in:bulan ini,akhir tahun,bulan ke 40',
        ]);

        $validated['created_by'] = $request->user()->employees_id ?? 1;
        $income = AdditionalIncome::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Komponen dicatat', 'data' => $income], 201);
    }

    public function destroy($id)
    {
        AdditionalIncome::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Komponen dihapus'], 200);
    }
}
