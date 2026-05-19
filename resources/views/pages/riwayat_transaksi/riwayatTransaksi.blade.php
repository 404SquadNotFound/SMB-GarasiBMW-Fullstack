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
    {{-- Diisi oleh JavaScript dari localStorage --}}
    <tr id="emptyRow" class="hidden">
        <td colspan="8" class="px-6 py-8 text-center text-gray-400 text-[13px]">Belum ada data riwayat transaksi.</td>
    </tr>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Riwayat Transaksi...',
        'showAddBtn' => false,
    ])

    @include('layouts.table_wrapper', [
        'from' => 1,
        'to' => 1,
        'total' => 1,
    ])

    <script>
        // ── Ambil data dari localStorage, filter hanya yang Selesai + Lunas ───────
        function getTransaksiList() {
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            return list.filter(item =>
                item.status === 'Selesai' && item.status_pembayaran === 'Lunas'
            );
        }

        // ── Format tanggal dari string created_at ────────────────────────────────
        function formatTanggal(createdAt) {
            if (!createdAt) return '-';
            const parts = createdAt.split(',');
            return parts[0].trim();
        }

        // ── Render tabel dari localStorage ───────────────────────────────────────
        function renderTable(filterText) {
            const list = getTransaksiList();
            const tbody = document.querySelector('tbody');
            const emptyRow = document.getElementById('emptyRow');

            Array.from(tbody.querySelectorAll('tr:not(#emptyRow)')).forEach(r => r.remove());

            const filtered = filterText ?
                list.filter(item =>
                    item.name.toLowerCase().includes(filterText.toLowerCase()) ||
                    item.phone.includes(filterText) ||
                    item.license_plate.toLowerCase().includes(filterText.toLowerCase()) ||
                    item.car_model.toLowerCase().includes(filterText.toLowerCase())
                ) :
                list;

            if (filtered.length === 0) {
                emptyRow.classList.remove('hidden');
                return;
            }

            emptyRow.classList.add('hidden');

            const statusPengerjaanCfg = {
                bg: 'bg-[#EDFBF3]',
                text: 'text-[#16A34A]',
                border: 'border-[#A7F3D0]',
            };

            const statusPembayaranCfg = {
                bg: 'bg-[#EDFBF3]',
                text: 'text-[#16A34A]',
                border: 'border-[#A7F3D0]',
            };

            filtered.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-[#F9FCFF] transition-colors group';
                tr.innerHTML = `
                    <td class="px-6 py-[18px] font-bold text-[#213F5C]">${escHtml(item.name)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.phone)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.license_plate)}</td>
                    <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${escHtml(item.car_model)}</td>
                    <td class="px-6 py-[18px] text-center text-[#213F5C] font-semibold text-[13px]">
                        ${escHtml(formatTanggal(item.created_at))}
                    </td>
                    <td class="px-6 py-[18px] text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border
                            ${statusPengerjaanCfg.bg} ${statusPengerjaanCfg.text} ${statusPengerjaanCfg.border}">
                            Selesai
                        </span>
                    </td>
                    <td class="px-6 py-[18px] text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border
                            ${statusPembayaranCfg.bg} ${statusPembayaranCfg.text} ${statusPembayaranCfg.border}">
                            Lunas
                        </span>
                    </td>
                    <td class="px-6 py-4.5 text-center">
                        <a href="/riwayat-transaksi/${item.id}"
                            onclick="goToDetail(event, ${item.id})"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Detail
                        </a>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            updatePaginationInfo(filtered.length);
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function updatePaginationInfo(total) {
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (fromEl) fromEl.textContent = total > 0 ? 1 : 0;
            if (toEl) toEl.textContent = total;
            if (totalEl) totalEl.textContent = total;
        }

        function goToDetail(e, id) {
            e.preventDefault();
            sessionStorage.setItem('currentTransaksiId', id);
            window.location.href = "{{ route('riwayat-transaksi.show', ':id') }}".replace(':id', id);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Patch dulu sebelum render — hapus kondisi !status_pembayaran
            // agar item yang sudah ada di localStorage tetap ter-update
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            list.forEach(item => {
                if (item.status === 'Selesai') {
                    item.status_pembayaran = 'Lunas';
                }
            });
            localStorage.setItem('antrianList', JSON.stringify(list));

            renderTable();

            const searchInput = document.querySelector('input[placeholder="Cari Riwayat Transaksi..."]');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    renderTable(searchInput.value);
                });
            }
        });
    </script>
@endsection
