@extends('layouts.master')

@section('title', 'Laporan Absensi')
@section('title_header', 'Laporan Absensi')

{{-- 1. HEADER TABEL ABSENSI MINGGUAN --}}
@section('table_header')
    <th class="px-6 py-5">Nama Pegawai</th>
    <th class="px-6 py-5 text-center">Senin</th>
    <th class="px-6 py-5 text-center">Selasa</th>
    <th class="px-6 py-5 text-center">Rabu</th>
    <th class="px-6 py-5 text-center">Kamis</th>
    <th class="px-6 py-5 text-center">Jumat</th>
    <th class="px-6 py-5 text-center">Sabtu</th>
    <th class="px-6 py-5 text-center">Minggu</th>
@endsection

{{-- 2. BODY TABEL DEFAULT SKELETON LOADING --}}
@section('table_body')
    <tbody id="attendanceTableBody" class="divide-y divide-gray-100 text-[13px]">
        <tr>
            <td colspan="8" class="text-center py-20 text-gray-400 italic font-medium">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-[#1273EB] border-t-transparent rounded-full animate-spin"></div>
                    Memuat data laporan absensi pegawai...
                </div>
            </td>
        </tr>
    </tbody>
@endsection


@section('content')
    {{-- Kontrol Utama Menggunakan Alpine.js --}}
    <div id="attendanceContainer" x-data="{ 
                            openModal: false, 
                            openInputModal: false, 
                            activeTab: 'mingguan',

                            {{-- Form State untuk Modal Input Absen Manual --}}
                            form: {
                                employee_id: '',
                                employee_name: '',
                                date: '',
                                day_name: '',
                                status: 'Hadir',
                                hour: '',
                                minute: '',
                                ampm: 'AM',
                                reason: '',
                                fileName: ''
                            },

                            {{-- Fungsi untuk mengisi form state dari pemicu luar/JavaScript biasa --}}
                            initForm(empId, empName, date, dayName, status, h, m, ampm, reason) {
                                this.form.employee_id = empId;
                                this.form.employee_name = empName;
                                this.form.date = date;
                                this.form.day_name = dayName;
                                this.form.status = status || 'Hadir';
                                this.form.hour = h || '';
                                this.form.minute = m || '';
                                this.form.ampm = ampm || 'AM';
                                this.form.reason = reason || '';
                                this.form.fileName = '';
                                this.openInputModal = true;
                            },

                            {{-- Konversi Waktu ke Format 24 Jam untuk Database --}}
                            get24HourTime() {
                                if (!this.form.hour) return null;
                                let h = parseInt(this.form.hour) || 0;
                                let m = parseInt(this.form.minute) || 0;
                                if (this.form.ampm === 'PM' && h < 12) h += 12;
                                if (this.form.ampm === 'AM' && h === 12) h = 0;
                                return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:00`;
                            },

                            {{-- Deteksi otomatis Hadir <-> Terlambat saat input jam diketik --}}
                            autoDetectStatus() {
                                if (!['Hadir', 'Terlambat'].includes(this.form.status)) return;
                                if (this.form.hour === '') return;

                                let h = parseInt(this.form.hour) || 0;
                                let m = parseInt(this.form.minute) || 0;
                                if (this.form.ampm === 'PM' && h < 12) h += 12;
                                if (this.form.ampm === 'AM' && h === 12) h = 0;

                                if ((h > 9) || (h === 9 && m >= 1)) {
                                    this.form.status = 'Terlambat';
                                } else {
                                    this.form.status = 'Hadir';
                                }
                            },

                            rekap: {
                                mingguan: null,
                                bulanan: null,
                                tahunan: null
                            },
                            rekapLoading: false,

                            async fetchRekap(type) {
                                if (this.rekap[type] !== null) return; 

                                this.rekapLoading = true;
                                try {
                                    const res = await fetch(`{{ route('attendance.rekap') }}?type=${type}`);
                                    const data = await res.json();
                                    this.rekap[type] = data;
                                } catch(e) {
                                    console.error('Gagal fetch rekap:', e);
                                } finally {
                                    $data.rekapLoading = false;
                                }
                            },
                        }" x-init="
                            $watch('form.hour', value => autoDetectStatus());
                            $watch('form.minute', value => autoDetectStatus());
                            $watch('form.ampm', value => autoDetectStatus());
                        ">

        {{-- 1. Action Bar dengan ID searchInput --}}
        <div class="flex items-center justify-between mb-5">
            <div
                class="flex items-center w-[340px] bg-white border border-[#D9E2EC] rounded-[10px] px-4 shadow-sm focus-within:border-[#1273EB] transition-all">
                <svg class="w-4 h-4 text-[#627D98] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                    </path>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari Pegawai..."
                    class="w-full py-3 ml-3 bg-transparent outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div class="flex items-center gap-2.5">
                <button @click="openModal = true; activeTab = 'mingguan'; fetchRekap('mingguan')"
                    class="flex items-center gap-2 px-5 py-[11px] bg-[#1273EB] text-white rounded-[10px] font-bold text-[13px] shadow-sm hover:bg-[#0E62CC] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Rekap Pegawai
                </button>
            </div>
        </div>

        {{-- 2. BANNER BIRU PERIODE ABSENSI --}}
        <div class="bg-[#4D82F3] rounded-[16px] p-6 mb-6 flex items-center justify-between text-white shadow-sm mt-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-white/20 rounded-[14px] flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-white/80 font-bold text-[12px] uppercase tracking-wider mb-0.5">Periode Absensi</p>
                    <h2 class="text-[22px] font-bold">{{ $periodeString }}</h2>
                </div>
            </div>

            <div class="flex items-center gap-1.5 bg-white/10 p-1.5 rounded-[12px] backdrop-blur-sm border border-white/10">
                <a href="?weekOffset={{ $weekOffset - 1 }}"
                    class="w-9 h-9 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="px-5 py-2 bg-white text-[#4D82F3] font-bold text-[13px] rounded-lg shadow-sm">
                    Minggu ke-{{ $mingguKe }}
                </div>
                <a href="?weekOffset={{ $weekOffset + 1 }}"
                    class="w-9 h-9 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- 3. Legend Section --}}
        <div class="bg-white rounded-xl border border-[#D9E2EC] p-4 mb-5 flex items-center gap-5 shadow-sm overflow-x-auto">
            <span class="text-[13px] font-bold text-[#213F5C] whitespace-nowrap">Keterangan:</span>
            <div class="flex items-center gap-3.5 whitespace-nowrap">
                <div class="bg-[#F0FFF4] text-[#22C55E] px-3.5 py-1.5 rounded-lg text-[12px] font-bold">Hadir</div>
                <div class="bg-[#F8FAFC] text-[#64748B] px-3.5 py-1.5 rounded-lg text-[12px] font-bold">Izin Terlambat</div>
                <div
                    class="bg-[#FFF4D9] text-[#FFB800] px-3.5 py-1.5 rounded-lg text-[12px] font-bold flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#FFB800]"></div>
                    Terlambat
                </div>
                <div class="bg-[#FFEAEA] text-[#FF4D4D] px-3.5 py-1.5 rounded-lg text-[12px] font-bold">Sakit</div>
                <div class="bg-[#EAF2FF] text-[#1273EB] px-3.5 py-1.5 rounded-lg text-[12px] font-bold">Cuti</div>
                <div class="bg-[#F3E8FF] text-[#A855F7] px-3.5 py-1.5 rounded-lg text-[12px] font-bold">Libur</div>
            </div>
        </div>

        {{-- 4. CONTAINER PEMBUNGKUS PAGINATION YANG VALID --}}
        @include('layouts.table_wrapper')

        {{-- 5. MODAL INPUT WAKTU ABSENSI --}}
        <div x-show="openInputModal" class="fixed inset-0 z-[999] flex items-center justify-center overflow-y-auto"
            style="display: none;">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" x-transition.opacity @click="openInputModal = false">
            </div>

            <div class="relative bg-white w-full max-w-md mx-4 rounded-[24px] shadow-2xl p-8" x-transition>
                <button @click="openInputModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h3 class="text-lg font-bold text-[#213F5C] mb-6">Input Waktu Absensi</h3>

                <form action="{{ route('attendance.storeManual') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="employee_id" :value="form.employee_id">
                    <input type="hidden" name="date" :value="form.date">
                    <input type="hidden" name="clock_in" :value="get24HourTime()">

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pegawai</label>
                        <p class="text-[14px] font-semibold text-[#213F5C]" x-text="form.employee_name"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                        <p class="text-[14px] font-semibold text-[#213F5C]" x-text="form.day_name + ', ' + form.date"></p>
                    </div>

                    <div class="mb-5" x-data="{ dropdownOpen: false }">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Kehadiran</label>
                        <div class="relative w-full">
                            <input type="hidden" name="status" :value="form.status">
                            <button type="button" @click="dropdownOpen = !dropdownOpen"
                                @click.outside="dropdownOpen = false"
                                class="w-full px-4 py-3.5 rounded-[12px] flex items-center justify-between font-bold text-[15px] border-2 transition-all duration-200 outline-none"
                                :class="{
                                                        'bg-[#F0FFF4] text-[#22C55E] border-[#22C55E]' : form.status === 'Hadir',
                                                        'bg-[#f0f0ff5d] text-[#393947] border-[#9c9faf]' : form.status === 'Izin Terlambat',
                                                        'bg-[#F3E8FF] text-[#A855F7] border-[#D8B4FE]' : form.status === 'Libur',
                                                        'bg-[#FFF4D9] text-[#FFB800] border-[#FFB800]' : form.status === 'Terlambat',
                                                        'bg-[#FFEAEA] text-[#FF4D4D] border-[#FF4D4D]' : form.status === 'Sakit',
                                                        'bg-[#EAF2FF] text-[#1273EB] border-[#1273EB]' : form.status === 'Cuti'
                                                    }">
                                <span x-text="form.status"></span>
                                <svg class="w-5 h-5 transition-transform duration-200"
                                    :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="dropdownOpen" x-transition.opacity.duration.200ms
                                class="absolute z-50 w-full mt-2 p-3 bg-white/95 backdrop-blur-md border border-gray-100 rounded-[16px] shadow-xl flex flex-col gap-2.5"
                                style="display: none;">
                                <button type="button" @click="form.status = 'Hadir'; dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#F0FFF4] text-[#22C55E] border-[#22C55E] hover:opacity-70 transition-opacity">Hadir</button>
                                <button type="button" @click="form.status = 'Izin Terlambat'; dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#f0f0ff5d] text-[#393947] border-[#9c9faf] hover:opacity-70 transition-opacity">Izin
                                    Terlambat</button>
                                <button type="button" @click="form.status = 'Sakit'; dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#FFEAEA] text-[#FF4D4D] border-[#FF4D4D] hover:opacity-70 transition-opacity">Sakit</button>
                                <button type="button" @click="form.status = 'Cuti'; dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#EAF2FF] text-[#1273EB] border-[#1273EB] hover:opacity-70 transition-opacity">Cuti</button>
                                <button type="button" @click="form.status = 'Libur'; dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#F3E8FF] text-[#A855F7] border-[#D8B4FE] hover:opacity-70 transition-opacity">Libur</button>
                                <button type="button" x-show="form.status === 'Terlambat'" @click="dropdownOpen = false"
                                    class="w-full text-left px-4 py-3 rounded-[12px] font-bold text-[15px] border-2 bg-[#FFF4D9] text-[#FFB800] border-[#FFB800]">Terlambat
                                    (Dideteksi Sistem)</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6" x-show="['Hadir', 'Terlambat', 'Izin Terlambat'].includes(form.status)" x-transition>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jam Absensi</label>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="form.hour"
                                @input="let h = parseInt(form.hour); if (h > 12 && h < 24) { form.hour = h - 12; form.ampm = 'PM'; } else if (h >= 24 || h < 1 && form.hour !== '') { form.hour = 12; }"
                                placeholder="09" min="1" max="12"
                                class="w-20 px-4 py-3 border border-[#D9E2EC] rounded-xl outline-none text-[14px] text-center focus:border-[#1273EB]">
                            <span class="font-bold text-gray-400">:</span>
                            <input type="number" x-model="form.minute"
                                @input="let m = parseInt(form.minute); if (m > 59) { form.minute = 59; } else if (m < 0 && form.minute !== '') { form.minute = 0; }"
                                placeholder="00" min="0" max="59"
                                class="w-20 px-4 py-3 border border-[#D9E2EC] rounded-xl outline-none text-[14px] text-center focus:border-[#1273EB]">
                            <select x-model="form.ampm"
                                class="px-4 py-3 border border-[#D9E2EC] rounded-xl outline-none text-[14px] focus:border-[#1273EB] bg-white flex-1">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                        <div x-show="form.status === 'Terlambat'" x-transition
                            class="mt-3 w-full bg-[#FFF4D9] text-[#FFB800] text-[11px] font-bold p-3 rounded-xl flex items-center justify-center gap-2 border border-[#FDE68A]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z">
                                </path>
                            </svg>
                            Dideteksi Terlambat (Lebih dari 09:00)
                        </div>
                    </div>

                    <div class="mb-6 space-y-4" x-show="['Sakit', 'Cuti', 'Izin Terlambat'].includes(form.status)"
                        x-transition>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Alasan Keterangan</label>
                            <textarea name="reason" x-model="form.reason" rows="3"
                                placeholder="Masukkan alasan yang relevan..."
                                class="w-full px-4 py-3 border border-[#D9E2EC] rounded-xl outline-none text-[13px] focus:border-[#1273EB] resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Lampiran Bukti
                                (Opsional)</label>
                            <div
                                class="relative w-full border-2 border-dashed border-[#D9E2EC] rounded-xl hover:bg-gray-50 transition-colors p-4 text-center cursor-pointer">
                                <input type="file" name="photo" @change="form.fileName = $event.target.files[0].name"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span class="text-[12px] font-medium text-gray-500"
                                        x-text="form.fileName ? form.fileName : 'Klik atau seret file ke sini'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" @click="openInputModal = false"
                            class="flex-1 py-3.5 bg-[#FFEAEA] text-[#FF4D4D] rounded-xl font-bold text-[14px] hover:bg-[#FFE0E0] transition-all">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E62CC] transition-all shadow-md shadow-blue-200">Simpan
                            Absen</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 6. MODAL REKAP --}}
        <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center overflow-y-auto"
            style="display: none;">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" x-transition.opacity @click="openModal = false"></div>

            <div class="relative bg-white w-full max-w-4xl mx-4 rounded-[32px] shadow-2xl p-10" x-transition>
                <button @click="openModal = false" class="absolute top-8 right-8 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="flex items-start gap-5 mb-8">
                    <div class="w-16 h-16 bg-[#F1F5F9] rounded-2xl flex items-center justify-center border border-gray-100">
                        <svg class="w-8 h-8 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#213F5C] mb-1">Rekap Kehadiran Pegawai</h2>
                        <p class="text-gray-400 font-medium text-[15px]">
                            <template x-if="rekap[activeTab] && !rekapLoading">
                                <span>Total <span x-text="rekap[activeTab].total"></span> catatan kehadiran</span>
                            </template>
                            <template x-if="rekapLoading">
                                <span class="inline-block w-44 h-4 bg-gray-200 rounded-full animate-pulse mt-1"></span>
                            </template>
                        </p>
                    </div>
                </div>

                <div class="w-full bg-[#F8FAFC] border border-gray-100 p-1.5 rounded-2xl mb-8 flex items-center">
                    <button @click="activeTab = 'mingguan'; fetchRekap('mingguan')"
                        :class="activeTab === 'mingguan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Mingguan
                    </button>
                    <button @click="activeTab = 'bulanan'; fetchRekap('bulanan')"
                        :class="activeTab === 'bulanan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Bulanan
                    </button>
                    <button @click="activeTab = 'tahunan'; fetchRekap('tahunan')"
                        :class="activeTab === 'tahunan' ? 'bg-white text-[#1273EB] shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Tahunan
                    </button>
                </div>

                <div x-show="rekapLoading" class="grid grid-cols-2 gap-5 mb-8">
                    <template x-for="i in 4">
                        <div class="rounded-[24px] p-6 border border-gray-100 animate-pulse bg-gray-50 h-[112px]"></div>
                    </template>
                    <div class="col-span-2 rounded-[24px] p-6 border border-gray-100 animate-pulse bg-gray-50 h-[112px]">
                    </div>
                </div>

                <div x-show="rekap[activeTab] && !rekapLoading" class="grid grid-cols-2 gap-5 mb-8">
                    {{-- Card Hadir, Terlambat, Sakit, Cuti, Libur tetap sama --}}
                    <div
                        class="bg-[#F0FFF4] border border-[#BBF7D0] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-[#BBF7D0] rounded-2xl flex items-center justify-center text-[#22C55E] border border-green-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500 font-bold text-[12px] uppercase tracking-wide mb-1">Hadir</p>
                                <h3 class="text-4xl font-bold text-[#22C55E]" x-text="rekap[activeTab]?.hadir ?? 0"></h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[#22C55E] font-bold text-[14px]" x-text="rekap[activeTab]?.p_hadir ?? '0%'"></p>
                            <p class="text-gray-400 text-[11px] font-medium">dari total</p>
                        </div>
                    </div>

                    <div
                        class="bg-[#FFF4D9] border border-[#FDE68A] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-[#FDE68A] rounded-2xl flex items-center justify-center text-[#FFB800] border border-yellow-200">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500 font-bold text-[12px] uppercase tracking-wide mb-1">Terlambat</p>
                                <h3 class="text-4xl font-bold text-[#FFB800]" x-text="rekap[activeTab]?.telat ?? 0"></h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[#FFB800] font-bold text-[14px]" x-text="rekap[activeTab]?.p_telat ?? '0%'"></p>
                            <p class="text-gray-400 text-[11px] font-medium">dari total</p>
                        </div>
                    </div>

                    <div
                        class="bg-[#FFEAEA] border border-[#FCA5A5] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-[#FCA5A5] rounded-2xl flex items-center justify-center text-[#FF4D4D] border border-red-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500 font-bold text-[12px] uppercase tracking-wide mb-1">Sakit</p>
                                <h3 class="text-4xl font-bold text-[#FF4D4D]" x-text="rekap[activeTab]?.sakit ?? 0"></h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[#FF4D4D] font-bold text-[14px]" x-text="rekap[activeTab]?.p_sakit ?? '0%'"></p>
                            <p class="text-gray-400 text-[11px] font-medium">dari total</p>
                        </div>
                    </div>

                    <div
                        class="bg-[#EAF2FF] border border-[#BFDBFE] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-[#BFDBFE] rounded-2xl flex items-center justify-center text-[#1273EB] border border-blue-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500 font-bold text-[12px] uppercase tracking-wide mb-1">Cuti</p>
                                <h3 class="text-4xl font-bold text-[#1273EB]" x-text="rekap[activeTab]?.cuti ?? 0"></h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[#1273EB] font-bold text-[14px]">-</p>
                            <p class="text-gray-400 text-[11px] font-medium">dari total</p>
                        </div>
                    </div>

                    <div
                        class="col-span-2 bg-[#F3E8FF] border border-[#D8B4FE] rounded-[24px] p-6 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-[#D8B4FE] rounded-2xl flex items-center justify-center text-[#A855F7] border border-purple-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-500 font-bold text-[12px] uppercase tracking-wide mb-1">Libur</p>
                                <h3 class="text-4xl font-bold text-[#A855F7]" x-text="rekap[activeTab]?.libur ?? 0"></h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[#A855F7] font-bold text-[14px]" x-text="rekap[activeTab]?.p_libur ?? '0%'"></p>
                            <p class="text-gray-400 text-[11px] font-medium">dari total</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <p class="text-gray-400 font-bold text-[14px]">
                        Periode: <span class="text-[#213F5C]" x-text="rekap[activeTab]?.periode ?? '...'"></span>
                    </p>
                    <button @click="openModal = false"
                        class="px-12 py-3.5 bg-[#1273EB] text-white rounded-2xl font-bold text-[15px] shadow-lg shadow-blue-200 hover:bg-[#0E62CC] transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        let timeout = null;
        const token = localStorage.getItem('access_token');

        const periodDates = @json($periodDates);
        const currentWeekOffset = "{{ $weekOffset ?? 0 }}";

        function getStatusClass(status) {
            switch (status) {
                case 'Hadir': return 'bg-[#F0FFF4] text-[#22C55E] border-[#22C55E]';
                case 'Izin Terlambat': return 'bg-[#f0f0ff5d] text-[#393947] border-[#9c9faf]';
                case 'Libur': return 'bg-[#F3E8FF] text-[#A855F7] border-[#D8B4FE]';
                case 'Terlambat': return 'bg-[#FFF4D9] text-[#FFB800] border-[#FFB800]';
                case 'Sakit': return 'bg-[#FFEAEA] text-[#FF4D4D] border-[#FF4D4D]';
                case 'Cuti': return 'bg-[#EAF2FF] text-[#1273EB] border-[#1273EB]';
                default: return 'bg-white text-gray-400 border border-dashed border-gray-300';
            }
        }

        function openManualInput(empId, empName, date, dayName, status, h, m, ampm, reason) {
            const container = document.getElementById('attendanceContainer');
            if (window.Alpine) {
                Alpine.$data(container).initForm(empId, empName, date, dayName, status, h, m, ampm, reason);
            }
        }

        async function fetchAttendances(search = '', page = 1) {
            const tbody = document.getElementById('attendanceTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const url = `/api/attendances?limit=10&search=${search}&page=${page}&weekOffset=${currentWeekOffset}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();

                console.log("=== INSPEKSI STRUKTUR DATA UTAMA ===", result);

                if (res.ok) {
                    const employees = result.data || [];
                    tbody.innerHTML = '';

                    if (employees.length === 0) {
                        tbody.innerHTML = `
                                <tr>
                                    <td colspan="8" class="py-24 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-60">
                                            <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                            <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Pegawai tidak ditemukan</h3>
                                            <p class="text-[13px] text-gray-400 font-medium">Coba cek kembali keyword pencarian atau filter lu brok.</p>
                                        </div>
                                    </td>
                                </tr>`;
                        if (fromEl) fromEl.innerText = 0;
                        if (toEl) toEl.innerText = 0;
                        if (totalEl) totalEl.innerText = 0;
                        return;
                    }

                    employees.forEach(emp => {
                        // 🕵️‍♂️ SCRIPT SNIFFER NAMA OTOMATIS
                        let rawName = '';

                        // Tahap 1: Scan properti root yang mengandung teks 'nam'
                        for (let key in emp) {
                            if (key.toLowerCase().includes('nam') && typeof emp[key] === 'string') {
                                rawName = emp[key];
                                break;
                            }
                        }
                        // Tahap 2: Scan nested object level 1 (misal emp.employee.name atau emp.karyawan.nama)
                        if (!rawName) {
                            for (let key in emp) {
                                if (emp[key] && typeof emp[key] === 'object') {
                                    for (let subKey in emp[key]) {
                                        if (subKey.toLowerCase().includes('nam') && typeof emp[key][subKey] === 'string') {
                                            rawName = emp[key][subKey];
                                            break;
                                        }
                                    }
                                }
                                if (rawName) break;
                            }
                        }

                        rawName = rawName || 'Tanpa Nama';
                        let cleanEmpName = String(rawName).replace(/'/g, "\\'");
                        let empId = emp.employee_id || emp.employees_id || emp.id || '';

                        let rowHtml = `<tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 font-bold text-[#213F5C]">${rawName}</td>`;

                        const daysKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

                        // 🕵️‍♂️ SNIFFER POLA ATTENDANCES
                        let attendancesData = emp.attendances || emp.attendance || emp.absen || emp.data_absen || {};

                        daysKeys.forEach((day, index) => {
                            const attendance = attendancesData[day] || null;
                            const dateContext = periodDates[index] ? periodDates[index].date : '';
                            const dayNameIndo = periodDates[index] ? periodDates[index].name : '';

                            let h = '', m = '', ampm = 'AM', buttonText = '+ Tambah';
                            let status = attendance ? attendance.status : '';

                            if (attendance && attendance.clock_in) {
                                const timeParts = attendance.clock_in.split(':');
                                let rawH = parseInt(timeParts[0]) || 0;
                                m = timeParts[1] || '00';
                                ampm = rawH >= 12 ? 'PM' : 'AM';

                                buttonText = `${String(rawH).padStart(2, '0')}:${m}`;

                                h = rawH % 12;
                                if (h === 0) h = 12;
                            } else if (attendance) {
                                buttonText = status;
                            }

                            let warningIcon = (status === 'Terlambat') ? `<svg class="w-3.5 h-3.5 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>` : '';
                            let currentBtnClass = getStatusClass(status);

                            const rawReason = (attendance && attendance.reason) ? attendance.reason : '';
                            let reasonClean = String(rawReason).replace(/'/g, "\\'");

                            rowHtml += `
                                        <td class="px-4 py-4 text-center">
                                            <button onclick="openManualInput('${empId}', '${cleanEmpName}', '${dateContext}', '${dayNameIndo}', '${status}', '${h}', '${m}', '${ampm}', '${reasonClean}')" 
                                                class="w-full inline-block py-2 px-3 rounded-xl text-[12px] font-bold border transition-all ${currentBtnClass}">
                                                <span class="flex items-center justify-center gap-1">
                                                    ${warningIcon} ${buttonText}
                                                </span>
                                            </button>
                                        </td>`;
                        });

                        rowHtml += `</tr>`;
                        tbody.innerHTML += rowHtml;
                    });

                    if (fromEl) fromEl.innerText = result.from || (employees.length ? 1 : 0);
                    if (toEl) toEl.innerText = result.to || employees.length;
                    if (totalEl) totalEl.innerText = result.total || employees.length;

                    if (typeof renderPaginationControls === 'function') {
                        const paginationObject = result.total ? result : {
                            current_page: 1,
                            last_page: 1,
                            per_page: 10,
                            total: employees.length
                        };
                        renderPaginationControls(paginationObject, (p) => fetchAttendances(document.getElementById('searchInput').value, p));
                    }
                }
            } catch (e) {
                console.error("Detail Error Sesuatu:", e);
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-10 text-red-500 font-bold">Gagal memuat laporan absensi, cek API!</td></tr>';
            }
        }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchAttendances(e.target.value, 1);
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetchAttendances();
        });
    </script>
@endsection