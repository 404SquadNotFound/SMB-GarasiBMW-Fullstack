@extends('layouts.master')

@section('title', 'Detail Mobil Masuk')
@section('title_header', 'Manajemen Servis | Detail')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1273EB] rounded-[12px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h2 class="text-[17px] font-bold text-[#213F5C]">Detail Mobil Masuk</h2>
        </div>
        <a href="{{ route('manajemen-servis.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 border border-[#E5E9F2] rounded-xl text-[13px] font-semibold text-[#213F5C] hover:bg-[#F9FBFF] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Informasi Pemilik Kendaraan --}}
            <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                    </svg>
                    <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex gap-4">
                        <span class="text-[13px] text-gray-400 w-36 shrink-0">Nama Lengkap</span>
                        <span class="text-[13px] font-semibold text-[#213F5C]" id="owner_name">—</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-[13px] text-gray-400 w-36 shrink-0">Nomor Telepon</span>
                        <span class="text-[13px] font-semibold text-[#213F5C]" id="owner_phone">—</span>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-[13px] text-gray-400 w-36 shrink-0">Alamat</span>
                        <span class="text-[13px] font-semibold text-[#213F5C]" id="owner_address">—</span>
                    </div>
                </div>
            </div>

            {{-- Informasi Mobil Pelanggan --}}
            <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3M8 17l4 4m0 0l4-4m-4 4V11" />
                    </svg>
                    <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h3>
                </div>
                <div class="grid grid-cols-2 gap-4 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl p-4">
                    <div>
                        <span class="text-[12px] text-gray-400 block mb-1">Model Mobil</span>
                        <span class="text-[13px] font-bold text-[#1273EB]" id="car_model">—</span>
                    </div>
                    <div>
                        <span class="text-[12px] text-gray-400 block mb-1">Km Masuk</span>
                        <span class="text-[13px] font-bold text-[#1273EB]" id="km_masuk">—</span>
                    </div>
                    <div>
                        <span class="text-[12px] text-gray-400 block mb-1">Kode Mesin</span>
                        <span class="text-[13px] font-bold text-[#1273EB]" id="engine_code">—</span>
                    </div>
                    <div>
                        <span class="text-[12px] text-gray-400 block mb-1">Nomor Polisi</span>
                        <span class="text-[13px] font-bold text-[#1273EB]" id="plate_number">—</span>
                    </div>
                </div>
            </div>

            {{-- Penggunaan Suku Cadang --}}
            <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-[15px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h3>
                </div>

                <div id="spare_parts_list" class="space-y-3">
                    {{-- Diisi via JS --}}
                    <p class="text-[13px] text-gray-400 text-center py-4">Memuat data...</p>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-5">

            {{-- Quick Info --}}
            <div class="bg-white border border-[#E5E9F2] rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                    </svg>
                    <h3 class="text-[15px] font-bold text-[#213F5C]">Quick Info</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[11px] text-gray-400 uppercase tracking-wide">Created By</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-7 h-7 rounded-full bg-[#1273EB] flex items-center justify-center text-white text-[11px] font-bold" id="created_avatar">—</div>
                            <span class="text-[13px] font-semibold text-[#213F5C]" id="created_by">—</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[11px] text-gray-400 uppercase tracking-wide">Created Date</span>
                        <p class="text-[13px] font-semibold text-[#213F5C] mt-1" id="created_at">—</p>
                    </div>
                    <div>
                        <span class="text-[11px] text-gray-400 uppercase tracking-wide">Last Updated</span>
                        <p class="text-[13px] font-semibold text-[#213F5C] mt-1" id="updated_at">—</p>
                    </div>

                    <div class="border-t border-[#E5E9F2] pt-4">
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#1273EB] inline-block"></span>
                            <span class="text-[12px] font-semibold text-[#213F5C]">Ubah Status</span>
                        </div>
                        <div class="relative">
                            <select id="status_select"
                                class="w-full px-4 py-3 rounded-xl border-2 border-[#22C55E] bg-[#F0FDF4] text-[#15803D] font-bold text-[13px] appearance-none outline-none cursor-pointer">
                                <option value="menunggu" class="text-black bg-white">Menunggu</option>
                                <option value="proses" class="text-black bg-white">Dalam Proses</option>
                                <option value="selesai" class="text-black bg-white" selected>Selesai</option>
                                <option value="batal" class="text-black bg-white">Dibatalkan</option>
                            </select>
                            <svg class="w-4 h-4 text-[#15803D] absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <button id="btnProsesPembayaran"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-[#22C55E] hover:bg-[#16A34A] text-white rounded-xl font-semibold text-[14px] transition shadow-md shadow-green-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Proses Pembayaran
                </button>

                <a href="{{ route('manajemen-servis.edit', $id) }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-[#1273EB] hover:bg-[#0F62CC] text-white rounded-xl font-semibold text-[14px] transition shadow-md shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit Data
                </a>

                <button id="btnHapusData"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-[#EF4444] hover:bg-[#DC2626] text-white rounded-xl font-semibold text-[14px] transition shadow-md shadow-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Data
                </button>

                <button id="btnBatal"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 border-2 border-[#E5E9F2] hover:bg-[#F9FBFF] text-[#EF4444] rounded-xl font-semibold text-[14px] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const serviceId = {{ $id }};
    const token = localStorage.getItem('access_token');

    // ── Format tanggal ke "27 Januari 2025, 08:00" ──────────────────────
    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const date = new Date(dateStr);
        return date.toLocaleString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    // ── Warna status ─────────────────────────────────────────────────────
    function applyStatusStyle(select, status) {
        const styles = {
            menunggu: { border: '#F59E0B', bg: '#FFFBEB', text: '#B45309' },
            proses:   { border: '#1273EB', bg: '#EFF6FF', text: '#1D4ED8' },
            selesai:  { border: '#22C55E', bg: '#F0FDF4', text: '#15803D' },
            batal:    { border: '#EF4444', bg: '#FEF2F2', text: '#B91C1C' },
        };
        const s = styles[status] ?? styles['selesai'];
        select.style.borderColor = s.border;
        select.style.backgroundColor = s.bg;
        select.style.color = s.text;
    }

    // ── Render suku cadang ────────────────────────────────────────────────
    function renderSpareParts(parts) {
        const container = document.getElementById('spare_parts_list');
        if (!parts || parts.length === 0) {
            container.innerHTML = '<p class="text-[13px] text-gray-400 text-center py-4">Tidak ada suku cadang yang digunakan.</p>';
            return;
        }

        container.innerHTML = parts.map(part => `
            <div class="flex items-start justify-between gap-4 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl px-4 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-[#213F5C] truncate">${part.part_name ?? '—'}</p>
                    <p class="text-[12px] text-gray-400 mt-0.5">${part.notes ?? ''}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-[13px] font-bold text-[#213F5C]">Rp ${Number(part.sell_price ?? 0).toLocaleString('id-ID')}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        ${part.qty ?? 1} pcs • ${part.entry_date ? new Date(part.entry_date).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) : '—'}
                    </p>
                    ${part.supplier_name ? `<p class="text-[11px] text-gray-400">Supplier: ${part.supplier_name}</p>` : ''}
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button class="text-[#1273EB] hover:opacity-70 transition" title="Edit suku cadang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                    </button>
                    <button class="text-[#EF4444] hover:opacity-70 transition" title="Hapus suku cadang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // ── Load data detail ──────────────────────────────────────────────────
    (async () => {
        try {
            const response = await fetch(`/api/service-records/${serviceId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            const result = await response.json();

            if (response.ok) {
                const d = result.data ?? result;

                // Pemilik
                document.getElementById('owner_name').textContent    = d.customer_name    ?? '—';
                document.getElementById('owner_phone').textContent   = d.customer_phone   ?? '—';
                document.getElementById('owner_address').textContent = d.customer_address ?? '—';

                // Mobil
                document.getElementById('car_model').textContent    = d.car_model    ?? '—';
                document.getElementById('km_masuk').textContent     = d.km_in ? `${Number(d.km_in).toLocaleString('id-ID')} Km` : '—';
                document.getElementById('engine_code').textContent  = d.engine_code  ?? '—';
                document.getElementById('plate_number').textContent = d.plate_number ?? '—';

                // Quick info
                const createdBy = d.created_by ?? '—';
                document.getElementById('created_by').textContent     = createdBy;
                document.getElementById('created_avatar').textContent = createdBy.charAt(0).toUpperCase();
                document.getElementById('created_at').textContent     = formatDate(d.created_at);
                document.getElementById('updated_at').textContent     = formatDate(d.updated_at);

                // Status
                const statusSelect = document.getElementById('status_select');
                if (d.status) {
                    statusSelect.value = d.status;
                    applyStatusStyle(statusSelect, d.status);
                }

                // Suku Cadang
                renderSpareParts(d.spare_parts ?? []);

            } else {
                Swal.fire('Gagal', 'Data tidak ditemukan.', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
        }
    })();

    // ── Ubah status ───────────────────────────────────────────────────────
    document.getElementById('status_select').addEventListener('change', async function () {
        const newStatus = this.value;
        applyStatusStyle(this, newStatus);

        try {
            const response = await fetch(`/api/service-records/${serviceId}/status`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ status: newStatus })
            });

            const result = await response.json();

            if (!response.ok) {
                Swal.fire('Gagal', result.message || 'Status gagal diperbarui.', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
        }
    });

    // ── Hapus data ────────────────────────────────────────────────────────
    document.getElementById('btnHapusData').addEventListener('click', async () => {
        const confirm = await Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#E5E9F2',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!confirm.isConfirmed) return;

        try {
            Swal.fire({ title: 'Menghapus data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const response = await fetch(`/api/service-records/${serviceId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.ok) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data servis berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false
                });
                window.location.href = "{{ route('manajemen-servis.index') }}";
            } else {
                const result = await response.json();
                Swal.fire('Gagal', result.message || 'Data gagal dihapus.', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
        }
    });

    // ── Batal ─────────────────────────────────────────────────────────────
    document.getElementById('btnBatal').addEventListener('click', () => {
        window.location.href = "{{ route('manajemen-servis.index') }}";
    });

    // ── Proses Pembayaran ─────────────────────────────────────────────────
    document.getElementById('btnProsesPembayaran').addEventListener('click', () => {
        // Arahkan ke halaman pembayaran atau buka modal sesuai kebutuhan
        Swal.fire({
            icon: 'info',
            title: 'Proses Pembayaran',
            text: 'Fitur pembayaran belum tersedia.',
        });
    });
</script>
@endsection