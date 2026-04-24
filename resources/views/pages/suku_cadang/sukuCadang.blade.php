@extends('layouts.master')

@section('title', 'Data Suku Cadang')
@section('title_header', 'Data Suku Cadang')

{{-- 1. WAJIB ADA HEADER TABEL --}}
@section('table_header')
    <th class="px-6 py-5">Kode Barang</th>
    <th class="px-6 py-5">Nama Suku Cadang</th>
    <th class="px-6 py-5">Kategori</th>
    <th class="px-6 py-5">Harga Beli</th>
    <th class="px-6 py-5">Harga Jual</th>
    <th class="px-6 py-5">Stok</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

{{-- 2. WAJIB ADA BODY TABEL --}}
@section('table_body')
    <tbody id="sparepartTableBody">
        <tr>
            <td colspan="7" class="text-center py-10 text-gray-400 italic">Memuat data suku cadang...</td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder'   => 'Cari Suku Cadang...',
        'filterModalId' => 'modalFilterSukuCadang',
        'addUrl'        => route('suku-cadang.create'),
        'btnText'       => 'Tambah Suku Cadang'
    ])

    @include('layouts.table_wrapper')

    {{-- MODAL FILTER SUKU CADANG --}}
    <div id="modalFilterSukuCadang" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalFilterSukuCadang')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#213F5C]">Filter Suku Cadang</h3>
                    <button onclick="toggleModal('modalFilterSukuCadang')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Kategori</label>
                        <select id="filterCategory" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Kategori</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Supplier</label>
                        <select id="filterSupplier" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Supplier</option>
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
        const token = localStorage.getItem('access_token');

        const formatRupiah = (num) =>
            num != null ? 'Rp ' + Number(num).toLocaleString('id-ID') : '-';

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.toggle('hidden');
        }

        // 1. Fetch Data Utama (server-side search + filter + pagination)
        async function fetchSpareparts(search = '', category = '', supplierId = '') {
            const tbody = document.getElementById('sparepartTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const queryParams = new URLSearchParams({
                    limit: 10,
                    search: search,
                    category: category,
                    supplier_id: supplierId
                });
                
                const url = `/api/spareparts?${queryParams.toString()}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });

                const result = await res.json();

                if (res.ok) {
                    const items = result.data || [];
                    tbody.innerHTML = '';

                    // EMPTY STATE
                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0l-1.65-3.712A2.25 2.25 0 0016.597 2.5H7.403a2.25 2.25 0 00-2.003 1.288L3.75 7.5m16.5 0H3.75" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Data suku cadang tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cek keyword pencarian atau tambahin data baru.</p>
                                    </div>
                                </td>
                            </tr>`;

                        if (fromEl) fromEl.innerText = 0;
                        if (toEl) toEl.innerText = 0;
                        if (totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // RENDER DATA
                    items.forEach(item => {
                        // Kategori bisa berupa string kolom atau object relasi (karena ada with('category'))
                        const catDisplay = item.category?.name ?? item.category ?? '-';
                        
                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.item_code || '-'}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.name || '-'}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${catDisplay}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${formatRupiah(item.cost_off_sell)}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${formatRupiah(item.selling_price)}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.quantity ?? '-'}</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/suku-cadang/detail/${item.sparepart_id}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">Detail</a>
                                </td>
                            </tr>`;
                    });

                    if (fromEl) fromEl.innerText = result.from || 0;
                    if (toEl) toEl.innerText = result.to || 0;
                    if (totalEl) totalEl.innerText = result.total || 0;
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        // 2. Load Filter Options (Cascading)
        async function loadFilterOptions() {
            const catSelect = document.getElementById('filterCategory');
            const supSelect = document.getElementById('filterSupplier');
            
            const catSelected = catSelect.value;
            const supSelected = supSelect.value;

            try {
                const queryParams = new URLSearchParams();
                if (catSelected) queryParams.append('category', catSelected);
                if (supSelected) queryParams.append('supplier_id', supSelected);

                const res = await fetch(`/api/sparepart-options?${queryParams.toString()}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                if (res.ok && result.data) {
                    catSelect.innerHTML = '<option value="">Semua Kategori</option>';
                    supSelect.innerHTML = '<option value="">Semua Supplier</option>';

                    if (result.data.categories) {
                        result.data.categories.forEach(c => {
                            catSelect.innerHTML += `<option value="${c}" ${c === catSelected ? 'selected' : ''}>${c}</option>`;
                        });
                    }
                    if (result.data.suppliers) {
                        Object.entries(result.data.suppliers).forEach(([id, name]) => {
                            supSelect.innerHTML += `<option value="${id}" ${id === supSelected ? 'selected' : ''}>${name}</option>`;
                        });
                    }
                }
            } catch (e) { console.error(e); }
        }

        // Action saat opsi filter diganti, update list optionnya (cascading)
        document.getElementById('filterCategory').addEventListener('change', loadFilterOptions);
        document.getElementById('filterSupplier').addEventListener('change', loadFilterOptions);

        // 3. Search debounce
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => applyFilter(), 500);
        });

        // 4. Apply & Reset Filter
        function applyFilter() {
            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('filterCategory').value;
            const supplier = document.getElementById('filterSupplier').value;
            fetchSpareparts(search, category, supplier);
            const modal = document.getElementById('modalFilterSukuCadang');
            if (modal && !modal.classList.contains('hidden')) toggleModal('modalFilterSukuCadang');
        }

        function resetFilter() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterSupplier').value = '';
            fetchSpareparts();
            toggleModal('modalFilterSukuCadang');
        }

        // 5. Init
        document.addEventListener('DOMContentLoaded', () => {
            loadFilterOptions();
            fetchSpareparts();
        });
    </script>
@endsection