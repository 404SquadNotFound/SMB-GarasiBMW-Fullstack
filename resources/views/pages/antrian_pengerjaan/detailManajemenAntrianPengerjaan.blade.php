{{-- resources/views/pages/antrian_pengerjaan/detailManajemenAntrianPengerjaan.blade.php --}}
{{--
    TODO Backend:
    - Kirim $antrian (Eloquent model) dari Controller ke view ini
    - Endpoint ubah status: PUT/PATCH /api/antrian-pengerjaan/{id}/status
    - Endpoint hapus: DELETE /api/antrian-pengerjaan/{id}
    - $antrian->suku_cadang → relasi ke tabel antrian_suku_cadang
--}}
@extends('layouts.master')

@section('title', 'Detail Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

@section('content')
@include('layouts.detail_wrapper_antrian')

<script>
    // ── Config warna per status ───────────────────────────────────────────────
    const statusConfig = {
        'pengecekan'  : { border: '#FDE68A', bg: '#FFF8EC', text: '#F59E0B', chevron: '#F59E0B', optClass: 'status-option-pengecekan'  },
        'menunggu'    : { border: '#E5E7EB', bg: '#F5F5F5', text: '#6B7280', chevron: '#6B7280', optClass: 'status-option-menunggu'     },
        'dikerjakan'  : { border: '#B1D3FF', bg: '#EAF2FF', text: '#1273EB', chevron: '#1273EB', optClass: 'status-option-dalamproses'  },
        'dibatalkan'  : { border: '#FFE0E0', bg: '#FFF5F5', text: '#FF4D4D', chevron: '#FF4D4D', optClass: 'status-option-dibatalkan'   },
        'selesai'     : { border: '#A7F3D0', bg: '#EDFBF3', text: '#16A34A', chevron: '#16A34A', optClass: 'status-option-selesai'      },
    };

    const statusList        = Object.keys(statusConfig);
    const btnPembayaran     = document.getElementById('btnProsesPembayaran');
    const token             = localStorage.getItem('access_token');
    let   currentStatus     = 'pengecekan';
    let   isStatusDropOpen  = false;
    let   currentTransactionId = null;

    // ── Render opsi status ────────────────────────────────────────────────────
    function renderStatusOptions() {
        const container = document.getElementById('statusDropdownItems');
        container.innerHTML = '';
        statusList.forEach(status => {
            const cfg = statusConfig[status];
            const div = document.createElement('div');
            div.className = `status-option-item ${cfg.optClass}`;
            div.textContent = status;
            div.addEventListener('click', () => selectStatus(status));
            container.appendChild(div);
        });
    }

    // ── Pilih status ──────────────────────────────────────────────────────────
    async function selectStatus(newStatus) {
        if (newStatus === currentStatus) { closeStatusDropdown(); return; }

        const prevStatus = currentStatus;
        currentStatus    = newStatus;

        applyStatusStyle(newStatus);
        closeStatusDropdown();

        if (!currentTransactionId) return;

        try {
            const res = await fetch(`/api/transactions/${currentTransactionId}/status`, {
                method : 'PUT',
                headers: {
                    'Content-Type' : 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept'       : 'application/json',
                },
                body: JSON.stringify({ status_service: newStatus }),
            });
            const result = await res.json();

            if (res.ok) {
                document.getElementById('updatedAt').textContent = formatTanggal(new Date().toISOString());
                Swal.fire({ icon: 'success', title: 'Status diperbarui!', text: `Status berhasil diubah ke "${newStatus}"`, timer: 1800, showConfirmButton: false });
            } else {
                currentStatus = prevStatus;
                applyStatusStyle(prevStatus);
                Swal.fire('Gagal!', result.message ?? 'Status gagal diperbarui.', 'error');
            }
        } catch (err) {
            console.error(err);
            currentStatus = prevStatus;
            applyStatusStyle(prevStatus);
            Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
        }
    }

    // ── Toggle / Open / Close dropdown status ─────────────────────────────────
    function toggleStatusDropdown() {
        if (isStatusDropOpen) closeStatusDropdown();
        else openStatusDropdown();
    }

    function openStatusDropdown() {
        renderStatusOptions();
        document.getElementById('statusDropdownList').classList.remove('hidden');
        document.getElementById('statusDropdownList').style.display = 'block';
        document.getElementById('statusDropdownChevron').style.transform = 'rotate(180deg)';
        isStatusDropOpen = true;
    }

    function closeStatusDropdown() {
        document.getElementById('statusDropdownList').classList.add('hidden');
        document.getElementById('statusDropdownList').style.display = '';
        document.getElementById('statusDropdownChevron').style.transform = 'rotate(0deg)';
        isStatusDropOpen = false;
    }

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById('statusDropdownWrapper');
        if (wrapper && !wrapper.contains(e.target)) closeStatusDropdown();
    });

    // ── Apply style trigger button sesuai status ─────────────────────────────
    function applyStatusStyle(status) {
        const cfg     = statusConfig[status] || statusConfig['Pengecekan'];
        const trigger = document.getElementById('statusDropdownTrigger');
        const label   = document.getElementById('statusDropdownLabel');
        const chevron = document.getElementById('statusDropdownChevron');

        trigger.style.borderColor     = cfg.border;
        trigger.style.backgroundColor = cfg.bg;
        trigger.style.color           = cfg.text;
        chevron.style.color           = cfg.chevron;
        label.textContent             = status;

        updatePembayaranBtn(status);
    }

    // ── Update tombol proses pembayaran ───────────────────────────────────────
    function updatePembayaranBtn(status) {
        if (status === 'selesai') {
            btnPembayaran.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.add('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = false;
        } else {
            btnPembayaran.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.remove('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = true;
        }
    }

    // ── Ambil ID antrian ──────────────────────────────────────────────────────
    function getAntrianId() {
        const fromSession = sessionStorage.getItem('currentAntrianId');
        if (fromSession) return parseInt(fromSession, 10);
        const segments = window.location.pathname.split('/').filter(Boolean);
        const lastSeg  = segments[segments.length - 1];
        const parsed   = parseInt(lastSeg, 10);
        return isNaN(parsed) ? null : parsed;
    }

    function getAntrianById(id) {
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        return list.find(item => item.id === id) || null;
    }

    function saveAntrianList(list) {
        localStorage.setItem('antrianList', JSON.stringify(list));
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function formatTanggal(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        const tgl = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const jam = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        return `${tgl} pukul ${jam}`;
    }

    // ── Render detail ke halaman ──────────────────────────────────────────────
    function renderDetail(t) {
        const customer = t.vehicle?.customer ?? {};
        const vehicle  = t.vehicle           ?? {};

        document.getElementById('detailName').textContent        = customer.name          || '-';
        document.getElementById('detailPhone').textContent       = customer.phone_number  || '-';
        document.getElementById('detailAddress').textContent     = customer.address       || '-';
        document.getElementById('detailCarModel').textContent    = vehicle.model          || '-';
        document.getElementById('detailKmMasuk').textContent     = t.km_masuk             || '-';
        document.getElementById('detailEngineCode').textContent  = vehicle.engine_code    || '-';
        document.getElementById('detailLicensePlate').textContent= vehicle.license_plate  || '-';

        const createdByName = t.creator?.name || 'Unknown';
        document.getElementById('createdByName').textContent    = createdByName;
        document.getElementById('createdByInitial').textContent = createdByName.charAt(0).toUpperCase();
        document.getElementById('createdAt').textContent        = formatTanggal(t.created_at);
        document.getElementById('updatedAt').textContent        = formatTanggal(t.updated_at);

        const editUrl = "{{ route('antrian-pengerjaan.edit', ':id') }}".replace(':id', t.transaction_id);
        const btnEdit = document.getElementById('btnEditData');
        if (btnEdit) {
            btnEdit.setAttribute('href', editUrl);
            btnEdit.addEventListener('click', (e) => {
                e.preventDefault();
                sessionStorage.setItem('currentAntrianId', t.transaction_id);
                window.location.href = editUrl;
            });
        }

        currentStatus = t.status_service || 'pengecekan';
        currentTransactionId = t.transaction_id;
        applyStatusStyle(currentStatus);

        renderSukuCadang(t.items || []);
    }

    // ── Render suku cadang (dari items API) ───────────────────────────────────
    function renderSukuCadang(items) {
        const container = document.getElementById('sukuCadangContainer');
        const emptyEl   = document.getElementById('sukuCadangEmpty');

        Array.from(container.querySelectorAll('.sc-item')).forEach(el => el.remove());

        if (!items || items.length === 0) {
            emptyEl.style.display = 'block';
            return;
        }
        emptyEl.style.display = 'none';

        items.forEach(sc => {
            // Support both API item format and legacy format
            const nama     = sc.nama      ?? sc.sparepart?.name ?? '-';
            const deskripsi= sc.deskripsi ?? sc.sparepart?.name ?? '-';
            const harga    = sc.harga     ?? (sc.sparepart ? 'Rp ' + Number(sc.sparepart.selling_price).toLocaleString('id-ID') : '-');
            const jumlah   = sc.jumlah    ?? String(sc.quantity ?? 1) + ' pcs';
            const tanggal  = sc.tanggal   ?? '-';
            const supplier = sc.supplier  ?? sc.sparepart?.supplier?.name ?? '-';
            const scId     = sc.id        ?? sc.transaction_item_id;

            const div = document.createElement('div');
            div.className = 'sc-item flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2]';
            div.setAttribute('data-scid', scId);
            div.innerHTML = `
                <div>
                    <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(nama)}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(deskripsi)}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(harga)}</p>
                        <p class="text-[11px] text-gray-400">${escHtml(jumlah)} • ${escHtml(tanggal)}</p>
                        <p class="text-[11px] text-gray-400">Supplier: ${escHtml(supplier)}</p>
                    </div>
                </div>
            `;
            container.appendChild(div);
        });
    }

    // ── Konfirmasi & Hapus suku cadang ────────────────────────────────────────
    function konfirmasiHapusSC(scId) {
        Swal.fire({
            title             : 'Hapus Suku Cadang?',
            text              : 'Apakah kamu yakin ingin menghapus suku cadang ini?',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#FF4D4D',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Hapus!',
        }).then((result) => {
            if (!result.isConfirmed) return;

            const id   = getAntrianId();
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const idx  = list.findIndex(item => item.id === id);

            if (idx !== -1) {
                list[idx].suku_cadang = (list[idx].suku_cadang || []).filter(sc => sc.id !== scId);
                saveAntrianList(list);
                renderSukuCadang(list[idx].suku_cadang);

                Swal.fire({
                    icon : 'success',
                    title: 'Terhapus!',
                    text : 'Suku cadang berhasil dihapus.',
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        });
    }

    // ── Konfirmasi & Edit suku cadang ─────────────────────────────────────────
    function konfirmasiEditSC(scId) {
        Swal.fire({
            title             : 'Edit Suku Cadang?',
            text              : 'Apakah kamu yakin ingin mengedit suku cadang ini?',
            icon              : 'question',
            showCancelButton  : true,
            confirmButtonColor: '#1273EB',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Edit!',
        }).then((result) => {
            if (!result.isConfirmed) return;
            bukaModalEditSC(scId);
        });
    }

    // ── Buka modal edit suku cadang ───────────────────────────────────────────
    function bukaModalEditSC(scId) {
        const id      = getAntrianId();
        const antrian = getAntrianById(id);
        if (!antrian) return;

        const sc = (antrian.suku_cadang || []).find(s => s.id === scId);
        if (!sc) return;

        document.getElementById('editSCId').value       = scId;
        document.getElementById('editSCNama').value     = sc.nama     || '';
        document.getElementById('editSCHarga').value    = sc.harga    || '';
        document.getElementById('editSCJumlah').value   = sc.jumlah   || '';
        document.getElementById('editSCSupplier').value = sc.supplier || '';

        const modal = document.getElementById('modalEditSC');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModalEditSC() {
        const modal = document.getElementById('modalEditSC');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ── Simpan hasil edit suku cadang ke localStorage ─────────────────────────
    function simpanEditSC() {
        const scId    = parseInt(document.getElementById('editSCId').value, 10);
        const newNama = document.getElementById('editSCNama').value.trim();
        const newHarga= document.getElementById('editSCHarga').value.trim();
        const newJml  = document.getElementById('editSCJumlah').value.trim();
        const newSup  = document.getElementById('editSCSupplier').value.trim();

        if (!newNama) {
            Swal.fire('Oops!', 'Nama barang wajib diisi!', 'warning');
            return;
        }

        const id   = getAntrianId();
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        const idx  = list.findIndex(item => item.id === id);

        if (idx !== -1) {
            const scIdx = (list[idx].suku_cadang || []).findIndex(sc => sc.id === scId);
            if (scIdx !== -1) {
                list[idx].suku_cadang[scIdx].nama     = newNama;
                list[idx].suku_cadang[scIdx].harga    = newHarga;
                list[idx].suku_cadang[scIdx].jumlah   = newJml;
                list[idx].suku_cadang[scIdx].supplier = newSup;

                const now = new Date();
                list[idx].updated_at = now.toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric',
                }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                saveAntrianList(list);
                document.getElementById('updatedAt').textContent = list[idx].updated_at;
                renderSukuCadang(list[idx].suku_cadang);
            }
        }

        tutupModalEditSC();

        Swal.fire({
            icon : 'success',
            title: 'Berhasil!',
            text : 'Suku cadang berhasil diperbarui.',
            timer: 1500,
            showConfirmButton: false,
        });
    }

    // ── Hapus antrian via API ──────────────────────────────────────────────────
    function handleHapus() {
        const id = currentTransactionId ?? getAntrianId();
        Swal.fire({
            title             : 'Hapus Data?',
            text              : 'Data antrian ini akan dihapus permanen.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#FF4D4D',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Hapus!',
        }).then(async (result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            try {
                const res = await fetch(`/api/transactions/${id}`, {
                    method : 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                });
                if (res.ok) {
                    await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
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

    // ── Proses pembayaran ─────────────────────────────────────────────────────
    function handleProsesPembayaran() {
        Swal.fire({ icon: 'info', title: 'Proses Pembayaran', text: 'Fitur ini belum tersedia.' });
    }

    // ── Inisialisasi halaman via API ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', async () => {
        const id = getAntrianId();

        if (id === null) {
            Swal.fire('Error', 'ID antrian tidak ditemukan!', 'error').then(() => {
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            });
            return;
        }

        try {
            const res = await fetch(`/api/transactions/${id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await res.json();

            if (!res.ok || result.status !== 'success') {
                Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error').then(() => {
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                });
                return;
            }

            renderDetail(result.data);

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
        }

        const name = localStorage.getItem('user_name') || 'User';
        const role = localStorage.getItem('user_role') || 'Staff';
        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
        document.querySelectorAll('.user-initial-box').forEach(el => {
            if (!el.id || el.id !== 'createdByInitial') {
                el.innerText = name.charAt(0).toUpperCase();
            }
        });
    });
</script>
@endsection