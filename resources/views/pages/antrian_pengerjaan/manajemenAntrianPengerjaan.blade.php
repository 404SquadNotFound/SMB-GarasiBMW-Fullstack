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
    <tbody id="antrianTableBody">
        <tr>
            <td colspan="6" class="text-center py-10 text-gray-400 italic">Memuat data antrian...</td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Antrian Pengerjaan...',
        'addUrl'      => route('antrian-pengerjaan.create'),
        'btnText'     => 'Tambah Antrian',
    ])
    {{-- Script: sembunyikan tombol tambah untuk role CEO --}}
    <script>
        (function() {
            const role = (localStorage.getItem('user_role') || '').toLowerCase();
            if (role === 'ceo') {
                document.addEventListener('DOMContentLoaded', function() {
                    const addBtn = document.querySelector('a[href="{{ route('antrian-pengerjaan.create') }}"]');
                    if (addBtn) addBtn.style.display = 'none';
                });
            }
        })();
    </script>
    @include('layouts.table_wrapper')

    <script>
        const token = localStorage.getItem('access_token');
        let debounceTimeout = null;

        const statusConfigMap = {
            'pengecekan'  : { bg: 'bg-[#FFF8EC]', text: 'text-[#F59E0B]', border: 'border-[#FDE68A]', label: 'Pengecekan'   },
            'menunggu'    : { bg: 'bg-[#F5F5F5]', text: 'text-[#6B7280]', border: 'border-[#E5E7EB]', label: 'Menunggu'     },
            'dikerjakan'  : { bg: 'bg-[#EAF2FF]', text: 'text-[#1273EB]', border: 'border-[#B1D3FF]', label: 'Dikerjakan'   },
            'dibatalkan'  : { bg: 'bg-[#FFF5F5]', text: 'text-[#FF4D4D]', border: 'border-[#FFE0E0]', label: 'Dibatalkan'   },
            'selesai'     : { bg: 'bg-[#EDFBF3]', text: 'text-[#16A34A]', border: 'border-[#A7F3D0]', label: 'Selesai'      },
        };

        async function fetchAntrian(search = '', page = 1) {
            const tbody = document.getElementById('antrianTableBody');
            const fromEl  = document.getElementById('paginationFrom');
            const toEl    = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            try {
                const params = new URLSearchParams({ limit: 10, search, page });
                const res = await fetch(`/api/transactions?${params}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();

                if (!res.ok) {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data: ${result.message ?? ''}</td></tr>`;
                    return;
                }

                const items = result.data ?? [];
                tbody.innerHTML = '';

                if (items.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-60">
                                    <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-7.5 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0016.5 4.5h-15A2.25 2.25 0 001.5 6.75v10.5A2.25 2.25 0 003.75 19.5z" />
                                    </svg>
                                    <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Belum ada antrian pengerjaan</h3>
                                    <p class="text-[13px] text-gray-400 font-medium">Klik "Tambah Antrian" untuk mendaftarkan mobil baru.</p>
                                </div>
                            </td>
                        </tr>`;
                    renderPaginationControls({ from: 0, to: 0, total: 0, current_page: 1, last_page: 1 }, () => {});
                    return;
                }

                items.forEach(item => {
                    const customer = item.vehicle?.customer ?? {};
                    const vehicle  = item.vehicle ?? {};
                    const status   = item.status_service ?? 'menunggu';
                    const cfg      = statusConfigMap[status] ?? statusConfigMap['menunggu'];

                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-[#F9FCFF] transition-colors group';
                    tr.innerHTML = `
                        <td class="px-6 py-[18px] font-bold text-[#213F5C]">${escHtml(customer.name ?? '-')}</td>
                        <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(customer.phone_number ?? '-')}</td>
                        <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(vehicle.license_plate ?? '-')}</td>
                        <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(vehicle.model ?? '-')}</td>
                        <td class="px-6 py-[18px] text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border ${cfg.bg} ${cfg.text} ${cfg.border}">
                                ${escHtml(cfg.label)}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 text-center">
                            <a href="{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', ${item.transaction_id})
                                onclick="goToDetail(event, ${item.transaction_id})"
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

                renderPaginationControls(result, (p) => fetchAntrian(search, p));

            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi!</td></tr>';
            }
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str ?? ''));
            return d.innerHTML;
        }

        function goToDetail(e, id) {
            e.preventDefault();
            sessionStorage.setItem('currentAntrianId', id);
            window.location.href = "{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', id);
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchAntrian();

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => fetchAntrian(searchInput.value, 1), 500);
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Hide Filter button
            const filterBtn = document.querySelector('button[onclick^="toggleModal"]');
            if (filterBtn) filterBtn.style.display = 'none';

            // Hide Export button wrapper
            document.querySelectorAll('button').forEach(btn => {
                if (btn.textContent.includes('Export')) {
                    const wrapper = btn.closest('div.relative');
                    if (wrapper) wrapper.style.display = 'none';
                }
            });
        });
    </script>
@endsection
