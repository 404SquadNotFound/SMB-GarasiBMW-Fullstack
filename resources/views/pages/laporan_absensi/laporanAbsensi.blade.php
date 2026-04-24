@extends('layouts.master')

@section('title', 'Laporan Absensi')
@section('title_header', 'Manajemen Rekap Karyawan')

@section('content')
{{-- Kontrol Modal dan Tab --}}
<div x-data="{ 
    openModal: false, 
    activeTab: 'mingguan',
    rekap: {
        mingguan: { hadir: 17, telat: 3, sakit: 3, cuti: 0, libur: 5, total: 28, periode: 'Minggu ini (Senin - Minggu)', p_hadir: '60.7%', p_telat: '10.7%', p_sakit: '10.7%', p_libur: '17.9%' },
        bulanan: { hadir: 68, telat: 12, sakit: 12, cuti: 0, libur: 20, total: 112, periode: 'Bulan ini (4 Minggu)', p_hadir: '60.7%', p_telat: '10.7%', p_sakit: '10.7%', p_libur: '17.9%' },
        tahunan: { hadir: 884, telat: 156, sakit: 156, cuti: 0, libur: 260, total: 1456, periode: 'Tahun ini (12 Bulan)', p_hadir: '60.7%', p_telat: '10.7%', p_sakit: '10.7%', p_libur: '17.9%' }
    }
}">

    {{-- 1. Action Bar --}}
    <div class="flex items-center justify-between mb-5">
        <div class="relative w-[340px]">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#627D98]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari Karyawan" class="w-full pl-10 pr-4 py-3 bg-white border border-[#D9E2EC] rounded-[10px] outline-none shadow-sm text-[14px]">
        </div>

        <div class="flex items-center gap-2.5">
            <button class="flex items-center gap-2 px-5 py-[11px] bg-white border border-[#D9E2EC] rounded-[10px] font-bold text-[13px] text-[#213F5C] shadow-sm hover:bg-gray-50 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> Filter</button>
            <button class="flex items-center gap-2 px-5 py-[11px] bg-white border border-[#D9E2EC] rounded-[10px] font-bold text-[13px] text-[#213F5C] shadow-sm hover:bg-gray-50 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export</button>
            <button @click="openModal = true" class="flex items-center gap-2 px-5 py-[11px] bg-[#1273EB] text-white rounded-[10px] font-bold text-[13px] shadow-sm hover:bg-[#0E62CC] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Rekap Karyawan
            </button>
        </div>
    </div>

    {{-- 2. Legend Section --}}
    <div class="bg-white rounded-xl border border-[#D9E2EC] p-4 mb-5 flex items-center gap-6 shadow-sm">
        <span class="text-[13px] font-bold text-[#213F5C]">Keterangan:</span>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-[12px] font-semibold text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Hadir</div>
            <div class="bg-[#FFF4D9] text-[#FFB800] px-3 py-1 rounded-md text-[11px] font-bold flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg> Terlambat</div>
            <div class="bg-[#FFEAEA] text-[#FF4D4D] px-3 py-1 rounded-md text-[11px] font-bold">Sakit</div>
            <div class="bg-[#EAF2FF] text-[#1273EB] px-3 py-1 rounded-md text-[11px] font-bold">Cuti</div>
            <div class="bg-[#F0FFF4] text-[#22C55E] px-3 py-1 rounded-md text-[11px] font-bold">Libur</div>
        </div>
    </div>

    {{-- 3. Main Table --}}
    <div class="bg-white rounded-xl border border-[#D9E2EC] shadow-sm overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-[#D9E2EC] text-[11px] font-bold text-[#627D98] uppercase tracking-wider">
                        <th class="px-6 py-5">Nama Karyawan</th>
                        <th class="px-6 py-5 text-center">Senin</th>
                        <th class="px-6 py-5 text-center">Selasa</th>
                        <th class="px-6 py-5 text-center">Rabu</th>
                        <th class="px-6 py-5 text-center">Kamis</th>
                        <th class="px-6 py-5 text-center">Jumat</th>
                        <th class="px-6 py-5 text-center">Sabtu</th>
                        <th class="px-6 py-5 text-center">Minggu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-5 font-bold text-[#213F5C]">Edsel Septa Haryanto</td>
                        <td class="px-6 py-5 text-center font-semibold text-[#213F5C]">08:00</td>
                        <td class="px-6 py-5 text-center font-semibold text-[#213F5C]">07:45</td>
                        <td class="px-6 py-5 text-center"><span class="bg-[#FFF4D9] text-[#FFB800] px-3 py-1 rounded-md text-[11px] font-bold">09:15</span></td>
                        <td class="px-6 py-5 text-center font-semibold text-[#213F5C]">08:00</td>
                        <td class="px-6 py-5 text-center font-semibold text-[#213F5C]">07:50</td>
                        <td class="px-6 py-5 text-center"><span class="bg-[#FFEAEA] text-[#FF4D4D] px-3 py-1 rounded-md text-[11px] font-bold">Sakit</span></td>
                        <td class="px-6 py-5 text-center"><span class="bg-[#F0FFF4] text-[#22C55E] px-3 py-1 rounded-md text-[11px] font-bold">Libur</span></td>
                    </tr>
                    <tr class="h-40"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL REKAP --}}
    <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center overflow-y-auto" style="display: none;">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="openModal = false"></div>

        {{-- Modal Box (rounded 32px) --}}
        <div class="relative bg-white w-full max-w-4xl mx-4 rounded-[32px] shadow-2xl p-10" x-transition>
            
            {{-- Close X --}}
            <button @click="openModal = false" class="absolute top-8 right-8 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            {{-- Header Modal --}}
            <div class="flex items-start gap-5 mb-8">
                <div class="w-16 h-16 bg-[#F1F5F9] rounded-2xl flex items-center justify-center border border-gray-100">
                    <svg class="w-8 h-8 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[#213F5C] mb-1">Rekap Kehadiran Karyawan</h2>
                    <p class="text-gray-400 font-medium text-[15px]">Total <span x-text="rekap[activeTab].total"></span> catatan kehadiran</p>
                </div>
            </div>

            {{-- Tab Switcher - FULL WIDTH & MINIMALIST --}}
            <div class="w-full bg-[#F8FAFC] border border-gray-100 p-1.5 rounded-2xl mb-10 flex items-center">
                <button @click="activeTab = 'mingguan'" :class="activeTab === 'mingguan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="flex-1 flex items-center justify-center gap-2.5 py-3 rounded-xl font-bold text-[14px] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Mingguan
                </button>
                <button @click="activeTab = 'bulanan'" :class="activeTab === 'bulanan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="flex-1 flex items-center justify-center gap-2.5 py-3 rounded-xl font-bold text-[14px] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path></svg>
                    Bulanan
                </button>
                <button @click="activeTab = 'tahunan'" :class="activeTab === 'tahunan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="flex-1 flex items-center justify-center gap-2.5 py-3 rounded-xl font-bold text-[14px] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tahunan
                </button>
            </div>

            {{-- Grid Content --}}
            <div class="grid grid-cols-2 gap-6 mb-10">
                {{-- Hadir --}}
                <div class="bg-[#EDF6FF] border border-[#BFDBFE] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#BFDBFE] rounded-2xl flex items-center justify-center text-[#1273EB] border border-blue-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 font-bold text-[13px] uppercase tracking-wide mb-1">Hadir</p>
                            <h3 class="text-4xl font-bold text-[#1273EB]" x-text="rekap[activeTab].hadir"></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#1273EB] font-bold text-[14px] mb-2" x-text="rekap[activeTab].p_hadir + ' dari total'"></div>
                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#1273EB] rounded-full" :style="'width: ' + rekap[activeTab].p_hadir"></div>
                        </div>
                    </div>
                </div>

                {{-- Terlambat --}}
                <div class="bg-[#FFF9EA] border border-[#FDE68A] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#FDE68A] rounded-2xl flex items-center justify-center text-[#FFB800] border border-amber-200">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 font-bold text-[13px] uppercase tracking-wide mb-1">Terlambat</p>
                            <h3 class="text-4xl font-bold text-[#FFB800]" x-text="rekap[activeTab].telat"></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#FFB800] font-bold text-[14px] mb-2" x-text="rekap[activeTab].p_telat + ' dari total'"></div>
                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#FFB800] rounded-full" :style="'width: ' + rekap[activeTab].p_telat"></div>
                        </div>
                    </div>
                </div>

                {{-- Sakit --}}
                <div class="bg-[#FFF1F2] border border-[#FECDD3] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#FECDD3] rounded-2xl flex items-center justify-center text-[#FF4D4D] border border-red-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 font-bold text-[13px] uppercase tracking-wide mb-1">Sakit</p>
                            <h3 class="text-4xl font-bold text-[#FF4D4D]" x-text="rekap[activeTab].sakit"></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#FF4D4D] font-bold text-[14px] mb-2" x-text="rekap[activeTab].p_sakit + ' dari total'"></div>
                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#FF4D4D] rounded-full" :style="'width: ' + rekap[activeTab].p_sakit"></div>
                        </div>
                    </div>
                </div>

                {{-- Cuti --}}
                <div class="bg-[#EDF6FF] border border-[#BFDBFE] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#BFDBFE] rounded-2xl flex items-center justify-center text-[#1273EB] border border-blue-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 font-bold text-[13px] uppercase tracking-wide mb-1">Cuti</p>
                            <h3 class="text-4xl font-bold text-[#1273EB]" x-text="rekap[activeTab].cuti"></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#1273EB] font-bold text-[14px] mb-2">0.0% dari total</div>
                        <div class="w-24 h-2 bg-gray-200 rounded-full"></div>
                    </div>
                </div>

                {{-- Libur --}}
                <div class="col-span-2 bg-[#F0FDF4] border border-[#BBF7D0] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#BBF7D0] rounded-2xl flex items-center justify-center text-[#22C55E] border border-green-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 font-bold text-[13px] uppercase tracking-wide mb-1">Libur</p>
                            <h3 class="text-4xl font-bold text-[#22C55E]" x-text="rekap[activeTab].libur"></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#22C55E] font-bold text-[14px] mb-2" x-text="rekap[activeTab].p_libur + ' dari total'"></div>
                        <div class="w-64 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#22C55E] rounded-full" :style="'width: ' + rekap[activeTab].p_libur"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-auto">
                <p class="text-gray-400 font-bold text-[14px]">Periode: <span class="text-[#213F5C]" x-text="rekap[activeTab].periode"></span></p>
                <button @click="openModal = false" class="px-12 py-3.5 bg-[#1273EB] text-white rounded-2xl font-bold text-[15px] shadow-lg shadow-blue-200 hover:bg-[#0E62CC] transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection