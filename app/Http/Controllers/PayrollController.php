<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AdditionalIncome;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    /**
     * Display a listing of payrolls.
     * Supports filtering by month & year (from the Periode Gaji banner).
     */
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        // Filter by month & year if provided
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Search by employee name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $payrolls = $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data payroll berhasil ditarik',
            'data' => $payrolls
        ], 200);
    }

    /**
     * Store a newly created payroll.
     *
     * Logic:
     *   1. total_income  = basic_salary + SUM(incomes.amount)
     *   2. total_deduction = SUM(penalties.amount) + auto-detect keterlambatan
     *   3. total_savings  = SUM(savings.amount)
     *   4. net_salary     = total_income - total_deduction - total_savings
     *
     * Auto-detect keterlambatan:
     *   - Count attendance records with status 'Terlambat' for the employee
     *     in the selected month/year
     *   - Each late = Rp 50.000 penalty (auto-inserted into penalties)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'  => 'required|exists:employees,employees_id',
            'month'        => 'required|integer|min:1|max:12',
            'year'         => 'required|integer|min:2000|max:2099',
            'basic_salary' => 'required|numeric|min:0',
            'incomes'      => 'nullable|array',
            'incomes.*.name'   => 'required_with:incomes|string|max:255',
            'incomes.*.type'   => 'required_with:incomes|string|max:255',
            'incomes.*.amount' => 'required_with:incomes|numeric|min:0',
            'savings'      => 'nullable|array',
            'savings.*.name'         => 'required_with:savings|string|max:255',
            'savings.*.type'         => 'required_with:savings|string|max:255',
            'savings.*.amount'       => 'required_with:savings|numeric|min:0',
            'savings.*.month_target' => 'nullable|integer|min:1',
            'penalties'    => 'nullable|array',
            'penalties.*.name'   => 'required_with:penalties|string|max:255',
            'penalties.*.info'   => 'nullable|string|max:255',
            'penalties.*.amount' => 'required_with:penalties|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Check for duplicate payroll (same employee, same month/year)
        $existingPayroll = Payroll::where('employees_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($existingPayroll) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gaji pegawai ini untuk bulan ' . $request->month . '/' . $request->year . ' sudah ada!'
            ], 409);
        }

        $employee = Employee::findOrFail($request->employee_id);

        // ── 1. Hitung Pendapatan ──────────────────────────────────────
        $basicSalary = (float) $request->basic_salary;
        $incomes = $request->incomes ?? [];
        $totalAdditionalIncome = collect($incomes)->sum('amount');
        $totalIncome = $basicSalary + $totalAdditionalIncome;

        // ── 2. Hitung Penalti (Manual + Auto-detect Keterlambatan) ────
        $penalties = $request->penalties ?? [];
        $totalManualPenalty = collect($penalties)->sum('amount');

        // Auto-detect keterlambatan dari tabel attendances
        $lateCount = Attendance::where('employee_id', $employee->employees_id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->where('status', 'Terlambat')
            ->count();

        $lateDeduction = $lateCount * 50000; // Rp 50.000 per keterlambatan
        $totalDeduction = $totalManualPenalty + $lateDeduction;

        // ── 3. Hitung Tabungan ────────────────────────────────────────
        $savingsData = $request->savings ?? [];
        $totalSavings = collect($savingsData)->sum('amount');

        // ── 4. Hitung Gaji Bersih ─────────────────────────────────────
        // Formula: Pendapatan - Penalti - Tabungan
        $netSalary = $totalIncome - $totalDeduction - $totalSavings;

        // ── Build notes ───────────────────────────────────────────────
        $notes = 'Di-generate otomatis.';
        if ($lateCount > 0) {
            $notes .= ' Telat: ' . $lateCount . 'x (Rp ' . number_format($lateDeduction, 0, ',', '.') . ')';
        }

        // ── Simpan ke database (wrapped in transaction) ───────────────
        DB::beginTransaction();
        try {
            $payroll = Payroll::create([
                'employees_id'   => $employee->employees_id,
                'month'          => $request->month,
                'year'           => $request->year,
                'total_income'   => $totalIncome,
                'total_deduction' => $totalDeduction,
                'total_savings'  => $totalSavings,
                'net_salary'     => $netSalary,
                'notes'          => $notes,
            ]);

            // Simpan pendapatan tambahan (incomes) ke additional_incomes
            foreach ($incomes as $income) {
                AdditionalIncome::create([
                    'payroll_id'          => $payroll->payroll_id,
                    'name'                => $income['name'],
                    'type'                => 'income',
                    'amount'              => $income['amount'],
                    'disbursement_method' => 'bulan ini',
                ]);
            }

            // Simpan tabungan ke savings
            foreach ($savingsData as $saving) {
                Saving::create([
                    'payroll_id'   => $payroll->payroll_id,
                    'employees_id' => $employee->employees_id,
                    'name'         => $saving['name'],
                    'status'       => 'locked',
                    'amount'       => $saving['amount'],
                    'due_date'     => now()->addMonths($saving['month_target'] ?? 12)->format('Y-m-d'),
                ]);
            }

            // Simpan penalti manual ke additional_incomes (type = deduction)
            foreach ($penalties as $penalty) {
                AdditionalIncome::create([
                    'payroll_id'          => $payroll->payroll_id,
                    'name'                => $penalty['name'],
                    'type'                => 'deduction',
                    'amount'              => $penalty['amount'],
                    'disbursement_method' => 'bulan ini',
                ]);
            }

            // Simpan penalti otomatis keterlambatan ke additional_incomes
            if ($lateCount > 0) {
                AdditionalIncome::create([
                    'payroll_id'          => $payroll->payroll_id,
                    'name'                => 'Keterlambatan Pegawai (Auto-detect)',
                    'type'                => 'deduction',
                    'amount'              => $lateDeduction,
                    'disbursement_method' => 'bulan ini',
                ]);
            }

            DB::commit();

            // Load relations for response
            $payroll->load(['employee', 'additionalIncomes', 'savings']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Slip gaji berhasil di-generate! Telat: ' . $lateCount . 'x',
                'data'    => $this->formatPayrollDetail($payroll, $basicSalary),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data payroll: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified payroll detail.
     * Returns data in the format expected by detailPayroll.blade.php
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employee', 'additionalIncomes', 'savings'])->findOrFail($id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail payroll berhasil ditarik',
            'data'    => $this->formatPayrollDetail($payroll),
        ], 200);
    }

    /**
     * Update the specified payroll.
     */
    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'basic_salary' => 'nullable|numeric|min:0',
            'month'        => 'nullable|integer|min:1|max:12',
            'year'         => 'nullable|integer|min:2000|max:2099',
            'notes'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Recalculate if basic_salary changes
        if ($request->filled('basic_salary')) {
            $basicSalary = (float) $request->basic_salary;

            // Sum additional incomes (type = income)
            $additionalIncome = $payroll->additionalIncomes()
                ->where('type', 'income')
                ->sum('amount');

            $payroll->total_income = $basicSalary + $additionalIncome;

            // Recalculate net salary
            $payroll->net_salary = $payroll->total_income - $payroll->total_deduction - $payroll->total_savings;
        }

        if ($request->filled('month')) $payroll->month = $request->month;
        if ($request->filled('year')) $payroll->year = $request->year;
        if ($request->filled('notes')) $payroll->notes = $request->notes;

        $payroll->save();

        $payroll->load(['employee', 'additionalIncomes', 'savings']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data payroll berhasil diupdate!',
            'data'    => $this->formatPayrollDetail($payroll),
        ], 200);
    }

    /**
     * Remove the specified payroll.
     */
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete(); // cascade will delete additional_incomes & savings

        return response()->json([
            'status'  => 'success',
            'message' => 'Data payroll berhasil dihapus!',
        ], 200);
    }

    /**
     * Format payroll data into the structure expected by the frontend detail page.
     *
     * Expected by detailPayroll.blade.php:
     *   employee.name, employee.employee_number, employee.join_year,
     *   employee.birth_date, employee.role,
     *   salary.base_salary,
     *   salary.allowances[]  → { name, type, amount }
     *   salary.savings[]     → { name, type, amount, description }
     *   salary.penalties[]   → { name, type, description, amount }
     *   created_by.name, created_at, updated_at
     */
    private function formatPayrollDetail(Payroll $payroll, float $baseSalary = null): array
    {
        $employee = $payroll->employee;

        // Calculate base salary if not explicitly provided
        // base_salary = total_income - SUM(additional income type=income)
        if ($baseSalary === null) {
            $additionalIncomeSum = $payroll->additionalIncomes
                ->where('type', 'income')
                ->sum('amount');
            $baseSalary = $payroll->total_income - $additionalIncomeSum;
        }

        // Separate additional_incomes by type
        $allowances = $payroll->additionalIncomes
            ->where('type', 'income')
            ->map(function ($item) {
                return [
                    'name'   => $item->name,
                    'type'   => $item->disbursement_method ?? 'Insentif',
                    'amount' => (float) $item->amount,
                ];
            })->values()->toArray();

        $penalties = $payroll->additionalIncomes
            ->where('type', 'deduction')
            ->map(function ($item) {
                return [
                    'name'        => $item->name,
                    'type'        => 'Penalti',
                    'description' => $item->disbursement_method ?? '-',
                    'amount'      => (float) $item->amount,
                ];
            })->values()->toArray();

        $savings = $payroll->savings->map(function ($item) {
            return [
                'name'        => $item->name,
                'type'        => $item->status,
                'amount'      => (float) $item->amount,
                'description' => 'Jatuh tempo: ' . $item->due_date,
            ];
        })->values()->toArray();

        // Month names for display
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return [
            'id'         => $payroll->payroll_id,
            'month'      => $payroll->month,
            'year'       => $payroll->year,
            'month_name' => $monthNames[$payroll->month] ?? '-',
            'employee'   => [
                'id'              => $employee->employees_id ?? null,
                'name'            => $employee->name ?? '-',
                'employee_number' => $employee->employees_id ?? '-',
                'join_year'       => $employee->join_date ? date('Y', strtotime($employee->join_date)) : '-',
                'birth_date'      => $employee->birth_date ?? '-',
                'role'            => $employee->role ?? '-',
            ],
            'salary' => [
                'base_salary'     => $baseSalary,
                'total_income'    => (float) $payroll->total_income,
                'total_deduction' => (float) $payroll->total_deduction,
                'total_savings'   => (float) $payroll->total_savings,
                'net_salary'      => (float) $payroll->net_salary,
                'allowances'      => $allowances,
                'savings'         => $savings,
                'penalties'       => $penalties,
            ],
            'notes'      => $payroll->notes,
            'created_by' => [
                'name' => $employee->name ?? 'System',
            ],
            'created_at' => $payroll->created_at,
            'updated_at' => $payroll->updated_at,
        ];
    }
}
