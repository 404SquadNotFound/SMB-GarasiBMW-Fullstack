@extends('layouts.master')

@section('title', 'Jenis Mesin')
@section('title_header', 'Master Data | Jenis Mesin')

{{-- 1. WAJIB ADA HEADER TABEL --}}
@section('table_header')
    <th class="px-6 py-5">Kode Mesin</th>
    <th class="px-6 py-5">Konfigurasi Silinder</th>
    <th class="px-6 py-5">Kapasitas Oli</th>
    <th class="px-6 py-5">Bahan Bakar</th>
    <th class="px-6 py-5">Kapasitas Mesin</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

{{-- 2. WAJIB ADA BODY TABEL DENGAN ID YANG SESUAI SCRIPT --}}
@section('table_body')
    <tbody id="engineTableBody">
        <tr>
            <td colspan="6" class="text-center py-10 text-gray-400 italic">Memuat data mesin...</td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Jenis Mesin...',
        'filterModalId' => 'modalFilterMesin',
        'addUrl' => route('jenis-mesin.create'),
        'btnText' => 'Tambah Jenis Mesin'
    ])

    @include('layouts.table_wrapper')

    {{-- MODAL FILTER MESIN --}}
    <div id="modalFilterMesin" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalFilterMesin')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#213F5C]">Filter Jenis Mesin</h3>
                    <button onclick="toggleModal('modalFilterMesin')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Bahan Bakar</label>
                        <select id="filterFuel" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Bahan Bakar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Konfigurasi Silinder</label>
                        <select id="filterCylinders" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Konfigurasi</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-5 bg-gray-50 flex gap-3">
                    <button onclick="resetFilter()" class="flex-1 py-3 bg-white border border-[#D9E2EC] text-[#627D98] font-bold rounded-xl text-[14px] hover:bg-gray-100 transition-all">Reset</button>
                    <button onclick="applyFilter()" class="flex-1 py-3 bg-[#1273EB] text-white font-bold rounded-xl text-[14px] hover:bg-[#0E62CC] transition-all shadow-lg shadow-blue-100">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timeout = null;
        let currentSearch = '';
        const token = localStorage.getItem('access_token');

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.toggle('hidden');
        }

        // 1. Fetch Data Utama
        async function fetchEngineTypes(search = '', fuel = '', cylinders = '') {
            const tbody = document.getElementById('engineTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const url = `/api/engine-types?limit=10&search=${search}&fuel_type=${fuel}&cylinders=${cylinders}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                
                const result = await res.json();

                if (res.ok) {
                    const items = result.data || [];
                    tbody.innerHTML = '';

                    // LOGIC EMPTY STATE
                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0l-1.65-3.712A2.25 2.25 0 0016.597 2.5H7.403a2.25 2.25 0 00-2.003 1.288L3.75 7.5m16.5 0H3.75m16.5 0v1.5a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V7.5m10.5-1.125h.008v.008h-.008V6.375zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Data mesin tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cek keyword pencarian lu atau tambahin data baru.</p>
                                    </div>
                                </td>
                            </tr>`;
                        
                        if(fromEl) fromEl.innerText = 0; 
                        if(toEl) toEl.innerText = 0; 
                        if(totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // RENDER DATA (Kalo ada)
                    items.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.name}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.cylinders}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.oil_cap} Liter</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">
                                    <span class="px-2.5 py-1 rounded-md ${item.fuel_type === 'Bensin' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600'} text-[11px] font-bold">${item.fuel_type}</span>
                                </td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.engine_cap} cc</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/master-data/jenis-mesin/detail/${item.engine_type_id}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">Detail</a>
                                </td>
                            </tr>`;
                    });

                    if(fromEl) fromEl.innerText = result.from || 0;
                    if(toEl) toEl.innerText = result.to || 0;
                    if(totalEl) totalEl.innerText = result.total || 0;
                }
            } catch (e) { 
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        async function loadFilterOptions() {
            try {
                const res = await fetch('/api/engine-options', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                if (res.ok) {
                    const fuelSelect = document.getElementById('filterFuel');
                    const cylSelect = document.getElementById('filterCylinders');
                    fuelSelect.innerHTML = '<option value="">Semua Bahan Bakar</option>';
                    cylSelect.innerHTML = '<option value="">Semua Konfigurasi</option>';
                    result.data.fuels.forEach(f => fuelSelect.innerHTML += `<option value="${f}">${f}</option>`);
                    result.data.cylinders.forEach(c => cylSelect.innerHTML += `<option value="${c}">${c}</option>`);
                }
            } catch (e) { console.error(e); }
        }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => applyFilter(), 500);
        });

        function applyFilter() {
            const search = document.getElementById('searchInput').value;
            const fuel = document.getElementById('filterFuel').value;
            const cylinders = document.getElementById('filterCylinders').value;
            fetchEngineTypes(search, fuel, cylinders);
            const modal = document.getElementById('modalFilterMesin');
            if (modal && !modal.classList.contains('hidden')) toggleModal('modalFilterMesin');
        }

        function resetFilter() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterFuel').value = '';
            document.getElementById('filterCylinders').value = '';
            fetchEngineTypes();
            toggleModal('modalFilterMesin');
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadFilterOptions();
            fetchEngineTypes();
        });
    </script>
@endsection