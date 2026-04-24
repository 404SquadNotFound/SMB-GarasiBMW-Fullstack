@extends('layouts.master')

@section('title', 'Jenis Mobil')
@section('title_header', 'Master Data | Jenis Mobil')

@section('table_header')
    <th class="px-6 py-5">Kode Sasis</th>
    <th class="px-6 py-5">Nama Model</th>
    <th class="px-6 py-5">Seri</th>
    <th class="px-6 py-5">Kode Mesin</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    <tbody id="carTableBody">
        <tr><td colspan="5" class="text-center py-10 text-gray-400">Memuat data mobil...</td></tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Jenis Mobil...',
        'filterModalId' => 'modalFilterMobil',
        'addUrl' => route('jenis-mobil.create'),
        'btnText' => 'Tambah Jenis Mobil'
    ])

    @include('layouts.table_wrapper')

    {{-- MODAL FILTER --}}
    <div id="modalFilterMobil" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalFilterMobil')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#213F5C]">Filter Mobil BMW</h3>
                    <button onclick="toggleModal('modalFilterMobil')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Seri</label>
                        <select id="filterSeries" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Seri</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Jenis Mesin</label>
                        <select id="filterEngineType" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Mesin</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-5 bg-gray-50 flex gap-3">
                    <button onclick="resetFilter()" class="flex-1 py-3 bg-white border border-[#D9E2EC] text-[#627D98] font-bold rounded-xl text-[14px]">Reset</button>
                    <button onclick="applyFilter()" class="flex-1 py-3 bg-[#1273EB] text-white font-bold rounded-xl text-[14px]">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timeout = null;
        const token = localStorage.getItem('access_token');

        // Fungsi Toggle Modal lokal
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.toggle('hidden');
        }

        async function fetchCarTypes(search = '', series = '', engine_id = '') {
            const tbody = document.getElementById('carTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            try {
                const res = await fetch(`/api/car-types?limit=10&search=${search}&series=${series}&engine_type_id=${engine_id}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();

                if (res.ok) {
                    tbody.innerHTML = '';
                    const items = result.data || [];
                    
                    // --- LOGIC EMPTY STATE ---
                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0l-1.65-3.712A2.25 2.25 0 0016.597 2.5H7.403a2.25 2.25 0 00-2.003 1.288L3.75 7.5m16.5 0H3.75m16.5 0v1.5a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V7.5m10.5-1.125h.008v.008h-.008V6.375zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Mobil tidak ditemukan brok</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cari seri lain atau filter mesin yang berbeda.</p>
                                    </div>
                                </td>
                            </tr>`;
                        
                        if(fromEl) fromEl.innerText = 0; 
                        if(toEl) toEl.innerText = 0; 
                        if(totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // --- RENDER DATA ---
                    items.forEach(item => {
                        // Gunakan engine_code biar daftar banyak mesinnya muncul
                        const engineDisplay = item.engine_code 
                            ? `<span class="px-3 py-1 bg-blue-50 text-[#1273EB] border border-blue-100 rounded-full text-[11px] font-bold">${item.engine_code}</span>` 
                            : '<span class="text-gray-400 italic text-[12px]">Belum diset</span>';

                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.chassis_number}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.name}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.series}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">
                                    ${engineDisplay}
                                </td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/jenis-mobil/detail/${item.car_type_id}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">Detail</a>
                                </td>
                            </tr>`;
                    });

                    if(fromEl) fromEl.innerText = result.from || 0;
                    if(toEl) toEl.innerText = result.to || 0;
                    if(totalEl) totalEl.innerText = result.total || 0;
                }
            } catch (e) { 
                console.error(e); 
                tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center text-red-500 font-bold">Koneksi API bermasalah brok.</td></tr>';
            }
        }
        // 2. Debounce Search
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const series = document.getElementById('filterSeries').value;
                const engine = document.getElementById('filterEngineType').value;
                fetchCarTypes(e.target.value, series, engine);
            }, 500);
        });

        // 3. Filter Actions
        function applyFilter() {
            const search = document.getElementById('searchInput').value;
            const series = document.getElementById('filterSeries').value;
            const engine = document.getElementById('filterEngineType').value;
            fetchCarTypes(search, series, engine);
            toggleModal('modalFilterMobil');
        }

        function resetFilter() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterSeries').value = '';
            document.getElementById('filterEngineType').value = '';
            fetchCarTypes();
            toggleModal('modalFilterMobil');
        }

        // 4. Load Dropdown Mesin ke Modal (Versi Fix Paginate)
        async function loadOptions() {
            try {
                const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

                // 1. Load Mesin (Tetap)
                const resEngine = await fetch('/api/engine-types', { headers });
                const resultEngine = await resEngine.json();
                if (resEngine.ok) {
                    const selectEngine = document.getElementById('filterEngineType');
                    const engines = resultEngine.data.data || resultEngine.data; 
                    engines.forEach(e => {
                        const opt = document.createElement('option');
                        opt.value = e.engine_type_id;
                        opt.text = e.name;
                        selectEngine.appendChild(opt);
                    });
                }

                // 2. Load Seri
                const resSeries = await fetch('/api/car-series', { headers });
                const resultSeries = await resSeries.json();

                if (resSeries.ok) {
                    const selectSeries = document.getElementById('filterSeries');
                    resultSeries.data.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s;
                        opt.text = s;
                        selectSeries.appendChild(opt);
                    });
                }
            } catch (e) { 
                console.error("Gagal load filter:", e); 
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadOptions();
            fetchCarTypes();
        });
    </script>
@endsection