@extends('layouts.master')

@section('title', 'Penggajian')

@section('title_header', 'Penggajian')

@section('table_header')
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Nama Pegawai</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Pendapatan</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Penalti</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px]">Tabungan</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px] uppercase tracking-wide">ROLE</th>
    <th class="px-6 py-5 font-semibold text-[#213F5C] text-[13px] uppercase tracking-wide text-center">ACTION</th>
@endsection

@section('table_body')
    <tbody id="payrollTableBody">
        <tr>
            <td colspan="6" class="text-center py-10 text-gray-400 italic">Memuat data payroll...</td>
        </tr>
    </tbody>
@endsection

@section('content')

    {{-- Action Bar --}}
    @include('layouts.action_bar', [
        'placeholder'    => 'Cari Nama Karyawan...',
        'searchUrl'      => '#',
        'filterModalId'  => 'modalFilterPayroll',
        'exportExcelUrl' => '#',
        'exportPdfUrl'   => '#',
        'addUrl'         => route('payroll.create'),
        'btnText'        => 'Tambah Daftar Gaji',
    ])

    {{-- Sembunyikan tombol tambah untuk role CEO --}}
    <script>
        (function () {
            const role = (localStorage.getItem('user_role') || '').toLowerCase();
            if (role === 'ceo') {
                document.addEventListener('DOMContentLoaded', function () {
                    const addBtn = document.querySelector('a[href="{{ route('payroll.create') }}"]');
                    if (addBtn) addBtn.style.display = 'none';
                });
            }
        })();
    </script>

    {{-- ===== Periode Gaji Banner ===== --}}
    <div id="payrollPeriodBanner" class="w-full bg-[#4B7BEC] rounded-2xl px-7 py-5 flex items-center justify-between mb-5"
         x-data="{
             currentMonth: {{ now()->month }},
             currentYear:  {{ now()->year }},
             get label() {
                 const months = ['Januari','Februari','Maret','April','Mei','Juni',
                                 'Juli','Agustus','September','Oktober','November','Desember'];
                 return months[this.currentMonth - 1] + ' ' + this.currentYear;
             },
             prev() {
                 if (this.currentMonth === 1) { this.currentMonth = 12; this.currentYear--; }
                 else { this.currentMonth--; }
                 fetchPayrolls();
             },
             next() {
                 if (this.currentMonth === 12) { this.currentMonth = 1; this.currentYear++; }
                 else { this.currentMonth++; }
                 fetchPayrolls();
             }
         }">

        {{-- Kiri: ikon + label --}}
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-semibold uppercase tracking-widest">Periode Gaji</p>
                <p class="text-white text-[22px] font-bold leading-tight" x-text="label"></p>
            </div>
        </div>

        {{-- Kanan: navigasi bulan --}}
        <div class="flex items-center gap-2">
            <button @click="prev"
                class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <span class="px-5 py-2 bg-white text-[#4B7BEC] rounded-full text-[13px] font-bold min-w-[110px] text-center"
                  x-text="label"></span>

            <button @click="next"
                class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
    {{-- ===== End Periode Gaji Banner ===== --}}

    {{-- Table --}}
    @include('layouts.table_wrapper')

    <script>
        const token = localStorage.getItem('access_token');
        let searchTimeout = null;

        // ── Role badge color map ──────────────────────────────────────────────────
        const roleBadgeColors = {
            'pemilik_bengkel': 'bg-[#FFF4E5] text-[#E07B00] border-[#FFD89B]',
            'finance'        : 'bg-[#EAF2FF] text-[#1273EB] border-[#B1D3FF]',
            'kepala_bengkel' : 'bg-[#F0FFF4] text-[#1A7F3C] border-[#A7E3BE]',
            'kepala_admin'   : 'bg-[#F3F4FF] text-[#5A5FDE] border-[#C5C8FF]',
            'admin'          : 'bg-[#FFF5F5] text-[#EF4444] border-[#FECACA]',
            'karyawan'       : 'bg-[#F9FAFB] text-[#6B7280] border-[#D1D5DB]',
        };

        function formatRupiah(angka) {
            return 'Rp. ' + Number(angka || 0).toLocaleString('id-ID') + ',00';
        }

        // ── Fetch Payrolls ────────────────────────────────────────────────────────
        async function fetchPayrolls(search = '', page = 1) {
            const tbody   = document.getElementById('payrollTableBody');
            const fromEl  = document.getElementById('paginationFrom');
            const toEl    = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            // Get current month/year from Alpine.js data
            const bannerEl = document.getElementById('payrollPeriodBanner');
            let month = {{ now()->month }};
            let year  = {{ now()->year }};
            if (bannerEl && bannerEl.__x) {
                month = bannerEl.__x.$data.currentMonth;
                year  = bannerEl.__x.$data.currentYear;
            } else if (bannerEl && bannerEl._x_dataStack) {
                const data = bannerEl._x_dataStack[0];
                month = data.currentMonth;
                year  = data.currentYear;
            }

            try {
                const url = `/api/payrolls?limit=10&month=${month}&year=${year}&search=${encodeURIComponent(search)}&page=${page}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const paginated = result.data || {};
                    const items = paginated.data || [];
                    tbody.innerHTML = '';

                    // Empty state
                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Belum ada data gaji</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Belum ada data gaji untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>`;

                        if (fromEl) fromEl.innerText = 0;
                        if (toEl) toEl.innerText = 0;
                        if (totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // Render rows
                    items.forEach(item => {
                        const emp = item.employee || {};
                        const role = emp.role || '-';
                        const badgeClass = roleBadgeColors[role] || 'bg-gray-100 text-gray-600 border-gray-300';

                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C] text-[13px]">${emp.name || '-'}</td>
                                <td class="px-6 py-[18px] text-[#16A34A] font-semibold text-[13px]">${formatRupiah(item.total_income)}</td>
                                <td class="px-6 py-[18px] text-[#EF4444] font-semibold text-[13px]">${formatRupiah(item.total_deduction)}</td>
                                <td class="px-6 py-[18px] text-[#F59E0B] font-semibold text-[13px]">${formatRupiah(item.total_savings)}</td>
                                <td class="px-6 py-[18px]">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-semibold border ${badgeClass}">
                                        ${role}
                                    </span>
                                </td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/payroll/detail/${item.payroll_id}"
                                       onclick="sessionStorage.setItem('currentPayrollId', '${item.payroll_id}')"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all no-underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>`;
                    });

                    // Update pagination
                    if (fromEl) fromEl.innerText = paginated.from || 0;
                    if (toEl) toEl.innerText = paginated.to || 0;
                    if (totalEl) totalEl.innerText = paginated.total || 0;
                    renderPaginationControls(paginated, (p) => fetchPayrolls(search, p));
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML =
                    '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal load data. Cek koneksi API!</td></tr>';
            }
        }

        // ── Search input binding ──────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => fetchPayrolls(e.target.value), 500);
                });
            }

            fetchPayrolls();
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