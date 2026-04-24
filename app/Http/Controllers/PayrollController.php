<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $payrolls = Payroll::with('employee')->orderBy('created_at', 'desc')->get();
    //     $employees = Employee::where('status', true)->get();

    //     return view('backend.payrolls.index', compact('payrolls', 'employees'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'employees_id' => 'required|exists:employees,employees_id',
    //         'month' => 'required|integer|min:1|max:12',
    //         'year' => 'required|integer',
    //     ]);

    //     $existingPayroll = Payroll::where('employees_id', $request->employees_id)
    //         ->where('month', $request->month)
    //         ->where('year', $request->year)
    //         ->first();

    //     if ($existingPayroll) {
    //         return redirect()->back()->with('error', 'Gaji pegawai ini untuk bulan tersebut sudah dicetak brok!');
    //     }

    //     $employee = Employee::findOrFail($request->employees_id);

    //     // --- LOGIKA PERHITUNGAN (Bisa disesuaikan lagi) ---

    //     // 1. Hitung Denda Keterlambatan (Misal: 1x telat potong Rp 50.000)
    //     $lateCount = Attendance::where('employee_id', $employee->employees_id)
    //         ->whereMonth('date', $request->month)
    //         ->whereYear('date', $request->year)
    //         ->where('status', 'Terlambat')
    //         ->count();
    //     $totalDeduction = $lateCount * 50000;

    //     // 2. Tabungan Wajib (Misal: Dipotong 5% dari gaji pokok buat disimpen)
    //     $totalSavings = $employee->base_salary * 0.05;

    //     // 3. Hitung Gaji Bersih (Take Home Pay)
    //     $netSalary = $employee->base_salary - $totalDeduction - $totalSavings;

    //     // Simpan ke Database
    //     Payroll::create([
    //         'employees_id' => $employee->employees_id,
    //         'month' => $request->month,
    //         'year' => $request->year,
    //         'total_income' => $employee->base_salary,
    //         'total_deduction' => $totalDeduction,
    //         'total_savings' => $totalSavings,
    //         'net_salary' => $netSalary,
    //         'notes' => 'Di-generate otomatis. Telat: ' . $lateCount . 'x',
    //     ]);

    //     return redirect()->back()->with('success', 'Slip Gaji berhasil di-generate!');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $payroll = Payroll::findOrFail($id);
    //     $payroll->delete();

    //     return redirect()->back()->with('success', 'Data Payroll berhasil dibatalkan!');
    // }
}
