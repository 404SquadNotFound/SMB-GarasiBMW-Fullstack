@extends('layouts.master')

@section('title', 'Pendataan Izin Keterlambatan')

@section('title_header', 'Pendataan Izin Keterlambatan')

@section('table_header')
    <th class="px-6 py-5">Nama Pegawai</th>
    <th class="px-6 py-5">Tanggal Terlambat</th>
    <th class="px-6 py-5">Alasan</th>
    <th class="px-6 py-5 text-center">Aksi</th>
@endsection

@section('table_body')
    <tbody id="izinTableBody">
        <tr>
            <td colspan="4" class="text-center py-10 text-gray-400 italic">Memuat data izin keterlambatan...</td>
        </tr>
    </tbody>
@endsection

@section('content')

    @include('layouts.action_bar', [
        'placeholder' => 'Cari Izin Keterlambatan...',
        'searchUrl' => '#',
        'filterModalId' => 'modalFilterIzinKeterlambatan',
        'exportExcelUrl' => '#',
        'exportPdfUrl' => '#',
        'addUrl' => route('izin-terlambat.create'),
        'btnText' => 'Tambah Izin',
    ])

    {{-- Script: sembunyikan tombol tambah untuk role CEO --}}
    <script>
        (function() {
            const role = (localStorage.getItem('user_role') || '').toLowerCase();
            if (role === 'ceo') {
                document.addEventListener('DOMContentLoaded', function() {
                    const addBtn = document.querySelector('a[href="{{ route('izin-terlambat.create') }}"]');
                    if (addBtn) addBtn.style.display = 'none';
                });
            }
        })();
    </script>

    @include('layouts.table_wrapper')

    <script>
        let timeout = null;
        const token = localStorage.getItem('access_token');

        const formatDate = (dateStr) =>
            dateStr
                ? new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                  })
                : '-';

        async function fetchIzinKeterlambatan(search = '', page = 1) {
            const tbody = document.getElementById('izinTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const url = `/api/late-permits?limit=10&search=${search}&page=${page}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const items = result.data || [];
                    tbody.innerHTML = '';

                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Data Izin Keterlambatan tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cek keyword pencarian atau tambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>`;

                        if (fromEl) fromEl.innerText = 0;
                        if (toEl) toEl.innerText = 0;
                        if (totalEl) totalEl.innerText = 0;
                        return;
                    }

                    items.forEach(item => {
                        const namaPegawai = item.employee?.name ?? item.employee_id ?? '-';
                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${namaPegawai}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${formatDate(item.late_date)}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.reason ?? '-'}</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/izin-terlambat/detail/${item.id}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>`;
                    });

                    if (fromEl) fromEl.innerText = result.from || 0;
                    if (toEl) toEl.innerText = result.to || 0;
                    if (totalEl) totalEl.innerText = result.total || 0;
                    renderPaginationControls(result, (p) => fetchIzinKeterlambatan(search, p));
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fetchIzinKeterlambatan(e.target.value), 500);
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetchIzinKeterlambatan();
        });
    </script>
@endsection