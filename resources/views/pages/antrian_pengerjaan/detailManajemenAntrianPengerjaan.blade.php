{{-- resources/views/pages/antrian_pengerjaan/detailManajemenAntrianPengerjaan.blade.php --}}
{{--
    TODO Backend:
    - Kirim $antrian (Eloquent model) dari Controller ke view ini
    - Endpoint ubah status: PUT/PATCH /api/antrian-pengerjaan/{id}/status
    - Endpoint hapus: DELETE /api/antrian-pengerjaan/{id}
    - $antrian->suku_cadang → relasi ke tabel antrian_suku_cadang
--}}
@extends('layouts.master')

@section('title', 'Detail Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

@section('content')
@php
    // =============================================
    // TODO Backend: hapus $dummy ini, ganti dengan
    // $antrian yang dikirim dari Controller
    // =============================================
    $dummy = [
        'id'            => 1,
        'name'          => 'Edsel Septa Haryanto',
        'phone'         => '085155030650',
        'address'       => 'Komplek Taman Bumi Prima Blok O no 8, Kecamatan Cibabat, Kelurahan Cimahi Utara, Kota Cimahi',
        'car_model'     => 'BMW E46 318i',
        'engine_code'   => 'N42',
        'km_masuk'      => '180.000 Km',
        'license_plate' => 'B 1080 JAW',
        'status'        => 'Pengecekan',
        'created_by'    => 'Edsel Septa Haryanto',
        'created_at'    => '27 Januari 2025, 08:00',
        'updated_at'    => '27 Januari 2025, 09:00',
        'suku_cadang'   => [
            [
                'id'       => 1,
                'nama'     => 'Q8 Oils 5W40 excel 5 liter',
                'deskripsi'=> 'oli mesin bmw',
                'harga'    => 'Rp 700.000',
                'jumlah'   => '1 pcs',
                'tanggal'  => '01 Januari 2025',
                'supplier' => 'Milan Motors',
            ],
        ],
    ];
    $antrian = $dummy; // TODO Backend: hapus baris ini
@endphp

<div class="block w-full space-y-6">

    {{-- Card Judul --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm w-full">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#EAF2FF] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-[#213F5C]">Detail Mobil Masuk</h1>
            </div>
            <a href="{{ route('antrian-pengerjaan.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-[#213F5C] font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke List
            </a>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="grid grid-cols-12 gap-6 pb-10 w-full">

        {{-- Kolom Kiri --}}
        <div class="col-span-9 space-y-6">

            {{-- Section 1: Informasi Pemilik Kendaraan --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h2>
                </div>
                <div class="p-8 space-y-4">
                    @foreach ([
                        'Nama Lengkap'   => $antrian['name'],
                        'Nomor Telepon'  => $antrian['phone'],
                        'Alamat'         => $antrian['address'],
                    ] as $label => $value)
                        <div class="flex items-start gap-4">
                            <span class="w-36 text-[13px] text-gray-400 font-semibold shrink-0">{{ $label }}</span>
                            <span class="text-[13px] font-bold text-[#213F5C]">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Section 2: Informasi Mobil Pelanggan --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16H6l-2-6h15l-1 4M3 11l1-4h14" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2] p-5">
                        @foreach ([
                            'Model Mobil'   => $antrian['car_model'],
                            'Km Masuk'      => $antrian['km_masuk'],
                            'Kode Mesin'    => $antrian['engine_code'],
                            'Nomor Polisi'  => $antrian['license_plate'],
                        ] as $label => $value)
                            <div class="flex items-center gap-2">
                                <span class="text-[13px] font-bold text-[#213F5C]">{{ $label }} :</span>
                                <span class="text-[13px] text-[#213F5C]">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section 3: Penggunaan Suku Cadang --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h2>
                </div>
                <div class="p-6 space-y-3">
                    @forelse ($antrian['suku_cadang'] as $sc)
                        <div class="flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2]">
                            <div>
                                <p class="text-[13px] font-bold text-[#213F5C]">{{ $sc['nama'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $sc['deskripsi'] }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-[13px] font-bold text-[#213F5C]">{{ $sc['harga'] }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $sc['jumlah'] }} • {{ $sc['tanggal'] }}</p>
                                    <p class="text-[11px] text-gray-400">Supplier: {{ $sc['supplier'] }}</p>
                                </div>
                                {{-- Edit & Hapus — TODO Backend: hubungkan ke endpoint masing-masing --}}
                                <button type="button"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#EAF2FF] border border-[#B1D3FF] text-[#1273EB] hover:bg-[#D4E8FF] transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </button>
                                <button type="button"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-[13px] text-gray-400 text-center py-4">Belum ada suku cadang yang digunakan.</p>
                    @endforelse
                </div>
            </div>

        </div>{{-- end kolom kiri --}}

        {{-- Kolom Kanan --}}
        <div class="col-span-3 space-y-4">

            {{-- Quick Info --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-50">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="font-bold text-[#213F5C] text-[15px]">Quick Info</h3>
                </div>

                {{-- Created By --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Created By</p>
                    <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                        <div class="user-initial-box w-9 h-9 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[12px]">
                            {{ strtoupper(substr($antrian['created_by'], 0, 1)) }}
                        </div>
                        <p class="text-[13px] font-bold text-[#213F5C] truncate">{{ $antrian['created_by'] }}</p>
                    </div>
                </div>

                {{-- Created Date --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Created Date</p>
                    <p class="text-[13px] font-bold text-[#213F5C]">{{ $antrian['created_at'] }}</p>
                </div>

                {{-- Last Updated --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Last Updated</p>
                    <p class="text-[13px] font-bold text-[#213F5C]">{{ $antrian['updated_at'] }}</p>
                </div>

                {{-- Ubah Status --}}
                <div class="pt-1">
                    <p class="text-[11px] font-bold text-[#1273EB] mb-2 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1273EB] inline-block"></span>
                        Ubah Status
                    </p>
                    {{-- Custom styled select --}}
                    <div class="relative" id="statusWrapper">
                        <select id="statusSelect"
                            data-antrian-id="{{ $antrian['id'] }}"
                            data-current="{{ $antrian['status'] }}"
                            class="w-full px-4 py-3 rounded-xl border-2 font-bold text-[14px] outline-none appearance-none cursor-pointer transition-all">
                            <option value="Pengecekan">Pengecekan</option>
                            <option value="Dalam Proses">Dalam Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <svg class="w-4 h-4" id="statusChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                {{-- Proses Pembayaran — hanya aktif kalau status Selesai --}}
                <button type="button" id="btnProsesPembayaran"
                    class="w-full flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-[15px] transition-all"
                    onclick="handleProsesPembayaran()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Proses Pembayaran
                </button>

                <a href="{{ route('antrian-pengerjaan.edit', $antrian['id']) }}"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit Data
                </a>

                <button type="button" id="btnHapus"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FF4D4D] text-white rounded-xl font-bold text-[15px] hover:bg-[#E53E3E] transition-all"
                    onclick="handleHapus({{ $antrian['id'] }})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Data
                </button>

                <a href="{{ route('antrian-pengerjaan.index') }}"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FFF5F5] text-[#FF4D4D] border border-[#FFE0E0] rounded-xl font-bold text-[15px] hover:bg-[#FFEBEB] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
            </div>

        </div>{{-- end kolom kanan --}}

    </div>
</div>

<script>
    // ── Config warna per status ───────────────────────────────────────────────
    const statusConfig = {
        'Pengecekan'   : { border: '#FDE68A', bg: '#FFF8EC', text: '#F59E0B', chevron: '#F59E0B' },
        'Dalam Proses' : { border: '#B1D3FF', bg: '#EAF2FF', text: '#1273EB', chevron: '#1273EB' },
        'Selesai'      : { border: '#A7F3D0', bg: '#EDFBF3', text: '#16A34A', chevron: '#16A34A' },
    };

    const select        = document.getElementById('statusSelect');
    const btnPembayaran = document.getElementById('btnProsesPembayaran');

    // Set nilai awal dari PHP
    select.value = select.dataset.current;
    applyStatusStyle(select.value);

    select.addEventListener('change', async () => {
        const newStatus  = select.value;
        const antrianId  = select.dataset.antrianId;
        const token      = localStorage.getItem('access_token');

        applyStatusStyle(newStatus);
        updatePembayaranBtn(newStatus);

        try {
            // TODO Backend: sesuaikan endpoint API-nya
            const response = await fetch(`/api/antrian-pengerjaan/${antrianId}/status`, {
                method : 'PATCH',
                headers: {
                    'Accept'       : 'application/json',
                    'Content-Type' : 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (!response.ok) throw new Error('Gagal update status');

            Swal.fire({
                icon : 'success',
                title: 'Status diperbarui!',
                text : `Status berhasil diubah ke "${newStatus}"`,
                timer: 1800,
                showConfirmButton: false
            });
        } catch (err) {
            Swal.fire('Error', 'Gagal mengubah status. Coba lagi.', 'error');
            // Kembalikan ke status sebelumnya jika gagal
            select.value = select.dataset.current;
            applyStatusStyle(select.dataset.current);
        }
    });

    function applyStatusStyle(status) {
        const cfg = statusConfig[status] || statusConfig['Pengecekan'];
        select.style.borderColor      = cfg.border;
        select.style.backgroundColor  = cfg.bg;
        select.style.color            = cfg.text;
        document.getElementById('statusChevron').style.color = cfg.chevron;
        updatePembayaranBtn(status);
    }

    function updatePembayaranBtn(status) {
        if (status === 'Selesai') {
            btnPembayaran.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.add('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = false;
        } else {
            btnPembayaran.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.remove('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = true;
        }
    }

    function handleProsesPembayaran() {
        // TODO Backend: arahkan ke route proses pembayaran
        Swal.fire({ icon: 'info', title: 'Proses Pembayaran', text: 'Fitur ini belum tersedia.' });
    }

    function handleHapus(id) {
        const token = localStorage.getItem('access_token');
        Swal.fire({
            title             : 'Hapus Data?',
            text              : 'Data antrian ini akan dihapus permanen.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#FF4D4D',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Hapus!',
        }).then(async (result) => {
            if (!result.isConfirmed) return;
            try {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                // TODO Backend: sesuaikan endpoint API-nya
                const response = await fetch(`/api/antrian-pengerjaan/${id}`, {
                    method : 'DELETE',
                    headers: {
                        'Accept'       : 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (!response.ok) throw new Error();
                await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            } catch {
                Swal.fire('Error', 'Gagal menghapus data.', 'error');
            }
        });
    }

    // ── Quick Info dari localStorage ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const name = localStorage.getItem('user_name') || 'User';
        const role = localStorage.getItem('user_role') || 'Staff';
        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
        document.querySelectorAll('.user-initial-box').forEach(el => el.innerText = name.charAt(0).toUpperCase());
    });
</script>
@endsection