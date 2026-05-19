@extends('layouts.master')

@section('title', 'Beranda')
@section('title_header', 'Beranda')

{{-- HEADER TABEL --}}
@section('table_header')
    <th class="px-6 py-5">Nama</th>
    <th class="px-6 py-5">Nomor Telepon</th>
    <th class="px-6 py-5">Nomor Polisi</th>
    <th class="px-6 py-5">Model Mobil</th>
    <th class="px-6 py-5 text-center">Status</th>
    <th class="px-6 py-5 text-center">Aksi</th>
@endsection

{{-- BODY TABEL --}}
@section('table_body')
    <tbody id="berandaTableBody">
        <tr>
            <td colspan="6" class="text-center py-10 text-gray-400 italic">Memuat data...</td>
        </tr>
    </tbody>
@endsection

@section('content')

    {{-- ===================== WELCOME BANNER ===================== --}}
    <div class="bg-[#1B3A57] rounded-[20px] px-8 py-6 mb-6">
        <h2 class="text-white text-[22px] font-bold mb-1">
            Selamat Datang, <span id="bannerNamaPegawai">…</span>! 👋
        </h2>
        <p class="text-[#A8C4E0] text-[14px]">Pantau status pengerjaan kendaraan Anda hari ini</p>
        <div class="mt-3">
            <span class="inline-flex items-center gap-2 bg-white/10 text-white text-[13px] font-semibold px-4 py-1.5 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
                Kendaraan Hari Ini : <span id="bannerTotalKendaraan">–</span>
            </span>
        </div>
    </div>

    {{-- ===================== STATUS CARDS ===================== --}}
    <div class="mb-2">
        <p class="text-[16px] font-bold text-[#213F5C]">Status Pengerjaan Mobil</p>
        <p class="text-[13px] text-[#627D98]">Tracking status pengerjaan</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        {{-- Menunggu --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p id="statMenunggu" class="text-[28px] font-bold text-[#213F5C]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Menunggu</p>
            </div>
        </div>

        {{-- Pengecekan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-[#FFF4E5] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                </svg>
            </div>
            <div>
                <p id="statPengecekan" class="text-[28px] font-bold text-[#F59E0B]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Pengecekan</p>
            </div>
        </div>

        {{-- Dikerjakan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-[#EAF2FF] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div>
                <p id="statDikerjakan" class="text-[28px] font-bold text-[#1273EB]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Dikerjakan</p>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-full bg-[#E8F5E9] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p id="statSelesai" class="text-[28px] font-bold text-[#22C55E]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Selesai</p>
            </div>
        </div>

    </div>

    {{-- TABLE WRAPPER --}}
    @include('layouts.table_wrapper')

    <script>
        let searchTimeout = null;
        const token = localStorage.getItem('access_token');

        /* ── Helper: escape HTML ── */
        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        /* ── 1. Load Nama Pegawai ── */
        async function loadNamaPegawai() {
            const el = document.getElementById('bannerNamaPegawai');
            if (!el) return;

            const namaLokal = localStorage.getItem('user_name');
            if (namaLokal) { el.innerText = namaLokal; return; }

            try {
                const employeeId = localStorage.getItem('user_employees_id');
                if (!employeeId) { el.innerText = 'Pengguna'; return; }

                const res    = await fetch(`/api/employees/${employeeId}`, {
                    headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
                });
                const result = await res.json();
                el.innerText = result?.data?.name ?? 'Pengguna';
            } catch {
                el.innerText = 'Pengguna';
            }
        }

        /* ── 2. Fetch dari localStorage (sama seperti antrian-pengerjaan) ── */
async function fetchTransaksi(search = '') {
    const tbody   = document.getElementById('berandaTableBody');
    const fromEl  = document.getElementById('paginationFrom');
    const toEl    = document.getElementById('paginationTo');
    const totalEl = document.getElementById('paginationTotal');

    if (!tbody) return;

    const list = JSON.parse(localStorage.getItem('antrianList') || '[]');

    const items = search
        ? list.filter(item =>
            item.name.toLowerCase().includes(search.toLowerCase()) ||
            item.phone.includes(search) ||
            item.license_plate.toLowerCase().includes(search.toLowerCase()) ||
            item.car_model.toLowerCase().includes(search.toLowerCase())
          )
        : list;

    /* Update stat cards & banner */
    updateStatCards(items, list.length);

    tbody.innerHTML = '';

    if (items.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="py-24 text-center">
                    <div class="flex flex-col items-center justify-center opacity-60">
                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375
                                     a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3
                                     0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504
                                     1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0
                                     00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048
                                     -.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0
                                     00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                        </svg>
                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Antrian tidak ditemukan</h3>
                        <p class="text-[13px] text-gray-400 font-medium">Belum ada data antrian untuk hari ini.</p>
                    </div>
                </td>
            </tr>`;
        if (fromEl)  fromEl.innerText = 0;
        if (toEl)    toEl.innerText   = 0;
        if (totalEl) totalEl.innerText = 0;
        return;
    }

    const statusMap = {
        'Menunggu'    : 'bg-gray-100 text-gray-500 border-gray-200',
        'Pengecekan'  : 'bg-[#FFF8EC] text-[#F59E0B] border-[#FDE68A]',
        'Dalam Proses': 'bg-[#EAF2FF] text-[#1273EB] border-[#B1D3FF]',
        'Selesai'     : 'bg-[#EDFBF3] text-[#16A34A] border-[#A7F3D0]',
    };

    items.forEach(item => {
        const status   = item.status ?? 'Menunggu';
        const badgeCls = statusMap[status] ?? 'bg-gray-100 text-gray-500 border-gray-200';

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-[#F9FCFF] transition-colors group';
        tr.innerHTML = `
            <td class="px-6 py-[18px] font-bold text-[#213F5C]">${escHtml(item.name)}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.phone)}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.license_plate)}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.car_model)}</td>
            <td class="px-6 py-[18px] text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border ${badgeCls}">
                    ${escHtml(status)}
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <a href="/antrian-pengerjaan/${item.id}"
                   onclick="goToDetail(event, ${item.id})"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5
                          bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF]
                          rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Detail
                </a>
            </td>`;
        tbody.appendChild(tr);
    });

    if (fromEl)  fromEl.innerText = items.length > 0 ? 1 : 0;
    if (toEl)    toEl.innerText   = items.length;
    if (totalEl) totalEl.innerText = list.length;
}

        /* ── 3. Update Stat Cards & Banner ── */
function updateStatCards(items, total) {
    const set   = (id, val) => { const e = document.getElementById(id); if (e) e.innerText = val; };
    const count = (label)   => items.filter(i => (i.status ?? '') === label).length;

    set('bannerTotalKendaraan', total);
    set('statMenunggu',   count('Menunggu'));
    set('statPengecekan', count('Pengecekan'));
    set('statDikerjakan', count('Dalam Proses'));
    set('statSelesai',    count('Selesai'));
}

        /* ── 4. Navigasi ke detail antrian ── */
        function goToDetail(e, id) {
            e.preventDefault();
            sessionStorage.setItem('currentAntrianId', id);
            window.location.href = "{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', id);
        }

        /* ── 5. Init ── */
        document.addEventListener('DOMContentLoaded', () => {
            loadNamaPegawai();
            fetchTransaksi();

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => fetchTransaksi(e.target.value), 500);
                });
            }
        });
    </script>

@endsection