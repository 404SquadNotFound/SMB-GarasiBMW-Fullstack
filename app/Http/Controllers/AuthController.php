<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Fungsi buat login dan generate token
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari data pegawai berdasarkan email
        $employee = Employee::where('email', $request->email)->first();

        // 3. Cek apakah email ketemu DAN password cocok
        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah brok!'
            ], 401);
        }

        // 4. Cek apakah akunnya masih aktif
        if ($employee->status == false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun lu udah non-aktif brok!'
            ], 403);
        }

        // 5. Kalo semua aman, Generate Token Sanctum!
        $token = $employee->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'user' => $employee,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    // Fungsi buat logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil, token udah hangus!'
        ], 200);
    }
}
