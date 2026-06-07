<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview Nota Pembayaran – GARASIBMW</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Arial',sans-serif;font-size:12px;background:#f0f4f8;color:#222;min-height:100vh;}

/* ── ACTION BAR ── */
.action-bar{
    background:#fff;border-bottom:1px solid #e5e9f2;
    padding:14px 32px;display:flex;align-items:center;
    justify-content:center;gap:10px;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:8px;
    font-size:13px;font-weight:600;cursor:pointer;border:1.5px solid transparent;
    text-decoration:none;transition:all .15s;white-space:nowrap;}
.btn-outline{border-color:#d1d5db;color:#374151;background:#fff;}
.btn-outline:hover{border-color:#9ca3af;background:#f9fafb;}
.btn-print{background:#1273EB;color:#fff;border-color:#1273EB;}
.btn-print:hover{background:#0E59B8;}
.btn-download{background:#16A34A;color:#fff;border-color:#16A34A;}
.btn-download:hover{background:#15803D;}

/* ── NAV PAGINATION ── */
.nav-box{
    width:794px;margin:20px auto 0;
    background:#EFF6FF;border:1.5px solid #BFDBFE;border-radius:12px;
    padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
}
.nav-box-left h3{font-size:13px;font-weight:700;color:#1e3a5f;margin-bottom:2px;}
.nav-box-left p{font-size:11px;color:#4B5563;margin-bottom:3px;}
.nav-box-left a{font-size:11px;color:#1273EB;font-weight:600;cursor:pointer;text-decoration:none;}
.nav-box-right{display:flex;gap:8px;}
.btn-nav{padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;
    border:1.5px solid;cursor:pointer;transition:all .15s;}
.btn-nav-prev{background:#fff;color:#374151;border-color:#D1D5DB;}
.btn-nav-prev:hover:not(:disabled){border-color:#9CA3AF;background:#F9FAFB;}
.btn-nav-next{background:#1273EB;color:#fff;border-color:#1273EB;}
.btn-nav-next:hover:not(:disabled){background:#0E59B8;}
.btn-nav:disabled{opacity:.4;cursor:not-allowed;}

/* ── WRAPPER ── */
.preview-wrapper{display:flex;flex-direction:column;align-items:center;padding:20px 16px 48px;}

/* ── KERTAS A4 (794px = 210mm) ── */
.nota-paper{
    background:#fff;width:794px;
    box-shadow:0 4px 24px rgba(0,0,0,.12);border-radius:4px;
    padding:32px 36px;margin-top:16px;
}

/* ── COPY ── */
.nota-copy{width:100%;}
.copy-separator{border:none;border-top:2px dashed #ccc;margin:20px 0;}

/* ── HEADER ── */
.copy-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;}
.brand-name{font-size:17px;font-weight:900;color:#111;letter-spacing:.5px;}
.brand-cabang{font-size:10px;color:#666;margin-top:2px;}
.nota-right{text-align:right;}
.nota-title-line{display:flex;align-items:center;justify-content:flex-end;gap:6px;}
.nota-title{font-size:13px;font-weight:800;color:#1273EB;text-transform:uppercase;letter-spacing:.4px;}
.badge{display:inline-block;padding:3px 8px;border-radius:4px;font-size:9px;font-weight:800;color:#fff;letter-spacing:.5px;}
.badge-admin{background:#1273EB;}
.badge-customer{background:#16A34A;}
.nota-meta{font-size:9.5px;color:#555;margin-top:3px;line-height:1.6;}

/* ── DIVIDERS ── */
.divider-bold{border:none;border-top:2px solid #1273EB;margin:7px 0 0;}
.divider-thin{border:none;border-top:1px solid #E5E9F2;margin:0;}

/* ── SECTION HEADER ── */
.section-header{
    background:#F1F5F9;padding:5px 10px;
    font-size:9.5px;font-weight:800;color:#334155;
    text-transform:uppercase;letter-spacing:.5px;
    border-left:3px solid #1273EB;
}

/* ── INFO GRID ── */
.info-grid{display:flex;padding:8px 4px 6px;gap:0;}
.info-cell{flex:1;padding-right:12px;}
.info-cell-label{font-size:8.5px;color:#94A3B8;text-transform:uppercase;letter-spacing:.3px;margin-bottom:2px;}
.info-cell-value{font-size:10.5px;font-weight:700;color:#111;line-height:1.4;}

/* ── TABEL LAYANAN ── */
.layanan-table{width:100%;border-collapse:collapse;}
.layanan-table th{font-size:9.5px;font-weight:700;color:#475569;
    padding:6px 6px;border-bottom:1.5px solid #CBD5E1;text-align:left;}
.layanan-table th.r,.layanan-table td.r{text-align:right;}
.layanan-table th.c,.layanan-table td.c{text-align:center;}
.layanan-table td{font-size:10px;padding:5px 6px;border-bottom:1px solid #F1F5F9;color:#222;vertical-align:middle;}
.layanan-table tr.empty td{height:22px;border-bottom:1px solid #F1F5F9;}
.layanan-table tr:last-child td{border-bottom:1.5px solid #CBD5E1;}

/* ── FOOTER ── */
.copy-footer{display:flex;justify-content:space-between;align-items:flex-start;
    margin-top:10px;padding-top:8px;}
.metode-label{font-size:9px;color:#64748B;margin-bottom:4px;}
.metode-badge{display:inline-block;background:#1273EB;color:#fff;
    font-size:9.5px;font-weight:700;padding:3px 12px;border-radius:5px;}
.summary-block{min-width:210px;}
.sum-row{display:flex;justify-content:space-between;font-size:9.5px;margin-bottom:3px;}
.sum-lbl{color:#64748B;}
.sum-val{font-weight:600;color:#111;}
.sum-sep{border:none;border-top:1.5px solid #111;margin:4px 0;}
.sum-total{display:flex;justify-content:space-between;align-items:center;}
.sum-total-lbl{font-size:11px;font-weight:800;color:#111;}
.sum-total-val{font-size:13px;font-weight:800;color:#1273EB;}

/* ── TTD ── */
.ttd-row{display:flex;justify-content:space-between;margin-top:24px;padding:0 20px;}
.ttd-cell{text-align:center;width:40%;}
.ttd-role{font-size:10px;color:#555;margin-bottom:42px;}/* space kosong untuk ttd */
.ttd-line{border:none;border-top:1px solid #888;width:100%;margin-bottom:5px;}
.ttd-name{font-size:11px;font-weight:700;color:#111;}

/* ── WATERMARK ── */
.wm{text-align:center;font-size:8.5px;color:#CBD5E1;margin-top:14px;}

/* ── PRINT ── */
@media print{
    @page{size:A4 portrait;margin:8mm 10mm;}
    body{background:#fff;}
    .action-bar,.nav-box{display:none!important;}
    .preview-wrapper{padding:0;}
    .nota-paper{box-shadow:none;border-radius:0;padding:0;margin:0;width:100%;}
}
</style>
</head>
<body>

<!-- ACTION BAR -->
<div class="action-bar">
    <button class="btn btn-outline" onclick="handleKembali()">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </button>
    <button class="btn btn-print" onclick="window.print()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34"/>
        </svg>
        Print Nota
    </button>
    <button class="btn btn-download" onclick="handleDownload()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Download PDF
    </button>
</div>

<!-- NAV PAGINATION (muncul jika item > 10) -->
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

    <!-- KERTAS NOTA -->
    <div class="nota-paper" id="notaPaper">
        <!-- ADMIN -->
        <div class="nota-copy" id="copyAdmin"></div>
        <hr class="copy-separator">
        <!-- CUSTOMER -->
        <div class="nota-copy" id="copyCustomer"></div>
    </div>
</div>

<script>
const token = localStorage.getItem('access_token');
const PER_PAGE = 10;
let nota = null, txData = null, allItems = [], currentPage = 1, totalPages = 1;

function fmtRp(n){ return 'Rp '+Number(n||0).toLocaleString('id-ID'); }
function esc(s){ const d=document.createElement('div');d.appendChild(document.createTextNode(s||''));return d.innerHTML; }
function fmtTgl(iso){
    try{ return new Date(iso).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}); }
    catch{ return '-'; }
}
function getIdFromUrl(){
    const s=window.location.pathname.split('/').filter(Boolean);
    const i=s.indexOf('antrian-pengerjaan');
    return i!==-1&&s[i+1]?s[i+1]:null;
}
function fmtNomor(txId){
    const n=new Date(); const pad=v=>String(v).padStart(2,'0');
    return n.getFullYear()+pad(n.getMonth()+1)+pad(n.getDate())+
           String(txId).padStart(4,'0');
}

function buildAllItems(){
    const items=[];
    (txData?.items??[]).forEach(it=>items.push({
        nama:it.item_name??'-', qty:it.qty??1,
        harga:it.price??0, subtotal:it.subtotal??0
    }));
    (nota.jasaList??[]).forEach(j=>items.push({
        nama:j.nama??'-', qty:1, harga:j.biaya??0, subtotal:j.biaya??0
    }));
    return items;
}

function renderCopy(type){
    const customer = txData?.vehicle?.customer ?? {};
    const vehicle  = txData?.vehicle ?? {};
    const cabangMap = {PELAJAR_PEJUANG:'Pelajar Pejuang'};
    const cabang = cabangMap[txData?.branch]??(txData?.branch??'Pusat');
    const nomorNota = 'TRX-'+fmtNomor(txData?.transaction_id??nota.transactionId);
    const tanggal = fmtTgl(nota.tanggal);
    const badgeClass = type==='admin'?'badge-admin':'badge-customer';
    const badgeLabel = type==='admin'?'ADMIN':'CUSTOMER';

    // items untuk halaman ini
    const start = (currentPage-1)*PER_PAGE;
    const pageItems = allItems.slice(start, start+PER_PAGE);
    const emptySlots = Math.max(0, PER_PAGE - pageItems.length);

    let rows='';
    pageItems.forEach(it=>{
        rows+=`<tr>
            <td>${esc(it.nama)}</td>
            <td class="c">${it.qty}</td>
            <td class="r">${fmtRp(it.harga)}</td>
            <td class="r">${fmtRp(it.subtotal)}</td>
        </tr>`;
    });
    for(let i=0;i<emptySlots;i++){
        rows+=`<tr class="empty"><td></td><td></td><td></td><td></td></tr>`;
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
            <div class="info-cell-value">${esc(customer.name??'-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Telepon</div>
            <div class="info-cell-value">${esc(customer.phone_number??'-')}</div>
        </div>
        <div class="info-cell" style="flex:2">
            <div class="info-cell-label">Alamat</div>
            <div class="info-cell-value">${esc(customer.address??'-')}</div>
        </div>
    </div>
    <div class="divider-thin"></div>

    <div class="section-header">INFORMASI KENDARAAN</div>
    <div class="info-grid">
        <div class="info-cell" style="flex:1.5">
            <div class="info-cell-label">Model</div>
            <div class="info-cell-value">${esc(vehicle.model??'-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Plat</div>
            <div class="info-cell-value">${esc(vehicle.license_plate??'-')}</div>
        </div>
        <div class="info-cell" style="flex:1">
            <div class="info-cell-label">Mesin</div>
            <div class="info-cell-value">${esc(vehicle.engine_code??'-')}</div>
        </div>
        <div class="info-cell" style="flex:1.5">
            <div class="info-cell-label">KM</div>
            <div class="info-cell-value">${esc(txData?.km_masuk??'-')}</div>
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
            <hr class="sum-sep">
            <div class="sum-total">
                <span class="sum-total-lbl">TOTAL:</span>
                <span class="sum-total-val">${fmtRp(nota.totalAll)}</span>
            </div>
        </div>
    </div>

    <div class="ttd-row">
        <div class="ttd-cell">
            <div class="ttd-role">Pelanggan,</div>
            <hr class="ttd-line">
            <div class="ttd-name">${esc(customer.name??'-')}</div>
        </div>
        <div class="ttd-cell">
            <div class="ttd-role">Penerima,</div>
            <hr class="ttd-line">
            <div class="ttd-name">Staff GARASIBMW</div>
        </div>
    </div>
    <div class="wm">&copy; ${new Date().getFullYear()} GARASIBMW | 404SquadNotFound</div>`;
}

function renderAll(){
    document.getElementById('copyAdmin').innerHTML    = renderCopy('admin');
    document.getElementById('copyCustomer').innerHTML = renderCopy('customer');
    updateNav();
}

function updateNav(){
    const nav = document.getElementById('navBox');
    if(totalPages<=1){ nav.style.display='none'; return; }
    nav.style.display='flex';
    document.getElementById('navSubtitle').textContent =
        `Total ${allItems.length} item, ditampilkan ${PER_PAGE} item per halaman`;
    document.getElementById('navPageLabel').textContent =
        `Halaman ${currentPage} dari ${totalPages}`;
    document.getElementById('btnPrev').disabled = currentPage<=1;
    document.getElementById('btnNext').disabled = currentPage>=totalPages;
}

function changePage(dir){
    const next = currentPage+dir;
    if(next<1||next>totalPages) return;
    currentPage=next;
    renderAll();
    document.getElementById('notaPaper').scrollIntoView({behavior:'smooth'});
}

function handleKembali(){
    const id = nota?.transactionId ?? getIdFromUrl();
    window.location.href = `/antrian-pengerjaan/${id}`;
}
function handleDownload(){
    if(!nota) return;
    const id = nota.transactionId ?? getIdFromUrl();
    
    // Gunakan form POST agar data JSON tidak terpotong oleh limit GET URL
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/antrian-pengerjaan/${id}/nota-pdf`;
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    const inputJasa = document.createElement('input');
    inputJasa.type = 'hidden';
    inputJasa.name = 'jasa_list';
    inputJasa.value = JSON.stringify(nota.jasaList ?? []);
    form.appendChild(inputJasa);

    const inputMetode = document.createElement('input');
    inputMetode.type = 'hidden';
    inputMetode.name = 'metode';
    inputMetode.value = nota.metode ?? '-';
    form.appendChild(inputMetode);

    const inputDownload = document.createElement('input');
    inputDownload.type = 'hidden';
    inputDownload.name = 'download';
    inputDownload.value = '1';
    form.appendChild(inputDownload);

    document.body.appendChild(form);
    form.submit();
}

async function init(){
    const raw = sessionStorage.getItem('notaPembayaran');
    if(!raw){ alert('Data nota tidak ditemukan. Silakan ulangi proses pembayaran.'); history.back(); return; }
    nota = JSON.parse(raw);

    try{
        const res = await fetch(`/api/transactions/${nota.transactionId}`,{
            headers:{'Authorization':`Bearer ${token}`,'Accept':'application/json'}
        });
        const r = await res.json();
        if(res.ok&&r.status==='success') txData=r.data;
    }catch(e){ console.warn('Gagal fetch transaksi:',e); }

    allItems   = buildAllItems();
    totalPages = Math.max(1, Math.ceil(allItems.length/PER_PAGE));
    renderAll();
}

document.addEventListener('DOMContentLoaded', init);
</script>
</body>
</html>
