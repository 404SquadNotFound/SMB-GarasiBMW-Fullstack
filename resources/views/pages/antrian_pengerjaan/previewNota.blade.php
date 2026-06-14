<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Nota Pembayaran – GARASIBMW</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            background: #f0f4f8;
            color: #222;
            min-height: 100vh;
        }

        /* ── ACTION BAR ── */
        .action-bar {
            background: #fff;
            border-bottom: 1px solid #e5e9f2;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid transparent;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-outline {
            border-color: #d1d5db;
            color: #374151;
            background: #fff;
        }

        .btn-outline:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }

        .btn-print {
            background: #1273EB;
            color: #fff;
            border-color: #1273EB;
        }

        .btn-print:hover {
            background: #0E59B8;
        }

        .btn-download {
            background: #16A34A;
            color: #fff;
            border-color: #16A34A;
        }

        .btn-download:hover {
            background: #15803D;
        }

        /* ── NAV PAGINATION ── */
        .nav-box {
            width: 794px;
            margin: 20px auto 0;
            background: #EFF6FF;
            border: 1.5px solid #BFDBFE;
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-box-left h3 {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 2px;
        }

        .nav-box-left p {
            font-size: 11px;
            color: #4B5563;
            margin-bottom: 3px;
        }

        .nav-box-left a {
            font-size: 11px;
            color: #1273EB;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .nav-box-right {
            display: flex;
            gap: 8px;
        }

        .btn-nav {
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-nav-prev {
            background: #fff;
            color: #374151;
            border-color: #D1D5DB;
        }

        .btn-nav-prev:hover:not(:disabled) {
            border-color: #9CA3AF;
            background: #F9FAFB;
        }

        .btn-nav-next {
            background: #1273EB;
            color: #fff;
            border-color: #1273EB;
        }

        .btn-nav-next:hover:not(:disabled) {
            background: #0E59B8;
        }

        .btn-nav:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ── WRAPPER ── */
        .preview-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 16px 48px;
        }

        /* ── KERTAS A4 PREVIEW LAYAR (794px = 210mm) ── */
        .nota-paper {
            background: #fff;
            width: 794px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .12);
            border-radius: 4px;
            padding: 20px 28px;
            margin-top: 16px;
        }

        /* ── COPY ── */
        .nota-copy {
            width: 100%;
        }

        .copy-separator {
            border: none;
            border-top: 2px dashed #ccc;
            margin: 10px 0;
        }

        /* ── HEADER ── */
        .copy-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 3px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 900;
            color: #111;
            letter-spacing: .5px;
        }

        .brand-cabang {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
        }

        .nota-right {
            text-align: right;
        }

        .nota-title-line {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .nota-title {
            font-size: 12px;
            font-weight: 800;
            color: #1273EB;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 800;
            color: #fff;
            letter-spacing: .5px;
        }

        .badge-admin {
            background: #1273EB;
        }

        .badge-customer {
            background: #16A34A;
        }

        .nota-meta {
            font-size: 8.5px;
            color: #555;
            margin-top: 2px;
            line-height: 1.5;
        }

        /* ── DIVIDERS ── */
        .divider-bold {
            border: none;
            border-top: 2px solid #1273EB;
            margin: 4px 0 0;
        }

        .divider-thin {
            border: none;
            border-top: 1px solid #E5E9F2;
            margin: 0;
        }

        /* ── SECTION HEADER ── */
        .section-header {
            background: #F1F5F9;
            padding: 3px 8px;
            font-size: 8.5px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-left: 3px solid #1273EB;
        }

        /* ── INFO GRID ── */
        .info-grid {
            display: flex;
            padding: 4px 4px 3px;
            gap: 0;
        }

        .info-cell {
            flex: 1;
            padding-right: 10px;
        }

        .info-cell-label {
            font-size: 7.5px;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 1px;
        }

        .info-cell-value {
            font-size: 9.5px;
            font-weight: 700;
            color: #111;
            line-height: 1.3;
        }

        /* ── TABEL LAYANAN ── */
        .layanan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .layanan-table th {
            font-size: 8.5px;
            font-weight: 700;
            color: #475569;
            padding: 3px 5px;
            border-bottom: 1.5px solid #CBD5E1;
            text-align: left;
        }

        .layanan-table th.r,
        .layanan-table td.r {
            text-align: right;
        }

        .layanan-table th.c,
        .layanan-table td.c {
            text-align: center;
        }

        .layanan-table td {
            font-size: 9px;
            padding: 3px 5px;
            border-bottom: 1px solid #F1F5F9;
            color: #222;
            vertical-align: middle;
        }

        .layanan-table tr.empty td {
            height: 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        .layanan-table tr:last-child td {
            border-bottom: 1.5px solid #CBD5E1;
        }

        /* ── FOOTER ── */
        .copy-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 6px;
            padding-top: 4px;
        }

        .metode-label {
            font-size: 8px;
            color: #64748B;
            margin-bottom: 2px;
        }

        .metode-badge {
            display: inline-block;
            background: #1273EB;
            color: #fff;
            font-size: 8.5px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 4px;
        }

        .summary-block {
            min-width: 200px;
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            margin-bottom: 2px;
        }

        .sum-lbl {
            color: #64748B;
        }

        .sum-val {
            font-weight: 600;
            color: #111;
        }

        .sum-sep {
            border: none;
            border-top: 1.5px solid #111;
            margin: 3px 0;
        }

        .sum-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sum-total-lbl {
            font-size: 10px;
            font-weight: 800;
            color: #111;
        }

        .sum-total-val {
            font-size: 12px;
            font-weight: 800;
            color: #1273EB;
        }

        /* ── TTD ── */
        .ttd-row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            padding: 0 16px;
        }

        .ttd-cell {
            text-align: center;
            width: 40%;
        }

        .ttd-role {
            font-size: 9px;
            color: #555;
            margin-bottom: 24px;
        }

        .ttd-line {
            border: none;
            border-top: 1px solid #888;
            width: 100%;
            margin-bottom: 3px;
        }

        .ttd-name {
            font-size: 10px;
            font-weight: 700;
            color: #111;
        }

        /* ── WATERMARK ── */
        .wm {
            text-align: center;
            font-size: 7.5px;
            color: #CBD5E1;
            margin-top: 6px;
        }

        /* ── EXPORT CONTAINER ── */
        #exportContainer {
            display: none;
            width: 794px;
            background: #fff;
            margin: 0 auto;
        }

        .nota-paper-export {
            background: #fff;
            /* Paksa lebar mutlak supaya Flexbox tidak hancur saat di-capture */
            width: 794px !important;
            min-width: 794px !important;
            max-width: 794px !important;
            padding: 20px 28px;
            box-sizing: border-box;
            margin: 0 auto;
        }

        /* ── PRINT ── */
        @media print {
            @page {
                size: A4 portrait;
                margin: 5mm;
            }

            body {
                background: #fff;
            }

            .action-bar,
            .nav-box,
            .preview-wrapper {
                display: none !important;
            }

            #exportContainer {
                display: block !important;
                position: relative !important;
                width: 100% !important;
                /* Bebaskan dari 794px agar pas di kertas browser */
            }

            .nota-paper-export {
                box-shadow: none;
                border-radius: 0;
                padding: 5mm 10mm;
                margin: 0;
                width: 100% !important;
                min-width: auto !important;
                max-width: none !important;
                page-break-inside: avoid;
            }

            /* ── INI KUNCI PENYELAMAT PRINT PREVIEW BROWSER ── */
            .html2pdf__page-break {
                page-break-before: always !important;
                break-before: page !important;
            }

            .copy-separator {
                margin: 8px 0;
            }

            .ttd-role {
                margin-bottom: 20px;
            }

            .ttd-row {
                margin-top: 8px;
            }

            .wm {
                margin-top: 4px;
            }
        }
    </style>
</head>

<body>

    <div class="action-bar">
        <button class="btn btn-outline" onclick="handleKembali()">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </button>
        <button class="btn btn-print" onclick="handleCetakNota()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34" />
            </svg>
            Cetak Nota
        </button>
        <button class="btn btn-download" onclick="handleDownload()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Download PDF
        </button>
    </div>

    <div id="exportContainer"></div>

    <div class="preview-wrapper">
        <div class="nav-box" id="navBox" style="display:none;">
            <div class="nav-box-left">
                <h3>Navigasi Halaman Nota</h3>
                <p id="navSubtitle">Total 0 item, ditampilkan 10 item per halaman</p>
                <a id="navPageLabel">Halaman 1 dari 1</a>
            </div>
            <div class="nav-box-right">
                <button class="btn-nav btn-nav-prev" id="btnPrev" onclick="changePage(-1)">← Sebelumnya</button>
                <button class="btn-nav btn-nav-next" id="btnNext" onclick="changePage(1)">Selanjutnya →</button>
            </div>
        </div>

        <div class="nota-paper" id="notaPaper">
            <div class="nota-copy" id="copyAdmin"></div>
            <hr class="copy-separator">
            <div class="nota-copy" id="copyCustomer"></div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('access_token');
        const PER_PAGE = 10;
        let nota = null, txData = null, allItems = [], currentPage = 1, totalPages = 1;

        function fmtRp(n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }
        function esc(s) { const d = document.createElement('div'); d.appendChild(document.createTextNode(s || '')); return d.innerHTML; }
        function fmtTgl(iso) {
            try { return new Date(iso).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }); }
            catch { return '-'; }
        }
        function getIdFromUrl() {
            const s = window.location.pathname.split('/').filter(Boolean);
            const i = s.indexOf('antrian-pengerjaan');
            return i !== -1 && s[i + 1] ? s[i + 1] : null;
        }
        function fmtNomor(txId) {
            const n = new Date(); const pad = v => String(v).padStart(2, '0');
            return n.getFullYear() + pad(n.getMonth() + 1) + pad(n.getDate()) + String(txId).padStart(4, '0');
        }

        function buildAllItems() {
            const items = [];
            (txData?.items ?? []).forEach(it => items.push({
                nama: it.item_name ?? '-', qty: it.qty ?? 1,
                harga: it.price ?? 0, subtotal: it.subtotal ?? 0
            }));
            (nota.jasaList ?? []).forEach(j => items.push({
                nama: j.nama ?? '-', qty: 1, harga: j.biaya ?? 0, subtotal: j.biaya ?? 0
            }));
            return items;
        }

        function prepareExportContainer() {
            const container = document.getElementById('exportContainer');
            container.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const paper = document.createElement('div');
                paper.className = 'nota-paper-export';

                const copyAdmin = renderCopy('admin', i);
                const copyCustomer = renderCopy('customer', i);

                paper.innerHTML = `
            <div class="nota-copy">${copyAdmin}</div>
            <hr class="copy-separator">
            <div class="nota-copy">${copyCustomer}</div>
        `;
                container.appendChild(paper);

                // TAMBAHAN: Gunakan class bawaan library untuk memotong halaman dengan sempurna
                if (i < totalPages) {
                    const pageBreak = document.createElement('div');
                    pageBreak.className = 'html2pdf__page-break';
                    container.appendChild(pageBreak);
                }
            }
        }

        function renderCopy(type, pageIdx = currentPage) {
            const customer = txData?.vehicle?.customer ?? {};
            const vehicle = txData?.vehicle ?? {};
            const cabangMap = { PELAJAR_PEJUANG: 'Pelajar Pejuang' };
            const cabang = cabangMap[txData?.branch] ?? (txData?.branch ?? 'Pusat');
            const nomorNota = 'TRX-' + fmtNomor(txData?.transaction_id ?? nota.transactionId);
            const tanggal = fmtTgl(nota.tanggal);
            const badgeClass = type === 'admin' ? 'badge-admin' : 'badge-customer';
            const badgeLabel = type === 'admin' ? 'ADMIN' : 'CUSTOMER';

            const start = (pageIdx - 1) * PER_PAGE;
            const pageItems = allItems.slice(start, start + PER_PAGE);
            const emptySlots = Math.max(0, PER_PAGE - pageItems.length);

            let rows = '';
            pageItems.forEach(it => {
                rows += `<tr>
            <td>${esc(it.nama)}</td>
            <td class="c">${it.qty}</td>
            <td class="r">${fmtRp(it.harga)}</td>
            <td class="r">${fmtRp(it.subtotal)}</td>
        </tr>`;
            });
            for (let i = 0; i < emptySlots; i++) {
                rows += `<tr class="empty"><td></td><td></td><td></td><td></td></tr>`;
            }

            return `
    <div class="copy-header">
        <div>
            <div class="brand-name">GARASIBMW</div>
            <div class="brand-cabang">Cabang: ${esc(cabang)}</div>
        </div>
        <div class="nota-right">
            <div class="nota-title-line">
                <span class="nota-title">NOTA PEMBAYARAN</span>
                <span class="badge ${badgeClass}">${badgeLabel}</span>
            </div>
            <div class="nota-meta">No. ${esc(nomorNota)}<br>Tanggal: ${esc(tanggal)}</div>
        </div>
    </div>
    <div class="divider-bold"></div>
    <div class="divider-thin"></div>

    <div class="section-header">INFORMASI PELANGGAN</div>
    <div class="info-grid">
        <div class="info-cell" style="flex:1.2">
            <div class="info-cell-label">Nama</div>
            <div class="info-cell-value">${esc(customer.name ?? '-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Telepon</div>
            <div class="info-cell-value">${esc(customer.phone_number ?? '-')}</div>
        </div>
        <div class="info-cell" style="flex:2">
            <div class="info-cell-label">Alamat</div>
            <div class="info-cell-value">${esc(customer.address ?? '-')}</div>
        </div>
    </div>
    <div class="divider-thin"></div>

    <div class="section-header">INFORMASI KENDARAAN</div>
    <div class="info-grid">
        <div class="info-cell" style="flex:1.5">
            <div class="info-cell-label">Model</div>
            <div class="info-cell-value">${esc(vehicle.model ?? '-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Plat</div>
            <div class="info-cell-value">${esc(vehicle.license_plate ?? '-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Mesin</div>
            <div class="info-cell-value">${esc(vehicle.engine_code ?? '-')}</div>
        </div>
        <div class="info-cell" style="flex:1.5">
            <div class="info-cell-label">KM</div>
            <div class="info-cell-value">${esc(txData?.km_masuk ?? '-')}</div>
        </div>
    </div>
    <div class="divider-thin"></div>

    <div class="section-header">RINCIAN LAYANAN</div>
    <table class="layanan-table">
        <thead><tr>
            <th style="width:48%">Item</th>
            <th class="c" style="width:9%">Qty</th>
            <th class="r" style="width:22%">Harga</th>
            <th class="r" style="width:21%">Total</th>
        </tr></thead>
        <tbody>${rows}</tbody>
    </table>

    <div class="copy-footer">
        <div>
            <div class="metode-label">Metode Pembayaran</div>
            <span class="metode-badge">${esc(nota.metode)}</span>
        </div>
        <div class="summary-block">
            <div class="sum-row"><span class="sum-lbl">Subtotal Suku Cadang:</span><span class="sum-val">${fmtRp(nota.totalSukuCadang)}</span></div>
            <div class="sum-row"><span class="sum-lbl">Biaya Jasa Service:</span><span class="sum-val">${fmtRp(nota.totalJasa)}</span></div>
            ${(nota.dpAmount ?? 0) > 0 ? `
            <div class="sum-row" style="margin-top:3px;">
                <span class="sum-lbl">Subtotal:</span>
                <span class="sum-val">${fmtRp(nota.subtotal ?? (nota.totalSukuCadang + nota.totalJasa))}</span>
            </div>
            <div class="sum-row" style="color:#D97706;">
                <span style="color:#D97706;font-size:9.5px;">Down Payment (sudah dibayar):</span>
                <span style="font-weight:600;color:#D97706;">- ${fmtRp(nota.dpAmount)}</span>
            </div>` : ''}
            <hr class="sum-sep">
            <div class="sum-total">
                <span class="sum-total-lbl">${(nota.dpAmount ?? 0) > 0 ? 'SISA BAYAR:' : 'TOTAL:'}</span>
                <span class="sum-total-val">${fmtRp(nota.totalAll)}</span>
            </div>
        </div>
    </div>

    <div class="ttd-row">
        <div class="ttd-cell">
            <div class="ttd-role">Pelanggan,</div>
            <hr class="ttd-line">
            <div class="ttd-name">${esc(customer.name ?? '-')}</div>
        </div>
        <div class="ttd-cell">
            <div class="ttd-role">Penerima,</div>
            <hr class="ttd-line">
            <div class="ttd-name">Staff GARASIBMW</div>
        </div>
    </div>
    <div class="wm">&copy; ${new Date().getFullYear()} GARASIBMW | 404SquadNotFound</div>`;
        }

        function renderAll() {
            document.getElementById('copyAdmin').innerHTML = renderCopy('admin');
            document.getElementById('copyCustomer').innerHTML = renderCopy('customer');
            updateNav();
        }

        function updateNav() {
            const nav = document.getElementById('navBox');
            if (totalPages <= 1) { nav.style.display = 'none'; return; }
            nav.style.display = 'flex';
            document.getElementById('navSubtitle').textContent = `Total ${allItems.length} item, ditampilkan ${PER_PAGE} item per halaman`;
            document.getElementById('navPageLabel').textContent = `Halaman ${currentPage} dari ${totalPages}`;
            document.getElementById('btnPrev').disabled = currentPage <= 1;
            document.getElementById('btnNext').disabled = currentPage >= totalPages;
        }

        function changePage(dir) {
            const next = currentPage + dir;
            if (next < 1 || next > totalPages) return;
            currentPage = next;
            renderAll();
            document.getElementById('notaPaper').scrollIntoView({ behavior: 'smooth' });
        }

        async function init() {
            const raw = sessionStorage.getItem('notaPembayaran');
            if (!raw) { alert('Data nota tidak ditemukan. Silakan ulangi proses pembayaran.'); history.back(); return; }
            nota = JSON.parse(raw);

            try {
                const res = await fetch(`/api/transactions/${nota.transactionId}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const r = await res.json();
                if (res.ok && r.status === 'success') txData = r.data;
            } catch (e) { console.warn('Gagal fetch transaksi:', e); }

            allItems = buildAllItems();
            totalPages = Math.max(1, Math.ceil(allItems.length / PER_PAGE));
            renderAll();
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await init();
        });

        function handleKembali() {
            window.location.href = "{{ route('riwayat-transaksi.index') }}";
        }

        function handleCetakNota() {
            prepareExportContainer();
            window.print();
        }

        async function handleDownload() {
            if (!nota) return;
            const id = nota.transactionId ?? getIdFromUrl();
            if (!id) {
                Swal.fire('Error', 'ID transaksi tidak ditemukan!', 'error');
                return;
            }

            Swal.fire({
                title: 'Menyiapkan PDF...',
                text: 'Mohon tunggu sebentar, dokumen sedang dirender.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            prepareExportContainer();

            const exportDiv = document.getElementById('exportContainer');
            const previewDiv = document.querySelector('.preview-wrapper');

            exportDiv.style.display = 'block';
            previewDiv.style.display = 'none';

            // Paksa browser scroll ke paling atas biar nggak ada potongan aneh
            window.scrollTo(0, 0);

            // Setting bersih tanpa paksaan X dan Y
            const opt = {
                margin: 0,
                filename: `Nota_GARASIBMW_TRX-${fmtNomor(id)}.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    scrollY: 0
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Beri jeda 500ms agar browser selesai menggambar elemen yang baru dimunculkan
            setTimeout(async () => {
                try {
                    await html2pdf().set(opt).from(exportDiv).save();

                    exportDiv.style.display = 'none';
                    previewDiv.style.display = 'flex';

                    const res = await fetch(`/api/transactions/${id}/finalize`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        },
                    });
                    const result = await res.json();

                    if (res.ok && result.status === 'success') {
                        sessionStorage.removeItem('notaPembayaran');
                        sessionStorage.removeItem('notaSudahDicetak');

                        await Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Selesai!',
                            text: 'PDF berhasil didownload. Pembayaran lunas & data dipindahkan ke Riwayat Transaksi.',
                            timer: 2500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        window.location.href = '/riwayat-transaksi';
                    } else {
                        Swal.fire('Gagal!', result.message ?? 'Gagal menyelesaikan transaksi.', 'error');
                    }
                } catch (err) {
                    console.error('Finalize / PDF error:', err);
                    exportDiv.style.display = 'none';
                    previewDiv.style.display = 'flex';
                    Swal.fire('Error', 'Terjadi kesalahan saat memproses dokumen atau menghubungi server.', 'error');
                }
            }, 500); // 500 milidetik delay
        }
    </script>
</body>

</html>