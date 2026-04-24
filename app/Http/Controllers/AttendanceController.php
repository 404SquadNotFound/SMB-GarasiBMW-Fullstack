<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')->orderBy('date', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data absensi ditarik',
            'data' => $attendances
        ], 200);
    }

    public function store(Request $request)
    {
        $employeeId = $request->user()->employees_id ?? 1;

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gps' => 'required|string',
        ]);

        $alreadyClockedIn = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($alreadyClockedIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lu udah absen hari ini!'
            ], 400);
        }

        $photoPath = $request->file('photo')->store('attendances', 'public');

        $clockInTime = Carbon::now();
        $status = $clockInTime->format('H:i') > '08:00' ? 'Terlambat' : 'Hadir';

        $attendance = Attendance::create([
            'employee_id' => $employeeId,
            'date' => Carbon::today(),
            'status' => $status,
            'clock_in' => $clockInTime->format('H:i:s'),
            'photo' => $photoPath,
            'gps' => $request->gps,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil! Semangat kerjanya!',
            'data' => $attendance
        ], 201);
    }
}
