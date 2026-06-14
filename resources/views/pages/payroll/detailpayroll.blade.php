{{-- resources/views/pages/payroll/detailPayroll.blade.php --}}
{{--
TODO Backend:
- Endpoint get detail payroll  : GET  /api/payrolls/{id}
- Endpoint update payroll      : PUT  /api/payrolls/{id}
- Endpoint hapus payroll       : DELETE /api/payrolls/{id}
- Endpoint cetak slip gaji     : GET  /api/payrolls/{id}/slip  (atau redirect ke preview)
Response fields yang diharapkan:
  employee.name, employee.employee_number, employee.join_year,
  employee.birth_date, employee.role,
  salary.base_salary,
  salary.allowances[]  → { name, type, amount }
  salary.savings[]     → { name, type, amount }
  salary.penalties[]   → { name, description, amount }
  created_by.name, created_at, updated_at
--}}
@extends('layouts.master')

@section('title', 'Detail Gaji Karyawan')
@section('title_header', 'Gaji Karyawan')

@section('content')

<style>
    /* ── Animasi skeleton loading ── */
    @keyframes shimmer {
        0%   { background-position: -700px 0; }
        100% { background-position: 700px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, #f0f4fa 25%, #e2eaf4 50%, #f0f4fa 75%);
        background-size: 700px 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 8px;
    }

    /* ── Section card ── */
    .detail-card {
        background: #fff;
        border: 1px solid #EEF2F8;
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: 0 1px 4px rgba(33,63,92,0.04);
    }
    .detail-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1.5px solid #F0F4FA;
    }
    .detail-card-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .detail-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #213F5C;
    }

    /* ── Grid info field ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 32px;
    }
    .info-field label {
        display: block;
        font-size: 11px;
        color: #9CA3AF;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    .info-field .info-value {
        font-size: 14px;
        font-weight: 700;
        color: #213F5C;
    }

    /* ── Row item (allowance / penalty / saving) ── */
    .pay-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #F9FBFF;
        border: 1px solid #EEF2F8;
        border-radius: 13px;
        margin-bottom: 8px;
    }
    .pay-row:last-child { margin-bottom: 0; }
    .pay-row-label { font-size: 13px; font-weight: 700; color: #213F5C; }
    .pay-row-sub   { font-size: 11px; color: #9CA3AF; margin-top: 2px; }
    .pay-row-amt   { font-size: 13px; font-weight: 700; white-space: nowrap; }
    .amt-positive  { color: #16A34A; }
    .amt-negative  { color: #EF4444; }
    .amt-neutral   { color: #213F5C; }

    /* ── Gaji Pokok row ── */
    .gapok-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0 16px;
        border-bottom: 1.5px solid #EEF2F8;
        margin-bottom: 16px;
    }
    .gapok-label { font-size: 13px; color: #6B7280; font-weight: 500; }
    .gapok-amt   { font-size: 15px; font-weight: 800; color: #213F5C; }

    /* ── Quick Info sidebar ── */
    .quick-info-card {
        background: #fff;
        border: 1px solid #EEF2F8;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 1px 4px rgba(33,63,92,0.04);
    }
    .quick-info-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 700; color: #213F5C;
        margin-bottom: 18px; padding-bottom: 12px;
        border-bottom: 1.5px solid #F0F4FA;
    }
    .qi-label {
        font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em;
        color: #9CA3AF; font-weight: 600; margin-bottom: 4px;
    }
    .qi-value  { font-size: 13px; font-weight: 700; color: #213F5C; }
    .qi-item   { margin-bottom: 16px; }
    .qi-item:last-child { margin-bottom: 0; }

    /* ── Avatar ring ── */
    .avatar-ring {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #1273EB 0%, #0E59B8 100%);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 12px; font-weight: 800;
        flex-shrink: 0;
    }

    /* ── Action buttons ── */
    .btn-action {
        width: 100%; display: flex; align-items: center; justify-content: center;
        gap: 8px; padding: 13px 16px; border-radius: 13px;
        font-size: 14px; font-weight: 700; cursor: pointer;
        transition: all 0.15s ease; border: none; margin-bottom: 10px;
    }
    .btn-action:last-child { margin-bottom: 0; }
    .btn-cetak  { background: #16A34A; color: #fff; }
    .btn-cetak:hover  { background: #15803D; box-shadow: 0 4px 14px rgba(22,163,74,0.25); }
    .btn-edit   { background: #1273EB; color: #fff; }
    .btn-edit:hover   { background: #0E59B8; box-shadow: 0 4px 14px rgba(18,115,235,0.25); }
    .btn-hapus  { background: #EF4444; color: #fff; }
    .btn-hapus:hover  { background: #DC2626; box-shadow: 0 4px 14px rgba(239,68,68,0.2); }
    .btn-batal  { background: #fff; color: #213F5C; border: 1.5px solid #E5E9F2 !important; }
    .btn-batal:hover  { background: #F9FBFF; }

    /* ── Breadcrumb header ── */
    .detail-breadcrumb {
        background: #fff;
        border: 1px solid #EEF2F8;
        border-radius: 14px;
        padding: 16px 22px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 1px 3px rgba(33,63,92,0.04);
    }

    /* ── Empty state ── */
    .empty-row {
        padding: 18px 16px;
        text-align: center;
        color: #9CA3AF;
        font-size: 12px;
        font-weight: 500;
        border: 1.5px dashed #E5E9F2;
        border-radius: 12px;
    }

    /* ── Role badge ── */
    .role-badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 20px;
        font-size: 12px; font-weight: 600; border: 1.5px solid;
    }
</style>

{{-- ── Breadcrumb ── --}}
<div class="detail-breadcrumb">
    <div class="w-8 h-8 rounded-lg bg-[#EAF2FF] flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <p class="text-[14px] font-bold text-[#213F5C]">Detail Pelanggan</p>
</div>

{{-- ── Main layout: kiri (3 kol) + kanan (1 kol) ── --}}
<div class="flex gap-5" style="align-items: flex-start;">

    {{-- ══════════════════════════════════════════════════════
         KOLOM KIRI — konten detail
    ══════════════════════════════════════════════════════ --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- ── 1. Informasi Pribadi Karyawan ── --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-card-icon bg-[#EAF2FF]">
                    <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="detail-card-title">Informasi Pribadi Karyawan</h3>
            </div>

            <div class="info-grid">
                <div class="info-field">
                    <label>Nama Lengkap</label>
                    <div class="info-value" id="detailNama">-</div>
                </div>
                <div class="info-field">
                    <label>Nomor Pokok Karyawan</label>
                    <div class="info-value" id="detailNPK">-</div>
                </div>
                <div class="info-field">
                    <label>Tahun Bergabung</label>
                    <div class="info-value" id="detailTahunBergabung">-</div>
                </div>
                <div class="info-field">
                    <label>Tanggal Lahir</label>
                    <div class="info-value" id="detailTanggalLahir">-</div>
                </div>
            </div>
        </div>

        {{-- ── 2. Pemasukan ── --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-card-icon bg-[#EDFBF3]">
                    <svg class="w-4 h-4 text-[#16A34A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="detail-card-title">Pemasukan</h3>
            </div>

            {{-- Gaji pokok --}}
            <div class="gapok-row">
                <span class="gapok-label">Gaji Pokok</span>
                <span class="gapok-amt" id="detailGajiPokok">Rp 0</span>
            </div>

            {{-- Pendapatan lain --}}
            <p class="text-[12px] font-bold text-[#6B7280] mb-3 uppercase tracking-wide">Pendapatan Lain</p>
            <div id="allowanceContainer">
                <div class="empty-row">Belum ada data pendapatan lain</div>
            </div>
        </div>

        {{-- ── 3. Tabungan ── --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-card-icon bg-[#FFF8EC]">
                    <svg class="w-4 h-4 text-[#F59E0B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="detail-card-title">Tabungan</h3>
            </div>

            <div id="savingContainer">
                <div class="empty-row">Belum ada data tabungan</div>
            </div>
        </div>

        {{-- ── 4. Penalti ── --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <div class="detail-card-icon bg-[#FFF5F5]">
                    <svg class="w-4 h-4 text-[#EF4444]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="detail-card-title">Penalti</h3>
            </div>

            <div id="penaltyContainer">
                <div class="empty-row">Belum ada data penalti</div>
            </div>
        </div>

    </div>
    {{-- ── end kolom kiri ── --}}

    {{-- ══════════════════════════════════════════════════════
         KOLOM KANAN — Quick Info + Action Buttons
    ══════════════════════════════════════════════════════ --}}
    <div style="width: 270px; flex-shrink: 0; position: sticky; top: 20px; align-self: flex-start;">

        {{-- Quick Info --}}
        <div class="quick-info-card mb-4">
            <div class="quick-info-title">
                <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Quick Info
            </div>

            <div class="qi-item">
                <div class="qi-label">Created By</div>
                <div class="flex items-center gap-2 mt-1">
                    <div class="avatar-ring" id="createdByInitial">?</div>
                    <span class="qi-value" id="createdByName">-</span>
                </div>
            </div>
            <div class="qi-item">
                <div class="qi-label">Created Date</div>
                <div class="qi-value" id="createdAt">-</div>
            </div>
            <div class="qi-item">
                <div class="qi-label">Last Updated</div>
                <div class="qi-value" id="updatedAt">-</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="quick-info-card">
            <button type="button" class="btn-action btn-cetak" onclick="handleCetakSlip()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34"/>
                </svg>
                Cetak Slip Gaji
            </button>

            <a id="btnEditData" href="#"
                class="btn-action btn-edit no-underline" style="text-decoration:none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                </svg>
                Edit Data
            </a>

            <button type="button" class="btn-action btn-hapus" onclick="handleHapus()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                </svg>
                Hapus Data
            </button>

            <button type="button" class="btn-action btn-batal" onclick="handleBatal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
        </div>

    </div>
    {{-- ── end kolom kanan ── --}}

</div>{{-- end main layout --}}

<script>
    // ════════════════════════════════════════════════════════════
    // HELPERS
    // ════════════════════════════════════════════════════════════
    const token = localStorage.getItem('access_token');

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function formatRupiah(angka) {
        return 'Rp. ' + Number(angka || 0).toLocaleString('id-ID') + ',00';
    }

    function formatTanggal(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        const tgl = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const jam = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        return `${tgl}, ${jam}`;
    }

    function getPayrollId() {
        const fromSession = sessionStorage.getItem('currentPayrollId');
        if (fromSession) return parseInt(fromSession, 10);
        const segments = window.location.pathname.split('/').filter(Boolean);
        const last = parseInt(segments[segments.length - 1], 10);
        return isNaN(last) ? null : last;
    }

    // Role badge color map
    const roleBadgeColors = {
        'developer'  : { bg: '#EAF2FF', text: '#1273EB', border: '#B1D3FF' },
        'manager'    : { bg: '#FFF4E5', text: '#E07B00', border: '#FFD89B' },
        'designer'   : { bg: '#F0FFF4', text: '#1A7F3C', border: '#A7E3BE' },
        'technician' : { bg: '#F3F4FF', text: '#5A5FDE', border: '#C5C8FF' },
    };

    function roleBadge(role) {
        const key = (role || '').toLowerCase();
        const cfg = roleBadgeColors[key] ?? { bg: '#F3F4F6', text: '#6B7280', border: '#D1D5DB' };
        return `<span class="role-badge"
            style="background:${cfg.bg};color:${cfg.text};border-color:${cfg.border}">
            ${escHtml(role)}
        </span>`;
    }

    // ════════════════════════════════════════════════════════════
    // RENDER DETAIL
    // ════════════════════════════════════════════════════════════
    function renderDetail(d) {
        const emp    = d.employee ?? {};
        const salary = d.salary   ?? {};

        // Informasi pribadi
        document.getElementById('detailNama').textContent           = emp.name            || '-';
        document.getElementById('detailNPK').textContent            = emp.employee_number  || '-';
        document.getElementById('detailTahunBergabung').textContent = emp.join_year        || '-';
        document.getElementById('detailTanggalLahir').textContent   = emp.birth_date
            ? new Date(emp.birth_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
            : '-';

        // Gaji pokok
        document.getElementById('detailGajiPokok').textContent = formatRupiah(salary.base_salary ?? 0);

        // Allowances (pendapatan lain)
        renderPayRows('allowanceContainer', salary.allowances ?? [], 'positive');

        // Savings (tabungan)
        renderPayRows('savingContainer', salary.savings ?? [], 'neutral');

        // Penalties (penalti)
        renderPayRows('penaltyContainer', salary.penalties ?? [], 'negative');

        // Quick info
        const creatorName = d.created_by?.name || 'Unknown';
        document.getElementById('createdByName').textContent    = creatorName;
        document.getElementById('createdByInitial').textContent = creatorName.charAt(0).toUpperCase();
        document.getElementById('createdAt').textContent        = formatTanggal(d.created_at);
        document.getElementById('updatedAt').textContent        = formatTanggal(d.updated_at);

        // Edit button URL
        const editUrl = "{{ route('payroll.edit', ':id') }}".replace(':id', d.id);
        const btnEdit = document.getElementById('btnEditData');
        if (btnEdit) {
            btnEdit.href = editUrl;
            btnEdit.addEventListener('click', e => {
                e.preventDefault();
                sessionStorage.setItem('currentPayrollId', d.id);
                window.location.href = editUrl;
            });
        }
    }

    // ── Render baris item (allowance / saving / penalty) ────────────────────
    function renderPayRows(containerId, items, amtType) {
        const container = document.getElementById(containerId);
        if (!items || items.length === 0) {
            container.innerHTML = '<div class="empty-row">Belum ada data</div>';
            return;
        }
        container.innerHTML = items.map(item => {
            const nama  = item.name        ?? item.nama        ?? '-';
            const sub   = item.type        ?? item.deskripsi   ?? item.description ?? '-';
            const amt   = item.amount      ?? item.jumlah      ?? 0;
            const amtClass = amtType === 'negative' ? 'amt-negative'
                           : amtType === 'positive' ? 'amt-positive'
                           : 'amt-neutral';
            const amtPrefix = amtType === 'negative' ? '' : '';
            return `
                <div class="pay-row">
                    <div>
                        <div class="pay-row-label">${escHtml(nama)}</div>
                        <div class="pay-row-sub">${escHtml(sub)}</div>
                    </div>
                    <span class="pay-row-amt ${amtClass}">${amtPrefix}${formatRupiah(amt)}</span>
                </div>`;
        }).join('');
    }

    // ════════════════════════════════════════════════════════════
    // ACTION HANDLERS
    // ════════════════════════════════════════════════════════════

    function handleCetakSlip() {
        const id = getPayrollId();
        if (!id) { Swal.fire('Error', 'ID payroll tidak ditemukan!', 'error'); return; }
        // TODO: redirect ke halaman preview slip gaji
        window.location.href = `/payroll/${id}/slip-preview`;
    }

    function handleHapus() {
        const id = getPayrollId();
        Swal.fire({
            title: 'Hapus Data Gaji?',
            text: 'Data gaji karyawan ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!',
        }).then(async result => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            try {
                const res = await fetch(`/api/payrolls/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                });
                if (res.ok) {
                    await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
                    window.location.href = "{{ route('payroll.index') }}";
                } else {
                    const r = await res.json();
                    Swal.fire('Gagal!', r.message ?? 'Gagal menghapus.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
            }
        });
    }

    function handleBatal() {
        window.location.href = "{{ route('payroll.index') }}";
    }

    // ════════════════════════════════════════════════════════════
    // INISIALISASI
    // ════════════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', async () => {
        // Isi info user dari localStorage
        const userName = localStorage.getItem('user_name') || 'User';
        const userRole = localStorage.getItem('user_role') || 'Staff';
        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = userName);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = userRole);

        const id = getPayrollId();

        if (!id || !token) {
            Swal.fire('Error', 'ID payroll tidak ditemukan atau belum login!', 'error');
            return;
        }

        try {
            const res = await fetch(`/api/payrolls/${id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await res.json();
            if (res.ok && result.status === 'success') {
                renderDetail(result.data);
            } else {
                Swal.fire('Gagal', result.message || 'Gagal memuat data payroll.', 'error');
            }
        } catch (err) {
            console.error('[Payroll Detail] API error:', err);
            Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
        }
    });
</script>

@endsection