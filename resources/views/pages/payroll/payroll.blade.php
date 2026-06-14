@extends('layouts.master')

@section('title', 'Penggajian')

@section('title_header', 'Penggajian')

@section('table_header')
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Nama Karyawan</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Pendapatan</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Penalti</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Tabungan</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px] uppercase tracking-wide">ROLE</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px] uppercase tracking-wide text-center">ACTION</th>
@endsection

@section('table_body')
    @php
        // Sementara masih data dummy, nanti ini dihapus jika sudah pakai data dari Controller
        $data = [
            ['id' => 1, 'nama' => 'Edsel Septa Haryanto', 'pendapatan' => 'Rp. 10.000.000', 'penalti' => 'Rp. 100.000', 'tabungan' => 'Rp. 8.100.000', 'role' => 'Developer'],
            ['id' => 2, 'nama' => 'John Doe',              'pendapatan' => 'Rp. 2.000.000',  'penalti' => 'Rp. 20.000',  'tabungan' => 'Rp. 200.000',   'role' => 'Manager'],
            ['id' => 3, 'nama' => 'Jane Smith',             'pendapatan' => 'Rp. 3.000.000',  'penalti' => 'Rp. 30.000',  'tabungan' => 'Rp. 3.000.000',  'role' => 'Designer'],
            ['id' => 4, 'nama' => 'Ahmad Ridho',            'pendapatan' => 'Rp. 5.000.000',  'penalti' => 'Rp. 50.000',  'tabungan' => 'Rp. 50.000',     'role' => 'Technician'],
        ];

        // Warna badge per role
        $roleBadgeColors = [
            'Developer'  => 'bg-[#EAF2FF] text-[#1273EB] border-[#B1D3FF]',
            'Manager'    => 'bg-[#FFF4E5] text-[#E07B00] border-[#FFD89B]',
            'Designer'   => 'bg-[#F0FFF4] text-[#1A7F3C] border-[#A7E3BE]',
            'Technician' => 'bg-[#F3F4FF] text-[#5A5FDE] border-[#C5C8FF]',
        ];
    @endphp

    @foreach ($data as $item)
        @php
            $badgeClass = $roleBadgeColors[$item['role']] ?? 'bg-gray-100 text-gray-600 border-gray-300';
        @endphp
        <tr class="hover:bg-[#F9FCFF] transition-colors group">
            <td class="px-6 py-[18px] font-bold text-[#213F5C] text-[13px]">{{ $item['nama'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['pendapatan'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['penalti'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['tabungan'] }}</td>
            <td class="px-6 py-[18px]">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-semibold border {{ $badgeClass }}">
                    {{ $item['role'] }}
                </span>
            </td>
            <td class="px-6 py-[18px] text-center">
                <a href="{{ route('payroll.show', $item['id']) }}"
                   onclick="sessionStorage.setItem('currentPayrollId', '{{ $item['id'] }}')"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all no-underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Detail
                </a>
            </td>
        </tr>
    @endforeach
@endsection

@section('content')

    {{-- Action Bar --}}
    @include('layouts.action_bar', [
        'placeholder'    => 'Cari Akun',
        'searchUrl'      => '#',
        'filterModalId'  => 'modalFilterPayroll',
        'exportExcelUrl' => '#',
        'exportPdfUrl'   => '#',
        'addUrl'         => route('payroll.create'),
        'btnText'        => 'Tambah Daftar Gaji',
    ])

    {{-- Sembunyikan tombol tambah untuk role CEO --}}
    <script>
        (function () {
            const role = (localStorage.getItem('user_role') || '').toLowerCase();
            if (role === 'ceo') {
                document.addEventListener('DOMContentLoaded', function () {
                    const addBtn = document.querySelector('a[href="{{ route('payroll.create') }}"]');
                    if (addBtn) addBtn.style.display = 'none';
                });
            }
        })();
    </script>

    {{-- ===== Periode Gaji Banner ===== --}}
    <div class="w-full bg-[#4B7BEC] rounded-2xl px-7 py-5 flex items-center justify-between mb-5"
         x-data="{
             currentMonth: {{ now()->month }},
             currentYear:  {{ now()->year }},
             get label() {
                 const months = ['Januari','Februari','Maret','April','Mei','Juni',
                                 'Juli','Agustus','September','Oktober','November','Desember'];
                 return months[this.currentMonth - 1] + ' ' + this.currentYear;
             },
             prev() {
                 if (this.currentMonth === 1) { this.currentMonth = 12; this.currentYear--; }
                 else { this.currentMonth--; }
             },
             next() {
                 if (this.currentMonth === 12) { this.currentMonth = 1; this.currentYear++; }
                 else { this.currentMonth++; }
             }
         }">

        {{-- Kiri: ikon + label --}}
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-semibold uppercase tracking-widest">Periode Gaji</p>
                <p class="text-white text-[22px] font-bold leading-tight" x-text="label"></p>
            </div>
        </div>

        {{-- Kanan: navigasi bulan --}}
        <div class="flex items-center gap-2">
            <button @click="prev"
                class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <span class="px-5 py-2 bg-white text-[#4B7BEC] rounded-full text-[13px] font-bold min-w-[110px] text-center"
                  x-text="label"></span>

            <button @click="next"
                class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
    {{-- ===== End Periode Gaji Banner ===== --}}

    {{-- Table --}}
    @include('layouts.table_wrapper', [
        'from'  => isset($payrolls) ? ($payrolls->firstItem() ?? 0) : 1,
        'to'    => isset($payrolls) ? ($payrolls->lastItem()  ?? 0) : count($data),
        'total' => isset($payrolls) ? ($payrolls->total()     ?? 0) : count($data),
    ])

@endsection