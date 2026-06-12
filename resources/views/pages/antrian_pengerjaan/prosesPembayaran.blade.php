{{-- resources/views/pages/antrian_pengerjaan/prosesPembayaran.blade.php --}}
@extends('layouts.master')

@section('title', 'Proses Pembayaran Service')
@section('title_header', 'Antrian Pengerjaan | Proses Pembayaran')

@section('content')

<style>
    /* ── Sembunyikan layout utama agar modal terasa full overlay ── */
    body { overflow: hidden; }

    /* ── Overlay modal ── */
    #modalPembayaran {
        animation: fadeInModal 0.22s ease;
    }
    @keyframes fadeInModal {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    #modalPembayaran .modal-panel {
        animation: slideUpModal 0.24s ease;
    }
    @keyframes slideUpModal {
        from { transform: translateY(32px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    /* ── Metode Pembayaran Card ── */
    .payment-method-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border: 1.5px solid #E5E9F2;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #fff;
        font-size: 13px;
        font-weight: 600;
        color: #213F5C;
        user-select: none;
    }
    .payment-method-card:hover  { border-color: #1273EB; background: #F0F7FF; }
    .payment-method-card.selected { border-color: #1273EB; background: #EAF2FF; color: #1273EB; }
    .payment-method-card .check-icon {
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid #D1D5DB;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s; flex-shrink: 0;
    }
    .payment-method-card.selected .check-icon { background: #1273EB; border-color: #1273EB; }
    .payment-method-card .check-icon svg { display: none; }
    .payment-method-card.selected .check-icon svg { display: block; }

    /* ── Jasa Service List Item ── */
    .jasa-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: #F9FBFF;
        border: 1px solid #E5E9F2;
        border-radius: 10px;
        gap: 10px;
    }
    .jasa-item .jasa-nomor {
        min-width: 28px; height: 22px;
        background: #1273EB; color: #fff;
        border-radius: 5px; font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .jasa-item .jasa-nama {
        flex: 1; font-size: 13px; font-weight: 600; color: #213F5C;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .jasa-item .jasa-harga { font-size: 13px; font-weight: 700; color: #16A34A; white-space: nowrap; }
    .jasa-item .btn-hapus-jasa {
        width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
        border-radius: 7px; background: #FFF5F5; border: 1px solid #FFE0E0;
        color: #FF4D4D; cursor: pointer; flex-shrink: 0; transition: background 0.12s;
    }
    .jasa-item .btn-hapus-jasa:hover { background: #FFEBEB; }

    /* ── Input focus ── */
    .input-jasa:focus {
        border-color: #1273EB;
        box-shadow: 0 0 0 3px rgba(18,115,235,0.08);
        outline: none;
    }

    /* ── Error box ── */
    .error-box {
        display: flex; align-items: flex-start; gap: 8px;
        padding: 10px 12px; background: #FFF5F5; border: 1.5px solid #FFD5D5;
        border-radius: 10px; font-size: 12px; color: #DC2626; font-weight: 500; line-height: 1.5;
    }

    /* ── Info box metode terpilih ── */
    .info-box-selected {
        padding: 10px 12px; background: #EAF2FF; border: 1.5px solid #B1D3FF;
        border-radius: 10px; font-size: 12px; color: #213F5C; font-weight: 500;
    }
    .info-box-selected span {
        display: block; font-size: 10px; text-transform: uppercase;
        letter-spacing: 0.05em; color: #6B7280; font-weight: 600; margin-bottom: 2px;
    }
    .info-box-selected strong { font-size: 14px; font-weight: 700; color: #1273EB; }

    /* ── Pagination ── */
    .pagination-btn {
        padding: 6px 14px; border: 1.5px solid #E5E9F2; border-radius: 8px;
        font-size: 12px; font-weight: 600; color: #213F5C; background: #fff;
        cursor: pointer; transition: all 0.15s;
    }
    .pagination-btn:hover:not(:disabled) { border-color: #1273EB; color: #1273EB; background: #F0F7FF; }
    .pagination-btn:disabled { opacity: 0.38; cursor: not-allowed; }
</style>

{{-- ── MODAL FULL OVERLAY ── --}}
<div id="modalPembayaran"
    class="fixed inset-0 z-[998] flex items-center justify-center"
    style="background: rgba(15,23,42,0.48); backdrop-filter: blur(3px);">

    <div class="modal-panel bg-white rounded-[24px] shadow-2xl w-full mx-4 overflow-hidden flex flex-col"
        style="max-width: 1060px; max-height: 92vh;">

        {{-- ── Header ── --}}
        <div class="px-7 pt-6 pb-5 border-b border-[#F0F4FA] flex-shrink-0 flex items-center justify-between">
            <div>
                <h2 class="text-[19px] font-bold text-[#213F5C]">Proses Pembayaran Service</h2>
                <p class="text-[12px] text-gray-400 mt-0.5">Isi biaya jasa, pilih metode, lalu cetak nota</p>
            </div>
            <button type="button" onclick="handleBatalPembayaran()"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── Body (scrollable) ── --}}
        <div class="flex-1 overflow-y-auto px-7 py-6">
            <div class="grid grid-cols-3 gap-5">

                {{-- ═══ Kolom 1: Tambah Jasa Service ═══ --}}
                <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-4 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#1273EB] inline-block"></span>
                        <h3 class="text-[14px] font-bold text-[#1273EB]">Tambah Jasa Service</h3>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[12px] font-bold text-[#213F5C] mb-1.5">
                                Nama Jasa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="inputNamaJasa" placeholder="Contoh: Jasa Ganti Oli Mesin"
                                class="input-jasa w-full px-4 py-2.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-[#213F5C] mb-1.5">
                                Biaya Jasa (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="inputBiayaJasa" placeholder="Nominal biaya" min="0"
                                class="input-jasa w-full px-4 py-2.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                        </div>
                        <button type="button" id="btnTambahJasa"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed"
                            disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Tambah Jasa
                        </button>
                    </div>

                    {{-- Daftar jasa --}}
                    <div id="jasaListSection" class="hidden space-y-3">
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <p class="text-[12px] font-bold text-[#213F5C]">Daftar Jasa</p>
                            <span id="jasaCountBadge"
                                class="px-2 py-0.5 bg-[#1273EB] text-white text-[11px] font-bold rounded-full">0</span>
                        </div>
                        <div id="jasaListContainer" class="space-y-2 max-h-[220px] overflow-y-auto pr-0.5"></div>
                        <div id="jasaPagination" class="hidden flex items-center justify-between pt-1 border-t border-gray-100">
                            <button type="button" class="pagination-btn" id="btnPrevPage" onclick="prevPage()">← Prev</button>
                            <span id="pageLabel" class="text-[11px] font-semibold text-gray-500">1 / 1</span>
                            <button type="button" class="pagination-btn" id="btnNextPage" onclick="nextPage()">Next →</button>
                        </div>
                    </div>
                </div>

                {{-- ═══ Kolom 2: Metode Pembayaran ═══ --}}
                <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h3 class="text-[14px] font-bold text-[#213F5C]">Metode Pembayaran</h3>
                    </div>
                    <p class="text-[11px] text-gray-400 -mt-1">Pilih salah satu metode</p>

                    <div class="space-y-2.5" id="metodePembayaranList">
                        @foreach(['Tunai','BCA','Mandiri','BNI','BRI','QRIS'] as $m)
                        <div class="payment-method-card" data-metode="{{ $m }}" onclick="selectMetode('{{ $m }}')">
                            <div class="flex items-center gap-3">
                                @if($m === 'Tunai')
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                @elseif($m === 'QRIS')
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                @endif
                                <span>{{ $m }}</span>
                            </div>
                            <div class="check-icon">
                                <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ═══ Kolom 3: Ringkasan & Aksi ═══ --}}
                <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-4 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-[14px] font-bold text-[#213F5C]">Ringkasan Pembayaran</h3>
                    </div>

                    <div class="space-y-2 py-3 border-t border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-[12px] text-gray-500">Suku Cadang</span>
                            <span id="ringkasanSukuCadang" class="text-[12px] font-bold text-[#213F5C]">Rp 0</span>
                        </div>
                        <div id="ringkasanJasaRow" class="hidden flex justify-between items-center">
                            <span class="text-[12px] text-gray-500">Jasa (<span id="ringkasanJasaCount">0</span> item)</span>
                            <span id="ringkasanJasaTotal" class="text-[12px] font-bold text-[#213F5C]">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-[13px] font-bold text-[#213F5C]">Subtotal</span>
                            <span id="ringkasanSubtotal" class="text-[14px] font-bold text-[#213F5C]">Rp 0</span>
                        </div>
                        <div id="ringkasanDpRow" class="hidden flex justify-between items-center">
                            <span class="text-[12px] text-gray-500">Down Payment (sudah dibayar)</span>
                            <span id="ringkasanDpJumlah" class="text-[12px] font-bold text-[#F59E0B]">- Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#E5E9F2]"
                            style="border-top-width:1.5px;">
                            <span class="text-[13px] font-bold text-[#213F5C]">Total yang Dibayar</span>
                            <span id="ringkasanTotal" class="text-[16px] font-bold text-[#16A34A]">Rp 0</span>
                        </div>
                    </div>

                    {{-- Metode terpilih --}}
                    <div id="ringkasanMetodeBox" class="hidden info-box-selected">
                        <span>Metode Dipilih</span>
                        <strong id="ringkasanMetodeLabel">-</strong>
                    </div>

                    {{-- Error: belum ada jasa --}}
                    <div id="errorJasaBox" class="error-box">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"/>
                        </svg>
                        <span>Tambahkan minimal 1 jasa service</span>
                    </div>

                    {{-- Error: belum pilih metode --}}
                    <div id="errorMetodeBox" class="error-box" style="display:none">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"/>
                        </svg>
                        <span>Pilih metode pembayaran terlebih dahulu</span>
                    </div>

                    <div class="flex-1"></div>

                    {{-- Tombol Cetak Nota --}}
                    <button type="button" id="btnCetakNota"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed"
                        disabled onclick="handleCetakNota()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34"/>
                        </svg>
                        Cetak Nota Pembayaran
                    </button>

                    {{-- Tombol Batal --}}
                    <button type="button"
                        class="w-full flex items-center justify-center gap-2 py-3 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[14px] hover:bg-gray-50 transition-all"
                        onclick="handleBatalPembayaran()">
                        Batal & Tutup
                    </button>
                </div>

            </div>{{-- end grid --}}
        </div>{{-- end body --}}
    </div>{{-- end modal-panel --}}
</div>{{-- end modal --}}

<script>
    const token = localStorage.getItem('access_token');
    let jasaList        = [];
    let selectedMetode  = null;
    let totalSukuCadang = 0;
    let dpAmount        = 0;   // DP yang sudah dibayar saat pendaftaran antrian
    let dpStatus        = null; // 'dp' | 'paid' | 'unpaid'
    let currentPage     = 1;
    const PER_PAGE      = 10;

    function getTransactionId() {
        const fromSession = sessionStorage.getItem('currentAntrianId');
        if (fromSession) return parseInt(fromSession, 10);
        const segments = window.location.pathname.split('/').filter(Boolean);
        const idx = segments.indexOf('antrian-pengerjaan');
        if (idx !== -1 && segments[idx + 1]) return parseInt(segments[idx + 1], 10);
        return null;
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
    }

    // ── Load data suku cadang dari API ──────────────────────────────────────
    async function loadTransactionData() {
        const id = getTransactionId();
        if (!id) return;
        try {
            const res    = await fetch(`/api/transactions/${id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await res.json();
            if (!res.ok || result.status !== 'success') return;

            let totalSC = 0;
            (result.data.items || []).forEach(item => {
                const harga  = item.price ?? item.sparepart?.selling_price ?? 0;
                const jumlah = item.quantity ?? item.qty ?? 1;
                totalSC += Number(harga) * Number(jumlah);
            });
            totalSukuCadang = totalSC;

            // Ambil data DP dari transaksi
            dpStatus = result.data.status_payment ?? 'unpaid';
            dpAmount = (dpStatus === 'dp' && result.data.dp_amount)
                ? Number(result.data.dp_amount)
                : 0;

            updateUI();
        } catch (e) {
            console.error('Gagal load transaksi:', e);
        }
    }

    // ── Validasi form tambah jasa ───────────────────────────────────────────
    function validateJasaForm() {
        const nama  = document.getElementById('inputNamaJasa').value.trim();
        const biaya = document.getElementById('inputBiayaJasa').value.trim();
        const btn   = document.getElementById('btnTambahJasa');
        if (nama && biaya && Number(biaya) >= 0) {
            btn.disabled  = false;
            btn.className = 'w-full flex items-center justify-center gap-2 py-2.5 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] transition-all hover:bg-[#0E59B8] cursor-pointer';
        } else {
            btn.disabled  = true;
            btn.className = 'w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed';
        }
    }

    document.getElementById('inputNamaJasa').addEventListener('input',  validateJasaForm);
    document.getElementById('inputBiayaJasa').addEventListener('input', validateJasaForm);

    document.getElementById('inputNamaJasa').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('inputBiayaJasa').focus(); }
    });
    document.getElementById('inputBiayaJasa').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); const b = document.getElementById('btnTambahJasa'); if (!b.disabled) b.click(); }
    });

    // ── Tambah Jasa ─────────────────────────────────────────────────────────
    document.getElementById('btnTambahJasa').addEventListener('click', () => {
        const nama  = document.getElementById('inputNamaJasa').value.trim();
        const biaya = parseInt(document.getElementById('inputBiayaJasa').value.trim()) || 0;
        if (!nama) { Swal.fire('Oops!', 'Nama jasa wajib diisi!', 'warning'); return; }
        jasaList.push({ id: Date.now(), nama, biaya });
        document.getElementById('inputNamaJasa').value  = '';
        document.getElementById('inputBiayaJasa').value = '';
        validateJasaForm();
        currentPage = Math.ceil(jasaList.length / PER_PAGE);
        renderJasaList();
        updateUI();
    });

    // ── Render daftar jasa ──────────────────────────────────────────────────
    function renderJasaList() {
        const container  = document.getElementById('jasaListContainer');
        const section    = document.getElementById('jasaListSection');
        const badge      = document.getElementById('jasaCountBadge');
        const pagination = document.getElementById('jasaPagination');

        container.innerHTML = '';
        if (jasaList.length === 0) { section.classList.add('hidden'); return; }

        section.classList.remove('hidden');
        badge.textContent = jasaList.length;

        const totalPages = Math.ceil(jasaList.length / PER_PAGE);
        const start      = (currentPage - 1) * PER_PAGE;
        jasaList.slice(start, start + PER_PAGE).forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'jasa-item';
            div.innerHTML = `
                <div class="jasa-nomor">#${start + idx + 1}</div>
                <span class="jasa-nama" title="${escHtml(item.nama)}">${escHtml(item.nama)}</span>
                <span class="jasa-harga">${formatRupiah(item.biaya)}</span>
                <button type="button" class="btn-hapus-jasa" onclick="hapusJasa(${item.id})">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>`;
            container.appendChild(div);
        });

        if (totalPages > 1) {
            pagination.classList.remove('hidden');
            document.getElementById('pageLabel').textContent   = `${currentPage} / ${totalPages}`;
            document.getElementById('btnPrevPage').disabled    = currentPage <= 1;
            document.getElementById('btnNextPage').disabled    = currentPage >= totalPages;
        } else {
            pagination.classList.add('hidden');
        }
    }

    function prevPage() { if (currentPage > 1) { currentPage--; renderJasaList(); } }
    function nextPage() {
        const totalPages = Math.ceil(jasaList.length / PER_PAGE);
        if (currentPage < totalPages) { currentPage++; renderJasaList(); }
    }

    // ── Hapus jasa ──────────────────────────────────────────────────────────
    function hapusJasa(id) {
        jasaList = jasaList.filter(j => j.id !== id);
        const totalPages = Math.ceil(jasaList.length / PER_PAGE) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        renderJasaList();
        updateUI();
    }

    // ── Pilih metode ────────────────────────────────────────────────────────
    function selectMetode(metode) {
        selectedMetode = metode;
        document.querySelectorAll('.payment-method-card').forEach(c => {
            c.classList.toggle('selected', c.dataset.metode === metode);
        });
        const box = document.getElementById('ringkasanMetodeBox');
        box.classList.remove('hidden');
        document.getElementById('ringkasanMetodeLabel').textContent = metode;
        updateUI();
    }

    // ── Update seluruh UI (single source of truth) ──────────────────────────
    function updateUI() {
        const hasJasa   = jasaList.length > 0;
        const hasMetode = !!selectedMetode;
        const totalJasa = jasaList.reduce((acc, j) => acc + j.biaya, 0);
        const subtotal  = totalSukuCadang + totalJasa;
        const totalAll  = Math.max(0, subtotal - dpAmount);

        document.getElementById('ringkasanSukuCadang').textContent = formatRupiah(totalSukuCadang);
        document.getElementById('ringkasanSubtotal').textContent   = formatRupiah(subtotal);
        document.getElementById('ringkasanTotal').textContent      = formatRupiah(totalAll);

        const jasaRow = document.getElementById('ringkasanJasaRow');
        if (hasJasa) {
            jasaRow.classList.remove('hidden');
            document.getElementById('ringkasanJasaCount').textContent = jasaList.length;
            document.getElementById('ringkasanJasaTotal').textContent = formatRupiah(totalJasa);
        } else {
            jasaRow.classList.add('hidden');
        }

        // Tampilkan baris DP jika ada
        const dpRow = document.getElementById('ringkasanDpRow');
        if (dpAmount > 0) {
            dpRow.classList.remove('hidden');
            document.getElementById('ringkasanDpJumlah').textContent = '- ' + formatRupiah(dpAmount);
        } else {
            dpRow.classList.add('hidden');
        }

        document.getElementById('errorJasaBox').style.display   = !hasJasa                ? 'flex' : 'none';
        document.getElementById('errorMetodeBox').style.display = (hasJasa && !hasMetode) ? 'flex' : 'none';

        const btn = document.getElementById('btnCetakNota');
        if (hasJasa && hasMetode) {
            btn.disabled  = false;
            btn.className = 'w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-[#16A34A] text-white hover:bg-[#15803D] shadow-lg shadow-green-100 cursor-pointer';
        } else {
            btn.disabled  = true;
            btn.className = 'w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed';
        }
    }

    // ── Cetak Nota ──────────────────────────────────────────────────────────
    function handleCetakNota() {
        if (jasaList.length === 0) { Swal.fire('Oops!', 'Tambahkan minimal 1 jasa service!', 'warning'); return; }
        if (!selectedMetode)       { Swal.fire('Oops!', 'Pilih metode pembayaran!', 'warning'); return; }

        const id        = getTransactionId();
        const totalJasa = jasaList.reduce((acc, j) => acc + j.biaya, 0);
        const subtotal  = totalSukuCadang + totalJasa;
        const totalAll  = Math.max(0, subtotal - dpAmount);

        sessionStorage.setItem('notaPembayaran', JSON.stringify({
            transactionId   : id,
            jasaList        : jasaList.map(j => ({ nama: j.nama, biaya: j.biaya })),
            metode          : selectedMetode,
            totalSukuCadang,
            totalJasa,
            subtotal,
            dpAmount,
            dpStatus,
            totalAll,
            tanggal         : new Date().toISOString(),
        }));

        window.location.href = `/antrian-pengerjaan/${id}/nota-preview`;
    }

    // ── Batal & Tutup ───────────────────────────────────────────────────────
    function handleBatalPembayaran() {
        const id = getTransactionId();
        if (jasaList.length > 0) {
            Swal.fire({
                title: 'Batalkan Pembayaran?', text: 'Data jasa yang sudah diisi akan hilang.',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#FF4D4D', cancelButtonText: 'Lanjut Isi', confirmButtonText: 'Ya, Batalkan',
            }).then(r => { if (r.isConfirmed) window.location.href = `/antrian-pengerjaan/${id}`; });
        } else {
            window.location.href = `/antrian-pengerjaan/${id}`;
        }
    }

    // ── Init ────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        loadTransactionData();
        updateUI();
    });
</script>

@endsection