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
        <a href="{{ route('antrian-pengerjaan.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4 hover:shadow-md hover:border-gray-200 transition-all cursor-pointer">
            <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p id="statMenunggu" class="text-[28px] font-bold text-[#213F5C]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Menunggu</p>
            </div>
        </a>

        {{-- Pengecekan --}}
        <a href="{{ route('antrian-pengerjaan.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4 hover:shadow-md hover:border-[#FDE68A] transition-all cursor-pointer">
            <div class="w-11 h-11 rounded-full bg-[#FFF4E5] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                </svg>
            </div>
            <div>
                <p id="statPengecekan" class="text-[28px] font-bold text-[#F59E0B]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Pengecekan</p>
            </div>
        </a>

        {{-- Dikerjakan --}}
        <a href="{{ route('antrian-pengerjaan.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4 hover:shadow-md hover:border-[#B1D3FF] transition-all cursor-pointer">
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
        </a>

        {{-- Selesai --}}
        <a href="{{ route('riwayat-transaksi.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4 hover:shadow-md hover:border-[#A7F3D0] transition-all cursor-pointer">
            <div class="w-11 h-11 rounded-full bg-[#E8F5E9] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p id="statSelesai" class="text-[28px] font-bold text-[#22C55E]">0</p>
                <p class="text-[13px] text-[#627D98] font-semibold">Selesai</p>
            </div>
        </a>

    </div>

    {{-- ===================== QUICK ACTIONS ===================== --}}
    <div class="mb-2">
        <p class="text-[16px] font-bold text-[#213F5C]">Akses Cepat</p>
        <p class="text-[13px] text-[#627D98]">Navigasi langsung ke modul utama</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('antrian-pengerjaan.create') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-3 hover:shadow-md hover:border-[#B1D3FF] transition-all">
            <div class="w-10 h-10 rounded-full bg-[#EAF2FF] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-[#213F5C]">Tambah Antrian</p>
                <p class="text-[11px] text-[#627D98]">Daftarkan mobil baru</p>
            </div>
        </a>
        <a href="{{ route('pelanggan.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-3 hover:shadow-md hover:border-[#B1D3FF] transition-all">
            <div class="w-10 h-10 rounded-full bg-[#F0EDFF] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#7C3AED]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-[#213F5C]">Data Pelanggan</p>
                <p class="text-[11px] text-[#627D98]">Kelola pelanggan</p>
            </div>
        </a>
        <a href="{{ route('suku-cadang.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-3 hover:shadow-md hover:border-[#B1D3FF] transition-all">
            <div class="w-10 h-10 rounded-full bg-[#FFF4E5] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-[#213F5C]">Suku Cadang</p>
                <p class="text-[11px] text-[#627D98]">Kelola stok barang</p>
            </div>
        </a>
        <a href="{{ route('riwayat-transaksi.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-3 hover:shadow-md hover:border-[#B1D3FF] transition-all">
            <div class="w-10 h-10 rounded-full bg-[#E8F5E9] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-[#213F5C]">Riwayat Transaksi</p>
                <p class="text-[11px] text-[#627D98]">Lihat transaksi selesai</p>
            </div>
        </a>
    </div>

    {{-- ===================== TABLE SECTION HEADER ===================== --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-[16px] font-bold text-[#213F5C]">Antrian Pengerjaan Terkini</p>
            <p class="text-[13px] text-[#627D98]">Data antrian dari sistem</p>
        </div>
        <a href="{{ route('antrian-pengerjaan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
            Lihat Semua Antrian
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
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

        /* ── Status config (sama seperti antrian pengerjaan) ── */
        const statusConfigMap = {
            'menunggu'    : { bg: 'bg-gray-100',   text: 'text-gray-500',   border: 'border-gray-200', label: 'Menunggu'   },
            'pengecekan'  : { bg: 'bg-[#FFF8EC]',  text: 'text-[#F59E0B]',  border: 'border-[#FDE68A]', label: 'Pengecekan' },
            'dikerjakan'  : { bg: 'bg-[#EAF2FF]',  text: 'text-[#1273EB]',  border: 'border-[#B1D3FF]', label: 'Dikerjakan' },
            'selesai'     : { bg: 'bg-[#EDFBF3]',  text: 'text-[#16A34A]',  border: 'border-[#A7F3D0]', label: 'Selesai'    },
            'dibatalkan'  : { bg: 'bg-[#FFF5F5]',  text: 'text-[#FF4D4D]',  border: 'border-[#FFE0E0]', label: 'Dibatalkan' },
        };

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

        /* ── 2. Fetch data dari API (real-time, nyambung ke antrian pengerjaan) ── */
        async function fetchTransaksi(search = '', page = 1) {
            const tbody   = document.getElementById('berandaTableBody');
            const fromEl  = document.getElementById('paginationFrom');
            const toEl    = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

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
                                    <p class="text-[13px] text-gray-400 font-medium">Belum ada data antrian saat ini.</p>
                                </div>
                            </td>
                        </tr>`;
                    if (fromEl)  fromEl.innerText = 0;
                    if (toEl)    toEl.innerText   = 0;
                    if (totalEl) totalEl.innerText = 0;
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
                        <td class="px-6 py-4 text-center">
                            <a href="/antrian-pengerjaan/${item.transaction_id}"
                               onclick="goToDetail(event, ${item.transaction_id})"
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

                renderPaginationControls(result, (p) => fetchTransaksi(search, p));

            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        /* ── 3. Load Status Summary dari API (akurat, semua data) ── */
        async function loadStatusSummary() {
            try {
                const res = await fetch('/api/transactions/status-summary', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (res.ok && result.data) {
                    const set = (id, val) => { const e = document.getElementById(id); if (e) e.innerText = val; };
                    set('bannerTotalKendaraan', result.data.total ?? 0);
                    set('statMenunggu',   result.data.menunggu   ?? 0);
                    set('statPengecekan', result.data.pengecekan ?? 0);
                    set('statDikerjakan', result.data.dikerjakan ?? 0);
                    set('statSelesai',    result.data.selesai    ?? 0);
                }
            } catch (e) {
                console.error('Failed to load status summary:', e);
            }
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
            loadStatusSummary();
            fetchTransaksi();

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => fetchTransaksi(e.target.value, 1), 500);
                });
            }
        });
    </script>

@endsection