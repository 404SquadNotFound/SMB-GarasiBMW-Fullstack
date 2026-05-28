<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #374151; }
    </style>
</head>
<body class="flex justify-center min-h-screen">

    @php
        // Simulasi data dari Controller. 
        // Ubah nilainya menjadi: 'belum', 'berhasil', atau 'terlambat' untuk melihat perubahannya.
        $status_absen = 'berhasil'; 
    @endphp

    <div class="w-full max-w-md bg-gray-50 min-h-screen shadow-2xl relative overflow-hidden flex flex-col">
        
        <div class="bg-blue-500 rounded-b-[2.5rem] pt-8 pb-8 px-5 shadow-sm relative">
            <div class="bg-blue-600/50 backdrop-blur-sm rounded-xl py-3 px-4 flex flex-col items-center justify-center text-white mb-6">
                <div class="flex items-center gap-2 text-sm font-medium mb-1">
                    <i class="fa-regular fa-calendar"></i>
                    <span>Minggu, 25 Januari 2026</span>
                </div>
                <div class="flex items-center gap-2 text-xl font-bold">
                    <i class="fa-regular fa-clock"></i>
                    <span>00.07.44</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-lg flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name=Edsel+Septa+Haryanto&background=random" alt="Profile" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
                <div>
                    <h2 class="text-gray-800 font-bold text-lg">Edsel Septa Haryanto</h2>
                    <span class="inline-block bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full mt-1">Karyawan</span>
                </div>
            </div>
        </div>

        <div class="px-5 mt-6 pb-10 flex-1 flex flex-col gap-6">
            
            @if ($status_absen == 'belum')
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center gap-4">
                    <div class="bg-red-100 text-red-500 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-sm">Belum Melakukan Absensi</p>
                        <p class="text-red-500 font-bold text-sm">Ayo segera absen!</p>
                    </div>
                </div>

            @elseif ($status_absen == 'berhasil')
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4">
                    <div class="bg-green-100 text-green-500 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-circle-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-sm">Absen Berhasil!</p>
                        <p class="text-gray-500 text-xs mt-0.5">Check-in: 08:15 WIB &bull; Lokasi terdeteksi</p>
                    </div>
                </div>

            @elseif ($status_absen == 'terlambat')
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex items-center gap-4">
                    <div class="bg-orange-100 text-orange-500 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-sm">Absen Terlambat</p>
                        <p class="text-gray-500 text-xs mt-0.5">Check-in: 08:30 WIB &bull; Terlambat 30 Menit</p>
                    </div>
                </div>
            @endif

            @if ($status_absen == 'belum')
                <button class="w-full bg-blue-500 hover:bg-blue-600 transition-colors text-white rounded-2xl py-4 font-bold text-lg flex items-center justify-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="fa-regular fa-circle-check"></i>
                    Absen Sekarang
                </button>
            @else
                <button class="w-full bg-[#78e354] text-white rounded-2xl py-4 font-bold text-lg flex items-center justify-center gap-2 shadow-lg shadow-green-500/20 cursor-default">
                    <i class="fa-regular fa-circle-check"></i>
                    Sudah Absen
                </button>
            @endif

            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm">Rekap Absenmu Bulan Ini</h3>
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-green-100 text-green-600 w-12 h-14 rounded-xl flex flex-col items-center justify-center">
                                <i class="fa-regular fa-circle-check mb-1"></i>
                                <span class="font-bold text-lg leading-none">10</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Hadir</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-blue-100 text-blue-600 w-12 h-14 rounded-xl flex flex-col items-center justify-center">
                                <i class="fa-regular fa-file-lines mb-1"></i>
                                <span class="font-bold text-lg leading-none">10</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Cuti</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-orange-100 text-orange-500 w-12 h-14 rounded-xl flex flex-col items-center justify-center">
                                <i class="fa-solid fa-hand-holding-heart mb-1"></i>
                                <span class="font-bold text-lg leading-none">10</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Sakit</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-red-100 text-red-500 w-12 h-14 rounded-xl flex flex-col items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation mb-1"></i>
                                <span class="font-bold text-lg leading-none">10</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Terlambat</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm">Denda Terlambat Bulan Ini</h3>
                <div class="bg-[#e65c5c] text-white rounded-2xl p-4 flex flex-col items-center justify-center shadow-lg shadow-red-500/20">
                    <span class="text-sm font-medium opacity-90 mb-1">Total Denda</span>
                    <span class="text-2xl font-bold">Rp 150.000</span>
                </div>
            </div>

            <button class="w-full bg-white border border-[#e65c5c] text-[#e65c5c] hover:bg-red-50 transition-colors rounded-2xl py-3.5 font-bold text-base flex items-center justify-center gap-2 mt-auto">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Keluar
            </button>

        </div>
    </div>

</body>
</html>