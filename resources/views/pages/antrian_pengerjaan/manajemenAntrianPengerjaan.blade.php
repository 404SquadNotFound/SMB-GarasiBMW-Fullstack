{{-- resources/views/layanan-servis/antrian-pengerjaan/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

@section('table_header')
    <th class="px-6 py-5">Nama</th>
    <th class="px-6 py-5">Nomor Telepon</th>
    <th class="px-6 py-5">Nomor Polisi</th>
    <th class="px-6 py-5">Model Mobil</th>
    <th class="px-6 py-5 text-center">Status</th>
    <th class="px-6 py-5 text-center">Aksi</th>
@endsection

@section('table_body')
    {{-- Diisi oleh JavaScript dari localStorage --}}
    <tr id="emptyRow" class="hidden">
        <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-[13px]">Belum ada data antrian.</td>
    </tr>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Antrian Pengerjaan...',
        'addUrl' => route('antrian-pengerjaan.create'),
        'btnText' => 'Tambah Antrian',
    ])
    @include('layouts.table_wrapper', [
        'from' => 1,
        'to' => 3,
        'total' => 3,
    ])

    <script>
        // ── Inisialisasi data dummy ke localStorage (hanya sekali) ────────────────
        (function initDummyData() {
            const existing = localStorage.getItem('antrianList');
            if (!existing) {
                const dummy = [{
                        id: 1,
                        name: 'Edsel Septa Haryanto',
                        phone: '085155030650',
                        license_plate: 'B 1040 JAW',
                        car_model: 'BMW E46 318i',
                        status: 'Pengecekan',
                        address: 'Komplek Taman Bumi Prima Blok O no 8, Kecamatan Cibabat, Kelurahan Cimahi Utara, Kota Cimahi',
                        engine_code: 'N42',
                        km_masuk: '180.000 Km',
                        created_by: 'Edsel Septa Haryanto',
                        created_at: '27 Januari 2025, 08:00',
                        updated_at: '27 Januari 2025, 09:00',
                        suku_cadang: [{
                            id: 1,
                            nama: 'Q8 Oils 5W40 excel 5 liter',
                            deskripsi: 'oli mesin bmw',
                            harga: 'Rp 700.000',
                            jumlah: '1 pcs',
                            tanggal: '01 Januari 2025',
                            supplier: 'Milan Motors',
                        }],
                    },
                    {
                        id: 2,
                        name: 'Abdul Aziz Saepurohmat',
                        phone: '081250353492',
                        license_plate: 'D 1015 PRT',
                        car_model: 'BMW M3 GTR',
                        status: 'Dalam Proses',
                        address: 'Jl. Sudirman No. 45, Jakarta Selatan',
                        engine_code: 'S54',
                        km_masuk: '95.000 Km',
                        created_by: 'Abdul Aziz Saepurohmat',
                        created_at: '28 Januari 2025, 10:00',
                        updated_at: '28 Januari 2025, 11:00',
                        suku_cadang: [],
                    },
                    {
                        id: 3,
                        name: 'Reza Indra Maulana',
                        phone: '081345304293',
                        license_plate: 'H 5090 TI',
                        car_model: 'BMW M Hybrid V8',
                        status: 'Selesai',
                        address: 'Jl. Pahlawan No. 12, Semarang',
                        engine_code: 'P63',
                        km_masuk: '50.000 Km',
                        created_by: 'Reza Indra Maulana',
                        created_at: '29 Januari 2025, 13:00',
                        updated_at: '29 Januari 2025, 14:00',
                        suku_cadang: [],
                    },
                ];
                localStorage.setItem('antrianList', JSON.stringify(dummy));
            }
        })();

        // ── Render tabel dari localStorage ───────────────────────────────────────
        function renderTable(filterText) {
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const tbody = document.querySelector('tbody');
            const emptyRow = document.getElementById('emptyRow');

            // Hapus semua baris kecuali emptyRow
            Array.from(tbody.querySelectorAll('tr:not(#emptyRow)')).forEach(r => r.remove());

            const filtered = filterText ?
                list.filter(item =>
                    item.name.toLowerCase().includes(filterText.toLowerCase()) ||
                    item.phone.includes(filterText) ||
                    item.license_plate.toLowerCase().includes(filterText.toLowerCase()) ||
                    item.car_model.toLowerCase().includes(filterText.toLowerCase())
                ) :
                list;

            if (filtered.length === 0) {
                emptyRow.classList.remove('hidden');
                return;
            }

            emptyRow.classList.add('hidden');

            const statusConfigMap = {
                'Pengecekan': {
                    bg: 'bg-[#FFF8EC]',
                    text: 'text-[#F59E0B]',
                    border: 'border-[#FDE68A]'
                },
                'Dalam Proses': {
                    bg: 'bg-[#EAF2FF]',
                    text: 'text-[#1273EB]',
                    border: 'border-[#B1D3FF]'
                },
                'Selesai': {
                    bg: 'bg-[#EDFBF3]',
                    text: 'text-[#16A34A]',
                    border: 'border-[#A7F3D0]'
                },
            };

            filtered.forEach(item => {
                const cfg = statusConfigMap[item.status] || {
                    bg: 'bg-[#F5F5F5]',
                    text: 'text-[#6B7280]',
                    border: 'border-[#E5E7EB]'
                };
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-[#F9FCFF] transition-colors group';
                tr.innerHTML = `
                    <td class="px-6 py-[18px] font-bold text-[#213F5C]">${escHtml(item.name)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.phone)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.license_plate)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.car_model)}</td>
                    <td class="px-6 py-[18px] text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border ${cfg.bg} ${cfg.text} ${cfg.border}">
                            ${escHtml(item.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4.5 text-center">
                        <a href="/antrian-pengerjaan/${item.id}"
                            onclick="goToDetail(event, ${item.id})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Detail
                        </a>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Update info "Showing x to y of z"
            updatePaginationInfo(filtered.length);
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function updatePaginationInfo(total) {
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (fromEl) fromEl.textContent = total > 0 ? 1 : 0;
            if (toEl) toEl.textContent = total;
            if (totalEl) totalEl.textContent = total;
        }

        // ── Navigasi ke halaman detail (pakai sessionStorage untuk passing ID) ───
        function goToDetail(e, id) {
            e.preventDefault();
            sessionStorage.setItem('currentAntrianId', id);
            // Navigasi ke halaman detail — sesuaikan path jika routing berbeda
            window.location.href = "{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', id);
        }

        // ── Search / filter ───────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            renderTable();

            const searchInput = document.querySelector('input[placeholder="Cari Antrian Pengerjaan..."]');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    renderTable(searchInput.value);
                });
            }
        });
    </script>
@endsection
