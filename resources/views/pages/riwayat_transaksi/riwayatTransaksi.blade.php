{{-- resources/views/layanan-servis/riwayat-transaksi/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Riwayat Transaksi')
@section('title_header', 'Riwayat Transaksi')

@section('table_header')
    <th class="px-6 py-5">Nama</th>
    <th class="px-6 py-5">Nomor Telepon</th>
    <th class="px-6 py-5">Nomor Polisi</th>
    <th class="px-6 py-5">Model Mobil</th>
    <th class="px-6 py-5 text-center">Tanggal Masuk</th>
    <th class="px-6 py-5 text-center">Status Pengerjaan</th>
    <th class="px-6 py-5 text-center">Status Pembayaran</th>
    <th class="px-6 py-5 text-center">Aksi</th>
@endsection

@section('table_body')
    <tr id="emptyRow" class="hidden">
        <td colspan="8" class="px-6 py-8 text-center text-gray-400 text-[13px]">Belum ada data riwayat transaksi.</td>
    </tr>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Riwayat Transaksi...',
        'showAddBtn'  => false,
    ])

    @include('layouts.table_wrapper', [
        'from'  => 1,
        'to'    => 1,
        'total' => 1,
    ])

    <script>
        const token = localStorage.getItem('access_token');

        // ── Format tanggal ────────────────────────────────────────────────────────
        function formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        // ── Config badge status pembayaran ────────────────────────────────────────
        const paymentCfg = {
            'lunas'        : { label: 'Lunas',       bg: 'bg-[#EDFBF3]', text: 'text-[#16A34A]', border: 'border-[#A7F3D0]' },
            'paid'         : { label: 'Lunas',       bg: 'bg-[#EDFBF3]', text: 'text-[#16A34A]', border: 'border-[#A7F3D0]' },
            'down_payment' : { label: 'DP',          bg: 'bg-[#FFF8EC]', text: 'text-[#F59E0B]', border: 'border-[#FDE68A]' },
            'dp'           : { label: 'DP',          bg: 'bg-[#FFF8EC]', text: 'text-[#F59E0B]', border: 'border-[#FDE68A]' },
            'belum_lunas'  : { label: 'Belum Lunas', bg: 'bg-[#FFF5F5]', text: 'text-[#FF4D4D]', border: 'border-[#FFE0E0]' },
            'unpaid'       : { label: 'Belum Lunas', bg: 'bg-[#FFF5F5]', text: 'text-[#FF4D4D]', border: 'border-[#FFE0E0]' },
        };

        function getPaymentCfg(raw) {
            const key = String(raw || '').toLowerCase();
            return paymentCfg[key] ?? paymentCfg['belum_lunas'];
        }

        // ── Render tabel ──────────────────────────────────────────────────────────
        function renderTable(data, filterText = '') {
            const tbody    = document.querySelector('tbody');
            const emptyRow = document.getElementById('emptyRow');
            Array.from(tbody.querySelectorAll('tr:not(#emptyRow)')).forEach(r => r.remove());

            const filtered = filterText
                ? data.filter(t => {
                    const name         = t.vehicle?.customer?.name         || '';
                    const phone        = t.vehicle?.customer?.phone_number || '';
                    const licensePlate = t.vehicle?.license_plate          || '';
                    const model        = t.vehicle?.model                  || '';
                    const q = filterText.toLowerCase();
                    return name.toLowerCase().includes(q)
                        || phone.includes(q)
                        || licensePlate.toLowerCase().includes(q)
                        || model.toLowerCase().includes(q);
                })
                : data;

            if (filtered.length === 0) {
                emptyRow.classList.remove('hidden');
                updatePaginationInfo(0);
                return;
            }

            emptyRow.classList.add('hidden');

            filtered.forEach(t => {
                const customer   = t.vehicle?.customer ?? {};
                const vehicle    = t.vehicle           ?? {};
                const rawPayment = t.status_payment ?? t.payment_status ?? 'unpaid';
                const pc         = getPaymentCfg(rawPayment);

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-[#F9FCFF] transition-colors group';
                tr.innerHTML = `
                    <td class="px-6 py-[18px] font-bold text-[#213F5C]">${escHtml(customer.name || '-')}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(customer.phone_number || '-')}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(vehicle.license_plate || '-')}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(vehicle.model || '-')}</td>
                    <td class="px-6 py-[18px] text-center text-[#213F5C] font-semibold text-[13px]">
                        ${escHtml(formatTanggal(t.created_at))}
                    </td>
                    <td class="px-6 py-[18px] text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border
                            bg-[#EDFBF3] text-[#16A34A] border-[#A7F3D0]">
                            Selesai
                        </span>
                    </td>
                    <td class="px-6 py-[18px] text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border
                            ${pc.bg} ${pc.text} ${pc.border}">
                            ${pc.label}
                        </span>
                    </td>
                    <td class="px-6 py-4.5 text-center">
                        <a href="/riwayat-transaksi/${t.transaction_id}"
                            onclick="goToDetail(event, ${t.transaction_id})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Detail
                        </a>
                    </td>`;
                tbody.appendChild(tr);
            });

            updatePaginationInfo(filtered.length);
        }

        function updatePaginationInfo(total) {
            const fromEl  = document.getElementById('paginationFrom');
            const toEl    = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');
            if (fromEl)  fromEl.textContent  = total > 0 ? 1 : 0;
            if (toEl)    toEl.textContent    = total;
            if (totalEl) totalEl.textContent = total;
        }

        function goToDetail(e, id) {
            e.preventDefault();
            sessionStorage.setItem('currentTransaksiId', id);
            window.location.href = "{{ route('riwayat-transaksi.show', ':id') }}".replace(':id', id);
        }

        // ── Fetch dari API — hanya status_service = selesai ───────────────────────
        let allData = [];

        async function loadData() {
            try {
                const res = await fetch('/api/transactions?status_service=selesai&per_page=1000', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept'       : 'application/json',
                    }
                });
                const result = await res.json();

                if (!res.ok) {
                    console.error('API error:', result);
                    document.getElementById('emptyRow').classList.remove('hidden');
                    updatePaginationInfo(0);
                    return;
                }

                // Antisipasi dua struktur: { data: [...] } atau { data: { data: [...] } }
                const raw = result.data;
                allData = Array.isArray(raw)
                    ? raw
                    : Array.isArray(raw?.data)
                        ? raw.data
                        : [];

                // Filter sisi client — fallback jika backend tidak support query param
                allData = allData.filter(t =>
                    String(t.status_service || '').toLowerCase() === 'selesai'
                );

                renderTable(allData);
            } catch (err) {
                console.error('Fetch error:', err);
                Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadData();

            const searchInput = document.querySelector('input[placeholder="Cari Riwayat Transaksi..."]');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    renderTable(allData, searchInput.value);
                });
            }

            // User info
            const name = localStorage.getItem('user_name') || 'User';
            const role = localStorage.getItem('user_role') || 'Staff';
            document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
            document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
            document.querySelectorAll('.user-initial-box').forEach(el => el.innerText = name.charAt(0).toUpperCase());
        });
    </script>
@endsection