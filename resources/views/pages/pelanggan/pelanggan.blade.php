@extends('layouts.master')

@section('title', 'Data Pelanggan')
@section('title_header', 'Data Pelanggan')

@section('table_header')
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nama</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nomor Telepon</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Alamat Pengguna</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nomor Polisi</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Model Mobil</th>
    <th class="px-6 py-5 text-center text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Aksi</th>
@endsection

@section('table_body')
    <tbody id="customerTableBody">
        <tr>
            <td colspan="6" class="text-center py-20 text-gray-400 italic font-medium">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-[#1273EB] border-t-transparent rounded-full animate-spin"></div>
                    Memuat data pelanggan...
                </div>
            </td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder'     => 'Cari Pelanggan...',
        'filterModalId'   => 'modalFilterPelanggan',
        'addUrl'          => route('pelanggan.create'),
        'btnText'         => 'Tambah Pelanggan',
        'exportExcelUrl'  => route('pelanggan.export'),
        'exportPdfUrl'    => route('pelanggan.export.pdf'),
    ])

    {{-- Script: sembunyikan tombol tambah untuk role CEO --}}
    <script>
        (function() {
            const role = (localStorage.getItem('user_role') || '').toLowerCase();
            if (role === 'ceo') {
                document.addEventListener('DOMContentLoaded', function() {
                    const addBtn = document.querySelector('a[href="{{ route('pelanggan.create') }}"]');
                    if (addBtn) addBtn.style.display = 'none';
                });
            }
        })();
    </script>

    @include('layouts.table_wrapper')

    {{-- MODAL FILTER --}}
    <div id="modalFilterPelanggan" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalFilterPelanggan')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#213F5C]">Filter Pelanggan</h3>
                    <button onclick="toggleModal('modalFilterPelanggan')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Model Mobil</label>
                        <select id="filterCarType" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Model</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Seri</label>
                        <select id="filterSeries" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Seri</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-5 bg-gray-50 flex gap-3">
                    <button onclick="resetFilter()"
                        class="flex-1 py-3 bg-white border border-[#D9E2EC] text-[#627D98] font-bold rounded-xl text-[14px]">
                        Reset
                    </button>
                    <button onclick="applyFilter()"
                        class="flex-1 py-3 bg-[#1273EB] text-white font-bold rounded-xl text-[14px]">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('access_token');
        let timeout = null;

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.toggle('hidden');
        }

        async function fetchCustomers(search = '', car_type_id = '', series = '', page = 1) {
            const tbody = document.getElementById('customerTableBody');

            try {
                const res = await fetch(
                    `/api/customers?limit=10&search=${search}&car_type_id=${car_type_id}&series=${series}&page=${page}`,
                    { headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` } }
                );
                const result = await res.json();

                if (res.ok) {
                    tbody.innerHTML = '';
                    const items = result.data || [];

                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-20 h-20 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C]">Pelanggan tidak ditemukan brok</h3>
                                        <p class="text-[13px] text-gray-400">Coba cari dengan nama atau nomor telepon lain.</p>
                                    </div>
                                </td>
                            </tr>`;
                        renderPaginationControls({ from: 0, to: 0, total: 0, current_page: 1, last_page: 1 }, () => {});
                        return;
                    }

                    items.forEach(c => {
                        const plates = c.vehicles.length > 0
                            ? c.vehicles.map(v => `<div class="mb-1 last:mb-0">${v.license_plate}</div>`).join('')
                            : '<span class="text-gray-300">-</span>';

                        const models = c.vehicles.length > 0
                            ? c.vehicles.map(v => `<div class="mb-1 last:mb-0 font-bold">${v.model || (v.car_type ? v.car_type.name : '-')}</div>`).join('')
                            : '<span class="text-gray-300">-</span>';

                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors border-b border-gray-50 last:border-0 group text-[14px]">
                                <td class="px-6 py-5 font-bold text-[#213F5C]">${c.name}</td>
                                <td class="px-6 py-5 text-[#213F5C] font-semibold">${c.phone_number}</td>
                                <td class="px-6 py-5 text-[#627D98] max-w-[200px] truncate font-medium">${c.address}</td>
                                <td class="px-6 py-5 text-[#213F5C] font-bold whitespace-nowrap">${plates}</td>
                                <td class="px-6 py-5 text-[#213F5C] whitespace-nowrap text-[13px]">${models}</td>
                                <td class="px-6 py-4.5 text-center">
                                    <a href="/pelanggan/detail/${c.customer_id}"
                                        onclick="goToDetail(event, ${c.customer_id})"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>`;
                    });

                    renderPaginationControls(result, (p) => fetchCustomers(search, car_type_id, series, p));
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="py-10 text-center text-red-500 font-bold">API bermasalah brok, cek log!</td></tr>';
            }
        }

        // Search dengan debounce — bawa nilai filter yang aktif
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const car_type_id = document.getElementById('filterCarType').value;
                const series = document.getElementById('filterSeries').value;
                fetchCustomers(e.target.value, car_type_id, series, 1);
            }, 500);
        });

        function applyFilter() {
            const search = document.getElementById('searchInput').value;
            const car_type_id = document.getElementById('filterCarType').value;
            const series = document.getElementById('filterSeries').value;
            fetchCustomers(search, car_type_id, series, 1);
            toggleModal('modalFilterPelanggan');
        }

        function resetFilter() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterCarType').value = '';
            document.getElementById('filterSeries').value = '';
            fetchCustomers();
            toggleModal('modalFilterPelanggan');
        }

        // Load dropdown options untuk modal filter
        async function loadOptions() {
            try {
                const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

                // Load model mobil
                const resCarTypes = await fetch('/api/car-types?limit=200', { headers });
                const resultCarTypes = await resCarTypes.json();
                if (resCarTypes.ok) {
                    const select = document.getElementById('filterCarType');
                    (resultCarTypes.data || []).forEach(ct => {
                        const opt = document.createElement('option');
                        opt.value = ct.car_type_id;
                        opt.text = ct.name;
                        select.appendChild(opt);
                    });
                }

                // Load seri
                const resSeries = await fetch('/api/car-series', { headers });
                const resultSeries = await resSeries.json();
                if (resSeries.ok) {
                    const select = document.getElementById('filterSeries');
                    (resultSeries.data || []).forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s;
                        opt.text = s;
                        select.appendChild(opt);
                    });
                }
            } catch (e) {
                console.error('Gagal load filter options:', e);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadOptions();
            fetchCustomers();
        });
    </script>
@endsection