@extends('layouts.master')

@section('title', 'Manajemen Karyawan')
@section('title_header', 'Manajemen Pegawai | Data Karyawan')

{{-- 1. WAJIB ADA HEADER TABEL --}}
@section('table_header')
    <th class="px-6 py-5 whitespace-nowrap">Nama</th>
    <th class="px-6 py-5 whitespace-nowrap">Email</th>
    <th class="px-6 py-5 whitespace-nowrap">Durasi Kerja</th>
    <th class="px-6 py-5 whitespace-nowrap">Role</th>
    <th class="px-6 py-5 whitespace-nowrap">Status</th>
    <th class="px-6 py-5 text-center whitespace-nowrap">Action</th>
@endsection

{{-- 2. WAJIB ADA BODY TABEL DENGAN ID YANG SESUAI SCRIPT --}}
@section('table_body')
    <tbody id="employeeTableBody">
        <tr>
            <td colspan="6" class="text-center py-10 text-gray-400 italic">Memuat data karyawan...</td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder'   => 'Cari Nama Karyawan...',
        'filterModalId' => 'modalFilterKaryawan',
        'addUrl'        => route('manajemen-pegawai.create'),
        'btnText'       => 'Tambah Akun'
    ])

    @include('layouts.table_wrapper')

    {{-- MODAL FILTER KARYAWAN --}}
    <div id="modalFilterKaryawan" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('modalFilterKaryawan')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[20px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#213F5C]">Filter Karyawan</h3>
                    <button onclick="toggleModal('modalFilterKaryawan')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Role</label>
                        <select id="filterRole" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Role</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-[#627D98] mb-2 uppercase tracking-wider">Status</label>
                        <select id="filterStatus" class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#D9E2EC] rounded-xl outline-none text-[#213F5C] font-semibold">
                            <option value="">Semua Status</option>
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
        async function fetchEmployees(search = '', role = '', status = '') {
            const tbody = document.getElementById('employeeTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const url = `/api/employees?limit=10&search=${search}&role=${role}&status=${status}`;
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
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Data karyawan tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cek keyword pencarian atau tambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>`;

                        if (fromEl) fromEl.innerText = 0;
                        if (toEl) toEl.innerText = 0;
                        if (totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // RENDER DATA (Kalo ada)
                    items.forEach(item => {
                        const isAktif = item.status == 1 || item.status === true || item.status === 'aktif';
                        const statusBadge = isAktif
                            ? '<span class="px-2.5 py-1 rounded-md bg-green-50 text-green-600 text-[11px] font-bold">Aktif</span>'
                            : '<span class="px-2.5 py-1 rounded-md bg-red-50 text-red-500 text-[11px] font-bold">Non-Aktif</span>';

                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.name}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.email}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.join_date ?? '-'}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.role}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${statusBadge}</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/manajemen-pegawai/detail/${item.employees_id}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">Detail</a>
                                </td>
                            </tr>`;
                    });

                    if (fromEl) fromEl.innerText = result.from || 0;
                    if (toEl) toEl.innerText = result.to || 0;
                    if (totalEl) totalEl.innerText = result.total || 0;
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        async function loadFilterOptions() {
            const roleSelect = document.getElementById('filterRole');
            const statusSelect = document.getElementById('filterStatus');
            
            const roleSelected = roleSelect.value;
            const statusSelected = statusSelect.value;

            try {
                const queryParams = new URLSearchParams();
                if (roleSelected) queryParams.append('role', roleSelected);
                if (statusSelected) queryParams.append('status', statusSelected);

                const res = await fetch(`/api/employee-options?${queryParams.toString()}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                
                if (res.ok && result.data) {
                    roleSelect.innerHTML = '<option value="">Semua Role</option>';
                    statusSelect.innerHTML = '<option value="">Semua Status</option>';

                    if (result.data.roles) {
                        result.data.roles.forEach(r => {
                            roleSelect.innerHTML += `<option value="${r}" ${r === roleSelected ? 'selected' : ''}>${r}</option>`;
                        });
                    }
                    if (result.data.statuses) {
                        Object.entries(result.data.statuses).forEach(([val, label]) => {
                            statusSelect.innerHTML += `<option value="${val}" ${val === statusSelected ? 'selected' : ''}>${label}</option>`;
                        });
                    }
                }
            } catch (e) { console.error(e); }
        }

        // Cascading: update options saat filter diganti
        document.getElementById('filterRole').addEventListener('change', loadFilterOptions);
        document.getElementById('filterStatus').addEventListener('change', loadFilterOptions);

        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => applyFilter(), 500);
        });

        function applyFilter() {
            const search = document.getElementById('searchInput').value;
            const role = document.getElementById('filterRole').value;
            const status = document.getElementById('filterStatus').value;
            fetchEmployees(search, role, status);
            const modal = document.getElementById('modalFilterKaryawan');
            if (modal && !modal.classList.contains('hidden')) toggleModal('modalFilterKaryawan');
        }

        function resetFilter() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterRole').value = '';
            document.getElementById('filterStatus').value = '';
            fetchEmployees();
            toggleModal('modalFilterKaryawan');
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadFilterOptions();
            fetchEmployees();
        });
    </script>
@endsection