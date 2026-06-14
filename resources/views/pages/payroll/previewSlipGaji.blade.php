<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Slip Gaji – GARASIBMW</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; background: #f0f4f8; color: #222; min-height: 100vh; }
        .action-bar { background: #fff; border-bottom: 1px solid #e5e9f2; padding: 14px 32px; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-outline { border-color: #d1d5db; color: #374151; background: #fff; }
        .btn-outline:hover { border-color: #9ca3af; background: #f9fafb; }
        .btn-print { background: #1273EB; color: #fff; border-color: #1273EB; }
        .btn-print:hover { background: #0E59B8; }
        .btn-download { background: #16A34A; color: #fff; border-color: #16A34A; }
        .btn-download:hover { background: #15803D; }
        
        .preview-wrapper { display: flex; flex-direction: column; align-items: center; padding: 20px 16px 48px; }
        .nota-paper { background: #fff; width: 794px; box-shadow: 0 4px 24px rgba(0, 0, 0, .12); border-radius: 4px; padding: 20px 28px; margin-top: 16px; }
        .nota-copy { width: 100%; }
        .copy-separator { border: none; border-top: 2px dashed #ccc; margin: 10px 0; }
        
        .copy-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 3px; }
        .brand-name { font-size: 15px; font-weight: 900; color: #111; letter-spacing: .5px; }
        .brand-cabang { font-size: 9px; color: #666; margin-top: 1px; font-weight: bold; }
        .nota-right { text-align: right; }
        .nota-title-line { display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
        .nota-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 8px; font-weight: 800; color: #fff; letter-spacing: .5px; }
        .badge-admin { background: #E11D48; }
        .badge-customer { background: #16A34A; }
        .nota-meta { font-size: 8.5px; color: #555; margin-top: 2px; line-height: 1.5; font-weight: bold; background: #F1F5F9; display: inline-block; padding: 3px 8px; border-radius: 3px;}
        
        .divider-bold { border: none; border-top: 2px solid; margin: 4px 0 0; }
        .divider-thin { border: none; border-top: 1px solid #E5E9F2; margin: 0; }
        
        .section-header { background: #F1F5F9; padding: 4px 8px; font-size: 8.5px; font-weight: 800; color: #111; text-transform: uppercase; letter-spacing: .5px; margin-top: 8px; margin-bottom: 4px;}
        
        .info-grid { display: flex; padding: 4px 8px 3px; gap: 0; }
        .info-cell { flex: 1; padding-right: 10px; }
        .info-cell-label { font-size: 7.5px; color: #111; font-weight: bold; margin-bottom: 1px; }
        .info-cell-value { font-size: 9px; font-weight: 800; color: #111; line-height: 1.3; }
        
        .layanan-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .layanan-table th { font-size: 8px; font-weight: 800; color: #111; padding: 4px 8px; border-bottom: 1px solid #CBD5E1; text-align: left; }
        .layanan-table td { font-size: 8.5px; padding: 4px 8px; border-bottom: 1px solid #F1F5F9; color: #111; font-weight: 700; vertical-align: middle; }
        .layanan-table th.r, .layanan-table td.r { text-align: right; }
        .layanan-table th.c, .layanan-table td.c { text-align: center; }
        .layanan-table tr:last-child td { border-bottom: none; }
        .text-red { color: #E11D48; }
        
        .summary-box { border: 1px solid #CBD5E1; border-radius: 4px; padding: 6px 8px; margin-top: 8px; }
        .sum-row { display: flex; justify-content: space-between; font-size: 8.5px; margin-bottom: 4px; align-items: center;}
        .sum-row-item { display: flex; gap: 5px; align-items: center; }
        .sum-lbl { color: #111; font-weight: bold; }
        .sum-val { font-weight: 800; color: #111; }
        .sum-sep { border: none; border-top: 1px solid #CBD5E1; margin: 4px 0; }
        .sum-total { display: flex; justify-content: space-between; align-items: center; }
        .sum-total-lbl { font-size: 10px; font-weight: 900; color: #111; }
        .sum-total-val { font-size: 11px; font-weight: 900; color: #16A34A; }
        
        .ttd-row { display: flex; justify-content: space-between; margin-top: 12px; padding: 0 40px; }
        .ttd-cell { text-align: center; width: 35%; }
        .ttd-role { font-size: 8px; color: #111; margin-bottom: 24px; }
        .ttd-line { border: none; border-top: 1px solid #111; width: 100%; margin-bottom: 3px; }
        .ttd-name { font-size: 9px; font-weight: 800; color: #111; }
        
        #exportContainer { display: none; width: 794px; background: #fff; margin: 0 auto; }
        .nota-paper-export { background: #fff; width: 794px !important; min-width: 794px !important; max-width: 794px !important; padding: 20px 28px; box-sizing: border-box; margin: 0 auto; }
        
        @media print {
            @page { size: A4 portrait; margin: 5mm; }
            body { background: #fff; }
            .action-bar, .preview-wrapper { display: none !important; }
            #exportContainer { display: block !important; position: relative !important; width: 100% !important; }
            .nota-paper-export { box-shadow: none; border-radius: 0; padding: 5mm 10mm; margin: 0; width: 100% !important; min-width: auto !important; max-width: none !important; page-break-inside: avoid; }
            .html2pdf__page-break { page-break-before: always !important; break-before: page !important; }
            .copy-separator { margin: 8px 0; }
        }
    </style>
</head>

<body>

    <div class="action-bar">
        <button class="btn btn-outline" onclick="handleKembali()">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </button>
        <button class="btn btn-print" onclick="handleCetakSlip()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34" /></svg>
            Cetak Slip Gaji
        </button>
        <button class="btn btn-download" onclick="handleDownload()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Download PDF
        </button>
    </div>

    <div id="exportContainer"></div>

    <div class="preview-wrapper">
        <div class="nota-paper" id="notaPaper">
            <div class="nota-copy" id="copyAdmin"></div>
            <hr class="copy-separator">
            <div class="nota-copy" id="copyCustomer"></div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('access_token');
        let slipData = null;
        let pId = '{{ $id ?? "" }}';

        function fmtRp(n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }
        function esc(s) { const d = document.createElement('div'); d.appendChild(document.createTextNode(s || '')); return d.innerHTML; }
        
        function fmtTgl(dateStr) {
            if (!dateStr) return '-';
            try { return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }); }
            catch { return '-'; }
        }

        function fmtNomor(id, dateStr) {
            const n = dateStr ? new Date(dateStr) : new Date(); 
            const pad = v => String(v).padStart(2, '0');
            return n.getFullYear() + pad(n.getMonth() + 1) + pad(n.getDate()) + '-' + String(id).padStart(4, '0');
        }

        function renderCopy(type) {
            if (!slipData) return 'Memuat data...';

            const emp = slipData.employee || {};
            const sal = slipData.salary || {};
            
            const isKaryawan = type === 'customer';
            const badgeClass = isKaryawan ? 'badge-customer' : 'badge-admin';
            const badgeLabel = isKaryawan ? 'KARYAWAN' : 'ADMIN';
            const colorTheme = isKaryawan ? '#16A34A' : '#1273EB';
            const roleColor = isKaryawan ? '#16A34A' : '#E11D48'; // Green vs Red badge for Admin

            const nomorSlip = 'SLIP-' + fmtNomor(slipData.id, slipData.created_at);
            const periode = `${slipData.month_name || '-'} ${slipData.year || '-'}`;

            // Rows Pendapatan Lain
            let rowsPendapatanLain = '';
            (sal.allowances || []).forEach(al => {
                rowsPendapatanLain += `<tr>
                    <td>${esc(al.name)}</td>
                    <td class="c">${esc(al.type || '-')}</td>
                    <td class="r">${fmtRp(al.amount)}</td>
                </tr>`;
            });
            if (!rowsPendapatanLain) rowsPendapatanLain = '<tr><td colspan="3" class="c" style="color:#94A3B8;">Tidak ada pendapatan lain</td></tr>';

            // Rows Penalti
            let rowsPenalti = '';
            (sal.penalties || []).forEach(p => {
                rowsPenalti += `<tr>
                    <td>${esc(p.name)}</td>
                    <td class="r text-red">- ${fmtRp(p.amount)}</td>
                </tr>`;
            });
            if (!rowsPenalti) rowsPenalti = '<tr><td colspan="2" class="c" style="color:#94A3B8;">Tidak ada potongan</td></tr>';

            // Rows Tabungan
            let rowsTabungan = '';
            (sal.savings || []).forEach(s => {
                rowsTabungan += `<tr>
                    <td>${esc(s.name)}</td>
                    <td class="r">${fmtRp(s.amount)}</td>
                </tr>`;
            });
            if (!rowsTabungan) rowsTabungan = '<tr><td colspan="2" class="c" style="color:#94A3B8;">Tidak ada tabungan</td></tr>';

            return `
            <div class="copy-header">
                <div>
                    <div class="brand-name">GARASIBMW</div>
                    <div class="brand-cabang">Cabang: Pelajar Pejuang</div>
                </div>
                <div class="nota-right">
                    <div class="nota-title-line">
                        <span class="nota-title" style="color:${colorTheme};">SLIP GAJI KARYAWAN</span>
                        <span class="badge ${badgeClass}" style="background-color:${roleColor};">${badgeLabel}</span>
                    </div>
                    <div style="margin-top:4px;">
                        <span class="nota-meta">No. ${esc(nomorSlip)}</span>
                    </div>
                    <div style="margin-top:2px;">
                        <span style="font-size:8px; font-weight:800; color:#111;">Periode: ${esc(periode)}</span>
                    </div>
                </div>
            </div>
            <div class="divider-bold" style="border-color:${colorTheme};"></div>

            <div class="section-header">INFORMASI KARYAWAN</div>
            <div class="info-grid">
                <div class="info-cell" style="flex:1.5">
                    <div class="info-cell-label">Nama Lengkap</div>
                    <div class="info-cell-value">${esc(emp.name)}</div>
                </div>
                <div class="info-cell" style="flex:1">
                    <div class="info-cell-label">NPK</div>
                    <div class="info-cell-value">${esc(emp.employee_number)}</div>
                </div>
                <div class="info-cell" style="flex:1">
                    <div class="info-cell-label">Jabatan</div>
                    <div class="info-cell-value">${esc(emp.role)}</div>
                </div>
                <div class="info-cell" style="flex:1">
                    <div class="info-cell-label">Tahun Bergabung</div>
                    <div class="info-cell-value">${esc(emp.join_year)}</div>
                </div>
                <div class="info-cell" style="flex:1.5">
                    <div class="info-cell-label">Tanggal Lahir</div>
                    <div class="info-cell-value">${fmtTgl(emp.birth_date)}</div>
                </div>
            </div>

            <div class="section-header">GAJI POKOK</div>
            <table class="layanan-table">
                <tr>
                    <td style="width:70%;">Gaji Pokok</td>
                    <td class="r" style="width:30%;">${fmtRp(sal.base_salary)}</td>
                </tr>
            </table>

            <div class="section-header">PENDAPATAN LAIN</div>
            <table class="layanan-table">
                <thead><tr>
                    <th style="width:50%">Keterangan</th>
                    <th class="c" style="width:20%">Jenis</th>
                    <th class="r" style="width:30%">Jumlah</th>
                </tr></thead>
                <tbody>${rowsPendapatanLain}</tbody>
            </table>

            <div class="section-header">PENALTI / POTONGAN</div>
            <table class="layanan-table">
                <thead><tr>
                    <th style="width:70%">Keterangan</th>
                    <th class="r" style="width:30%">Jumlah</th>
                </tr></thead>
                <tbody>${rowsPenalti}</tbody>
            </table>

            <div class="section-header">TABUNGAN / ALLOWANCE</div>
            <table class="layanan-table">
                <thead><tr>
                    <th style="width:70%">Keterangan</th>
                    <th class="r" style="width:30%">Jumlah</th>
                </tr></thead>
                <tbody>${rowsTabungan}</tbody>
            </table>

            <div class="summary-box">
                <div class="sum-row">
                    <span class="sum-lbl">RINCIAN PENDAPATAN:</span>
                    <div style="display:flex; gap:16px;">
                        <div class="sum-row-item">
                            <span class="sum-lbl" style="font-weight:normal;">Total Pemasukan:</span>
                            <span class="sum-val">${fmtRp(sal.total_income)}</span>
                        </div>
                        <div class="sum-row-item">
                            <span class="sum-lbl" style="font-weight:normal;">Total Penalti:</span>
                            <span class="sum-val text-red">- ${fmtRp(sal.total_deduction)}</span>
                        </div>
                        <div class="sum-row-item">
                            <span class="sum-lbl" style="font-weight:normal;">Total Tabungan:</span>
                            <span class="sum-val">${fmtRp(sal.total_savings)}</span>
                        </div>
                    </div>
                </div>
                <hr class="sum-sep">
                <div class="sum-total">
                    <span class="sum-total-lbl">GAJI BERSIH:</span>
                    <span class="sum-total-val">${fmtRp(sal.net_salary)}</span>
                </div>
            </div>

            <div class="ttd-row">
                <div class="ttd-cell">
                    <div class="ttd-role">Pelanggan,</div>
                    <hr class="ttd-line">
                    <div class="ttd-name">${esc(emp.name)}</div>
                </div>
                <div class="ttd-cell">
                    <div class="ttd-role">Penerima,</div>
                    <hr class="ttd-line">
                    <div class="ttd-name">Staff GARASIBMW</div>
                </div>
            </div>
            `;
        }

        function renderAll() {
            document.getElementById('copyAdmin').innerHTML = renderCopy('admin');
            document.getElementById('copyCustomer').innerHTML = renderCopy('customer');
        }

        function prepareExportContainer() {
            const container = document.getElementById('exportContainer');
            container.innerHTML = `
                <div class="nota-paper-export">
                    <div class="nota-copy">${renderCopy('admin')}</div>
                    <hr class="copy-separator">
                    <div class="nota-copy">${renderCopy('customer')}</div>
                </div>
            `;
        }

        async function init() {
            if (!pId) {
                pId = window.location.pathname.split('/').pop();
            }

            try {
                const res = await fetch(`/api/payrolls/${pId}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const r = await res.json();
                if (res.ok && r.status === 'success') {
                    slipData = r.data;
                    renderAll();
                } else {
                    Swal.fire('Error', r.message || 'Gagal memuat data slip gaji.', 'error');
                }
            } catch (e) { 
                console.error('Gagal fetch slip gaji:', e); 
                Swal.fire('Error', 'Gagal memuat data.', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', init);

        function handleKembali() {
            window.location.href = `/payroll/detail/${pId}`;
        }

        function handleCetakSlip() {
            prepareExportContainer();
            window.print();
        }

        async function handleDownload() {
            if (!slipData) return;

            Swal.fire({
                title: 'Menyiapkan PDF...',
                text: 'Mohon tunggu sebentar, dokumen sedang dirender.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            prepareExportContainer();
            const exportDiv = document.getElementById('exportContainer');
            const previewDiv = document.querySelector('.preview-wrapper');

            exportDiv.style.display = 'block';
            previewDiv.style.display = 'none';
            window.scrollTo(0, 0);

            const opt = {
                margin: 0,
                filename: `SlipGaji_GARASIBMW_${slipData.employee?.name || 'Karyawan'}_${slipData.month_name}${slipData.year}.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            setTimeout(async () => {
                try {
                    await html2pdf().set(opt).from(exportDiv).save();
                    exportDiv.style.display = 'none';
                    previewDiv.style.display = 'flex';
                    Swal.close();
                } catch (err) {
                    console.error('PDF error:', err);
                    exportDiv.style.display = 'none';
                    previewDiv.style.display = 'flex';
                    Swal.fire('Error', 'Terjadi kesalahan saat memproses dokumen.', 'error');
                }
            }, 500);
        }
    </script>
</body>
</html>