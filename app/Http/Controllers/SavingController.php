<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index()
    {
        $savings = Saving::with(['employee', 'payroll'])->orderBy('due_date', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $savings], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_id' => 'required|exists:payrolls,payroll_id',
            'employees_id' => 'required|exists:employees,employees_id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:locked,available,withdrawn,forfeited',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $saving = Saving::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Tabungan dicatat', 'data' => $saving], 201);
    }

    public function update(Request $request, $id)
    {
        $saving = Saving::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:locked,available,withdrawn,forfeited',
        ]);

        $saving->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Status tabungan diupdate', 'data' => $saving], 200);
    }
}
