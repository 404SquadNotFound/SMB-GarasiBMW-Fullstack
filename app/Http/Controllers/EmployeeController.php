<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // 1. Search (nama, email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Filter Status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('status', 1);
            } elseif ($request->status === 'nonaktif') {
                $query->where('status', 0);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pegawai berhasil ditarik',
            'data' => $employee
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'join_date' => 'required|date',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:6',
            'role' => 'required|in:pemilik_bengkel,finance,kepala_bengkel,kepala_admin,admin,karyawan',
            'base_salary' => 'required|numeric',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $employee = Employee::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pegawai berhasil ditambahkan!',
            'data' => $employee
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'base_salary' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $validated['edited_by'] = $request->user()->employees_id ?? 1;
        $employee->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pegawai berhasil diupdate!',
            'data' => $employee
        ], 200);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['status' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil dinonaktifkan!',
        ], 200);
    }

    public function getFilterOptions(Request $request)
    {
        // 1. Ambil Role (tergantung filter status)
        $roleQuery = Employee::whereNotNull('role')->where('role', '!=', '');
        if ($request->filled('status')) {
            $statusVal = $request->status === 'aktif' ? 1 : 0;
            $roleQuery->where('status', $statusVal);
        }
        $roles = $roleQuery->distinct()->orderBy('role', 'asc')->pluck('role');

        // 2. Ambil Status (tergantung filter role)
        $statusQuery = Employee::query();
        if ($request->filled('role')) {
            $statusQuery->where('role', $request->role);
        }
        $rawStatuses = $statusQuery->distinct()->pluck('status');
        $statuses = [];
        foreach ($rawStatuses as $s) {
            if ($s == 1) {
                $statuses['aktif'] = 'Aktif';
            } else {
                $statuses['nonaktif'] = 'Non-Aktif';
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
                'statuses' => $statuses
            ]
        ]);
    }
}
