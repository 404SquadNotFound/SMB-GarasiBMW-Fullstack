<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ], [
            'photo.max' => 'Ukuran lampiran bukti tidak boleh melebihi 1MB!',
            'photo.image' => 'File yang diupload harus berupa gambar.'
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

    public function reportManual(Request $request)
    {
        Carbon::setLocale('id'); // Bahasa Indonesia

        // Cek apakah ada request pindah minggu (0 = minggu ini, -1 = minggu lalu, 1 = minggu depan)
        $weekOffset = (int) $request->query('weekOffset', 0);

        // Geser waktu real-time sekarang sesuai tombol yang dipencet
        $baseDate = Carbon::now()->addWeeks($weekOffset);

        $startOfWeek = $baseDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $baseDate->copy()->endOfWeek(Carbon::SUNDAY);

        // String Periode (Contoh: "8 Juni - 14 Juni 2026")
        $periodeString = $startOfWeek->translatedFormat('j F') . ' - ' . $endOfWeek->translatedFormat('j F Y');
        $mingguKe = $startOfWeek->weekOfYear;

        $periodDates = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $periodDates[] = [
                'day_key' => $days[$i],
                'name' => $date->locale('id')->isoFormat('dddd'),
                'date' => $date->format('Y-m-d')
            ];
        }

        $employeesData = Employee::where('status', true)->get()->map(function ($emp) use ($startOfWeek, $endOfWeek, $periodDates) {
            $attendances = Attendance::where('employee_id', $emp->employees_id)
                ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->get();

            $attendanceByDay = [];
            foreach ($periodDates as $pd) {
                $att = $attendances->firstWhere('date', $pd['date']);
                $attendanceByDay[$pd['day_key']] = $att ? $att->toArray() : null;
            }

            return [
                'employee_id' => $emp->employees_id,
                'name' => $emp->name,
                'attendances' => $attendanceByDay
            ];
        });

        return view('pages.laporan_absensi.laporanAbsensi', [
            'employees' => $employeesData,
            'periodDates' => $periodDates,
            'periodeString' => $periodeString,
            'mingguKe' => $mingguKe,
            'weekOffset' => $weekOffset // <-- Kirim data offset ke view
        ]);
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employees_id',
            'date' => 'required|date',
            'status' => 'required|in:Hadir,Cuti,Sakit,Terlambat,Libur,Izin Terlambat',
            'clock_in' => 'nullable',
            'reason' => 'nullable|string', // Validasi alasan
            'evidence' => 'nullable|image|mimes:png,jpg,jpeg|max:5120' // Validasi gambar max 5MB
        ]);

        $status = $request->status;
        $clockIn = $request->clock_in;

        // Logika Telat Otomatis
        if ($status === 'Hadir' && $clockIn) {
            $timeString = Carbon::parse($clockIn)->format('H:i');
            if ($timeString >= '09:01') {
                $status = 'Terlambat';
            }
        }

        // Kosongkan jam jika Sakit/Cuti/Libur
        if (in_array($status, ['Sakit', 'Cuti', 'Libur'])) {
            $clockIn = null;
        }

        // Handle File Upload
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('attendances/evidence', 'public');
        }

        // Cari absen yang sudah ada buat update (biar file lama gak nimpa jadi null kalau ga upload baru)
        $existing = Attendance::where('employee_id', $request->employee_id)->where('date', $request->date)->first();

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            [
                'status' => $status,
                'clock_in' => $clockIn,
                'reason' => $request->reason,
                'photo' => $evidencePath ? $evidencePath : ($existing ? $existing->photo : null), // Simpan path foto
                'updated_by' => auth()->id() ?? 1,
            ]
        );

        return back()->with('success', 'Data absensi berhasil disimpan!');
    }

    public function getRekapData(Request $request)
    {
        $type = $request->query('type', 'mingguan');
        $now = Carbon::now();

        switch ($type) {
            case 'bulanan':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $periode = 'Bulan ini (' . $now->translatedFormat('F Y') . ')';
                break;

            case 'tahunan':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $periode = 'Tahun ini (' . $now->year . ')';
                break;

            default: // mingguan
                $start = $now->copy()->startOfWeek(Carbon::MONDAY);
                $end = $now->copy()->endOfWeek(Carbon::SUNDAY);
                $periode = 'Minggu ini (Senin - Minggu)';
                break;
        }

        // Query aggregate langsung dari DB, efisien
        $counts = Attendance::whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status IN ('Hadir', 'Izin Terlambat') THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = 'Terlambat' THEN 1 ELSE 0 END) as telat,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = 'Cuti' THEN 1 ELSE 0 END) as cuti,
            SUM(CASE WHEN status = 'Libur' THEN 1 ELSE 0 END) as libur
        ")
            ->first();

        $total = $counts->total ?: 1; // hindari division by zero

        return response()->json([
            'hadir' => (int) $counts->hadir,
            'telat' => (int) $counts->telat,
            'sakit' => (int) $counts->sakit,
            'cuti' => (int) $counts->cuti,
            'libur' => (int) $counts->libur,
            'total' => (int) $counts->total,
            'periode' => $periode,
            'p_hadir' => round($counts->hadir / $total * 100, 1) . '%',
            'p_telat' => round($counts->telat / $total * 100, 1) . '%',
            'p_sakit' => round($counts->sakit / $total * 100, 1) . '%',
            'p_libur' => round($counts->libur / $total * 100, 1) . '%',
        ]);
    }
}
