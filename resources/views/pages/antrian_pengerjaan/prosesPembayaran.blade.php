{{-- resources/views/pages/antrian_pengerjaan/prosesPembayaran.blade.php --}}
@extends('layouts.master')

@section('title', 'Proses Pembayaran Service')
@section('title_header', 'Antrian Pengerjaan | Proses Pembayaran')

@section('content')

    <style>
        /* ── Metode Pembayaran Card ── */
        .payment-method-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border: 1.5px solid #E5E9F2;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.18s ease;
            background: #fff;
            font-size: 14px;
            font-weight: 600;
            color: #213F5C;
            user-select: none;
        }

        .payment-method-card:hover {
            border-color: #1273EB;
            background: #F0F7FF;
        }

        .payment-method-card.selected {
            border-color: #1273EB;
            background: #EAF2FF;
            color: #1273EB;
        }

        .payment-method-card .check-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s;
            flex-shrink: 0;
        }

        .payment-method-card.selected .check-icon {
            background: #1273EB;
            border-color: #1273EB;
        }

        .payment-method-card .check-icon svg {
            display: none;
        }

        .payment-method-card.selected .check-icon svg {
            display: block;
        }

        /* ── Jasa Service List Item ── */
        .jasa-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 14px;
            background: #F9FBFF;
            border: 1px solid #E5E9F2;
            border-radius: 10px;
            gap: 12px;
        }

        .jasa-item .jasa-nomor {
            min-width: 32px;
            height: 24px;
            background: #1273EB;
            color: #fff;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .jasa-item .jasa-nama {
            flex: 1;
            font-size: 13px;
            font-weight: 600;
            color: #213F5C;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .jasa-item .jasa-harga {
            font-size: 13px;
            font-weight: 700;
            color: #16A34A;
            white-space: nowrap;
        }

        .jasa-item .btn-hapus-jasa {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #FFF5F5;
            border: 1px solid #FFE0E0;
            color: #FF4D4D;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.15s;
        }

        .jasa-item .btn-hapus-jasa:hover {
            background: #FFEBEB;
        }

        /* ── Pagination ── */
        .pagination-btn {
            padding: 7px 16px;
            border: 1.5px solid #E5E9F2;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #213F5C;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
        }

        .pagination-btn:hover:not(:disabled) {
            border-color: #1273EB;
            color: #1273EB;
            background: #F0F7FF;
        }

        .pagination-btn:disabled {
            opacity: 0.38;
            cursor: not-allowed;
        }

        /* ── Ringkasan error box ── */
        .error-box {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 12px 14px;
            background: #FFF5F5;
            border: 1.5px solid #FFD5D5;
            border-radius: 10px;
            font-size: 12px;
            color: #DC2626;
            font-weight: 500;
            line-height: 1.5;
        }

        /* ── Ringkasan info box ── */
        .info-box-selected {
            padding: 12px 14px;
            background: #EAF2FF;
            border: 1.5px solid #B1D3FF;
            border-radius: 10px;
            font-size: 12px;
            color: #213F5C;
            font-weight: 500;
        }

        .info-box-selected span {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-box-selected strong {
            font-size: 14px;
            font-weight: 700;
            color: #1273EB;
        }

        /* ── Tambah Jasa input focus ── */
        .input-jasa:focus {
            border-color: #1273EB;
            box-shadow: 0 0 0 3px rgba(18, 115, 235, 0.08);
            outline: none;
        }

        /* ── Modal overlay ── */
        #modalPembayaran {
            animation: fadeInModal 0.22s ease;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #modalPembayaran .modal-panel {
            animation: slideUpModal 0.22s ease;
        }

        @keyframes slideUpModal {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    {{-- ════════════════════════════════════════════════
    MODAL PROSES PEMBAYARAN
    ════════════════════════════════════════════════ --}}
    <div id="modalPembayaran" class="fixed inset-0 z-[998] flex items-center justify-center"
        style="background: rgba(15,23,42,0.45); backdrop-filter: blur(2px);">

        <div class="modal-panel bg-white rounded-[24px] shadow-2xl w-full mx-4 overflow-hidden"
            style="max-width: 1100px; max-height: 92vh; display: flex; flex-direction: column;">

            {{-- Header --}}
            <div class="px-8 pt-7 pb-5 border-b border-[#F0F4FA] flex-shrink-0">
                <h2 class="text-[20px] font-bold text-[#213F5C]">Proses Pembayaran Service</h2>
                <p class="text-[13px] text-gray-400 mt-0.5">Lengkapi informasi jasa service dan pilih metode pembayaran</p>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto px-8 py-6">
                <div class="grid grid-cols-3 gap-6">

                    {{-- ── Kolom 1: Tambah Jasa Service ── --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-6 flex flex-col gap-5 shadow-sm">

                        {{-- Section title --}}
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#1273EB] inline-block"></span>
                            <h3 class="text-[15px] font-bold text-[#1273EB]">Tambah Jasa Service</h3>
                        </div>

                        {{-- Form input --}}
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[13px] font-bold text-[#213F5C] mb-1.5">
                                    Nama Jasa Service <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="inputNamaJasa" placeholder="Contoh: Jasa Ganti Oli Mesin"
                                    class="input-jasa w-full px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-[#213F5C] mb-1.5">
                                    Biaya Jasa (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="inputBiayaJasa" placeholder="Masukkan nominal biaya" min="0"
                                    class="input-jasa w-full px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                            </div>
                            <button type="button" id="btnTambahJasa"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed"
                                disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                Tambah Jasa
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div id="jasaListSection" class="hidden space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-[13px] font-bold text-[#213F5C]">Daftar Jasa Service</p>
                                <span id="jasaCountBadge"
                                    class="px-2.5 py-0.5 bg-[#1273EB] text-white text-[11px] font-bold rounded-full">
                                    0 Item
                                </span>
                            </div>

                            {{-- List jasa (paginated) --}}
                            <div id="jasaListContainer" class="space-y-2"></div>

                            {{-- Pagination --}}
                            <div id="jasaPagination"
                                class="hidden flex items-center justify-between pt-1 border-t border-gray-100">
                                <button type="button" class="pagination-btn" id="btnPrevPage" onclick="prevPage()">←
                                    Prev</button>
                                <span id="pageLabel" class="text-[12px] font-semibold text-gray-500">Halaman 1 dari 1</span>
                                <button type="button" class="pagination-btn" id="btnNextPage" onclick="nextPage()">Next
                                    →</button>
                            </div>
                        </div>
                    </div>

                    {{-- ── Kolom 2: Metode Pembayaran ── --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-6 flex flex-col gap-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="text-[15px] font-bold text-[#213F5C]">Metode Pembayaran</h3>
                        </div>
                        <p class="text-[12px] text-gray-400 -mt-2">Pilih salah satu metode pembayaran</p>

                        <div class="space-y-3" id="metodePembayaranList">
                            @foreach(['BCA', 'Mandiri', 'BNI', 'BRI', 'Tunai'] as $metode)
                                <div class="payment-method-card" data-metode="{{ $metode }}"
                                    onclick="selectMetode('{{ $metode }}')">
                                    <span>{{ $metode }}</span>
                                    <div class="check-icon">
                                        <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Kolom 3: Ringkasan Pembayaran ── --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-6 flex flex-col gap-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-[15px] font-bold text-[#213F5C]">Ringkasan Pembayaran</h3>
                        </div>

                        {{-- Breakdown harga --}}
                        <div class="space-y-2.5 py-2 border-t border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-[13px] text-gray-500">Suku Cadang</span>
                                <span id="ringkasanSukuCadang" class="text-[13px] font-bold text-[#213F5C]">Rp 0</span>
                            </div>
                            <div id="ringkasanJasaRow" class="hidden flex justify-between items-center">
                                <span class="text-[13px] text-gray-500">Jasa Service (<span id="ringkasanJasaCount">0</span>
                                    item)</span>
                                <span id="ringkasanJasaTotal" class="text-[13px] font-bold text-[#213F5C]">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-[14px] font-bold text-[#213F5C]">Total Pembayaran</span>
                            <span id="ringkasanTotal" class="text-[17px] font-bold text-[#16A34A]">Rp 0</span>
                        </div>

                        {{-- Metode terpilih atau error --}}
                        <div id="ringkasanMetodeBox" class="hidden info-box-selected">
                            <span>Metode Pembayaran Dipilih:</span>
                            <strong id="ringkasanMetodeLabel">-</strong>
                        </div>

                        {{-- Error: belum ada jasa service --}}
                        <div id="errorJasaBox" class="error-box">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Tambahkan minimal 1 jasa service terlebih dahulu</span>
                        </div>

                        {{-- Error: belum pilih metode --}}
                        <div id="errorMetodeBox" class="error-box" style="display:none">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Pilih metode pembayaran terlebih dahulu</span>
                        </div>

                        {{-- Tombol Cetak Nota --}}
                        <button type="button" id="btnCetakNota"
                            class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed"
                            disabled onclick="handleCetakNota()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34" />
                            </svg>
                            Cetak Nota Pembayaran
                        </button>

                        {{-- Tombol Batal --}}
                        <button type="button" id="btnBatalPembayaran"
                            class="w-full flex items-center justify-center gap-2 py-3.5 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[14px] hover:bg-gray-50 transition-all"
                            onclick="handleBatalPembayaran()">
                            Batal & Tutup
                        </button>
                    </div>

                </div>{{-- end grid --}}
            </div>{{-- end body --}}
        </div>{{-- end modal-panel --}}
    </div>{{-- end modal --}}

    <script>
        // ── State ─────────────────────────────────────────────────────────────────
        const token = localStorage.getItem('access_token');
        let jasaList = [];        // [{id, nama, biaya}]
        let selectedMetode = null;
        let totalSukuCadang = 0;         // diisi dari API / sessionStorage
        let currentPage = 1;
        const PER_PAGE = 10;

        // ── Ambil transaction ID ──────────────────────────────────────────────────
        function getTransactionId() {
            // Prioritas: sessionStorage (set saat klik dari detail)
            const fromSession = sessionStorage.getItem('currentAntrianId');
            if (fromSession) return parseInt(fromSession, 10);
            // Fallback: URL segment terakhir sebelum /pembayaran
            const segments = window.location.pathname.split('/').filter(Boolean);
            // URL: /antrian-pengerjaan/{id}/pembayaran
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
            if (!angka && angka !== 0) return 'Rp 0';
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        // ── Load data suku cadang dari API ────────────────────────────────────────
        async function loadTransactionData() {
            const id = getTransactionId();
            if (!id) return;

            try {
                const res = await fetch(`/api/transactions/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (!res.ok || result.status !== 'success') return;

                const data = result.data;
                const items = data.items || [];

                // Hitung total suku cadang
                let totalSC = 0;
                items.forEach(item => {
                    const harga = item.harga_num
                        ?? item.sparepart?.selling_price
                        ?? 0;
                    const jumlah = item.quantity ?? parseInt(item.jumlah) ?? 1;
                    totalSC += Number(harga) * Number(jumlah);
                });

                totalSukuCadang = totalSC;
                document.getElementById('ringkasanSukuCadang').textContent = formatRupiah(totalSukuCadang);
                updateRingkasan();
            } catch (e) {
                console.error('Gagal load transaksi:', e);
            }
        }

        // ── Validasi form tambah jasa ─────────────────────────────────────────────
        function validateJasaForm() {
            const nama = document.getElementById('inputNamaJasa').value.trim();
            const biaya = document.getElementById('inputBiayaJasa').value.trim();
            const btn = document.getElementById('btnTambahJasa');

            if (nama && biaya && Number(biaya) >= 0) {
                btn.disabled = false;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] transition-all hover:bg-[#0E59B8] cursor-pointer';
            } else {
                btn.disabled = true;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed';
            }
        }

        document.getElementById('inputNamaJasa').addEventListener('input', validateJasaForm);
        document.getElementById('inputBiayaJasa').addEventListener('input', validateJasaForm);

        // ── Tambah Jasa ───────────────────────────────────────────────────────────
        document.getElementById('btnTambahJasa').addEventListener('click', () => {
            const nama = document.getElementById('inputNamaJasa').value.trim();
            const biaya = parseInt(document.getElementById('inputBiayaJasa').value.trim()) || 0;

            if (!nama) {
                Swal.fire('Oops!', 'Nama jasa service wajib diisi!', 'warning');
                return;
            }

            jasaList.push({ id: Date.now(), nama, biaya });

            // Reset form
            document.getElementById('inputNamaJasa').value = '';
            document.getElementById('inputBiayaJasa').value = '';
            validateJasaForm();

            // Langsung ke halaman terakhir setelah tambah
            currentPage = Math.ceil(jasaList.length / PER_PAGE);

            renderJasaList();
            updateRingkasan();
            updateCetakBtn();
        });

        // Enter di input biaya = klik tambah
        document.getElementById('inputBiayaJasa').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const btn = document.getElementById('btnTambahJasa');
                if (!btn.disabled) btn.click();
            }
        });

        // ── Render daftar jasa (paginated) ────────────────────────────────────────
        function renderJasaList() {
            const container = document.getElementById('jasaListContainer');
            const section = document.getElementById('jasaListSection');
            const badge = document.getElementById('jasaCountBadge');
            const pagination = document.getElementById('jasaPagination');
            const btnPrev = document.getElementById('btnPrevPage');
            const btnNext = document.getElementById('btnNextPage');
            const pageLabel = document.getElementById('pageLabel');

            container.innerHTML = '';

            if (jasaList.length === 0) {
                section.classList.add('hidden');
                return;
            }

            section.classList.remove('hidden');
            badge.textContent = jasaList.length + ' Item';

            const totalPages = Math.ceil(jasaList.length / PER_PAGE);
            const start = (currentPage - 1) * PER_PAGE;
            const pageItems = jasaList.slice(start, start + PER_PAGE);

            pageItems.forEach((item, idx) => {
                const globalIdx = start + idx + 1;
                const div = document.createElement('div');
                div.className = 'jasa-item';
                div.innerHTML = `
                    <div class="jasa-nomor">#${globalIdx}</div>
                    <span class="jasa-nama" title="${escHtml(item.nama)}">${escHtml(item.nama)}</span>
                    <span class="jasa-harga">${formatRupiah(item.biaya)}</span>
                    <button type="button" class="btn-hapus-jasa" onclick="hapusJasa(${item.id})" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
                container.appendChild(div);
            });

            // Pagination
            if (totalPages > 1) {
                pagination.classList.remove('hidden');
                pageLabel.textContent = `Halaman ${currentPage} dari ${totalPages}`;
                btnPrev.disabled = currentPage <= 1;
                btnNext.disabled = currentPage >= totalPages;
            } else {
                pagination.classList.add('hidden');
            }
        }

        function prevPage() {
            if (currentPage > 1) { currentPage--; renderJasaList(); }
        }
        function nextPage() {
            const totalPages = Math.ceil(jasaList.length / PER_PAGE);
            if (currentPage < totalPages) { currentPage++; renderJasaList(); }
        }

        // ── Hapus jasa ────────────────────────────────────────────────────────────
        function hapusJasa(id) {
            jasaList = jasaList.filter(j => j.id !== id);

            // Pastikan currentPage tidak melebihi total halaman baru
            const totalPages = Math.ceil(jasaList.length / PER_PAGE) || 1;
            if (currentPage > totalPages) currentPage = totalPages;

            renderJasaList();
            updateRingkasan();
            updateCetakBtn();
        }

        // ── Pilih metode pembayaran ───────────────────────────────────────────────
        function selectMetode(metode) {
            selectedMetode = metode;

            document.querySelectorAll('.payment-method-card').forEach(card => {
                if (card.dataset.metode === metode) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            // Ringkasan
            document.getElementById('ringkasanMetodeBox').classList.remove('hidden');
            document.getElementById('ringkasanMetodeLabel').textContent = metode;

            updateCetakBtn();
        }

        // ── Update seluruh UI ringkasan + error + tombol (single source of truth) ──
        function updateRingkasan() { updateUI(); }   // alias agar pemanggil lama tetap jalan
        function updateCetakBtn() { updateUI(); }   // alias agar pemanggil lama tetap jalan

        function updateUI() {
            const hasJasa = jasaList.length > 0;
            const hasMetode = !!selectedMetode;

            const totalJasa = jasaList.reduce((acc, j) => acc + j.biaya, 0);
            const totalAll = totalSukuCadang + totalJasa;

            // ── Ringkasan angka ───────────────────────────────────────────────────
            document.getElementById('ringkasanSukuCadang').textContent = formatRupiah(totalSukuCadang);
            document.getElementById('ringkasanTotal').textContent = formatRupiah(totalAll);

            const jasaRow = document.getElementById('ringkasanJasaRow');
            if (hasJasa) {
                jasaRow.classList.remove('hidden');
                document.getElementById('ringkasanJasaCount').textContent = jasaList.length;
                document.getElementById('ringkasanJasaTotal').textContent = formatRupiah(totalJasa);
            } else {
                jasaRow.classList.add('hidden');
            }

            // ── Error box: jasa belum ada ─────────────────────────────────────────
            // Muncul hanya kalau belum ada jasa sama sekali
            const errorJasaBox = document.getElementById('errorJasaBox');
            errorJasaBox.style.display = !hasJasa ? 'flex' : 'none';

            // ── Error box: metode belum dipilih ───────────────────────────────────
            // Muncul hanya kalau jasa sudah ada TAPI metode belum dipilih
            const errorMetodeBox = document.getElementById('errorMetodeBox');
            errorMetodeBox.style.display = (hasJasa && !hasMetode) ? 'flex' : 'none';

            // ── Tombol Cetak Nota ─────────────────────────────────────────────────
            const btn = document.getElementById('btnCetakNota');
            if (hasJasa && hasMetode) {
                btn.disabled = false;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-[14px] transition-all bg-[#16A34A] text-white hover:bg-[#15803D] shadow-lg shadow-green-100 cursor-pointer';
            } else {
                btn.disabled = true;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed';
            }
        }

        // ── Cetak Nota ────────────────────────────────────────────────────────────
        function handleCetakNota() {
            if (jasaList.length === 0) {
                Swal.fire('Oops!', 'Tambahkan minimal 1 jasa service!', 'warning');
                return;
            }
            if (!selectedMetode) {
                Swal.fire('Oops!', 'Pilih metode pembayaran terlebih dahulu!', 'warning');
                return;
            }

            const transactionId = getTransactionId();
            const totalJasa = jasaList.reduce((acc, j) => acc + j.biaya, 0);
            const totalAll = totalSukuCadang + totalJasa;

            // Simpan ke sessionStorage supaya halaman preview bisa baca
            sessionStorage.setItem('notaPembayaran', JSON.stringify({
                transactionId,
                jasaList: jasaList.map(j => ({ nama: j.nama, biaya: j.biaya })),
                metode: selectedMetode,
                totalSukuCadang,
                totalJasa,
                totalAll,
                tanggal: new Date().toISOString(),
            }));

            // Navigasi ke halaman preview di tab yang sama
            window.location.href = `/antrian-pengerjaan/${transactionId}/previewNota`;
        }

        // ── Batal & Tutup ─────────────────────────────────────────────────────────
        function handleBatalPembayaran() {
            if (jasaList.length > 0) {
                Swal.fire({
                    title: 'Batalkan Pembayaran?',
                    text: 'Data jasa service yang sudah diisi akan hilang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF4D4D',
                    cancelButtonText: 'Lanjut Isi',
                    confirmButtonText: 'Ya, Batalkan',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const id = getTransactionId();
                        window.location.href = `/antrian-pengerjaan/${id}`;
                    }
                });
            } else {
                const id = getTransactionId();
                window.location.href = `/antrian-pengerjaan/${id}`;
            }
        }

        // ── Init ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            loadTransactionData();
            updateRingkasan();
            updateCetakBtn();
        });

        // Enter di nama jasa = fokus ke biaya
        document.getElementById('inputNamaJasa').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('inputBiayaJasa').focus();
            }
        });
    </script>

@endsection