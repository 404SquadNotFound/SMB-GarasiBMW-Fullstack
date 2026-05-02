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
        'Pengecekan'   : { border: '#FDE68A', bg: '#FFF8EC', text: '#F59E0B', chevron: '#F59E0B', optClass: 'status-option-pengecekan' },
        'Dalam Proses' : { border: '#B1D3FF', bg: '#EAF2FF', text: '#1273EB', chevron: '#1273EB', optClass: 'status-option-dalamproses' },
        'Selesai'      : { border: '#A7F3D0', bg: '#EDFBF3', text: '#16A34A', chevron: '#16A34A', optClass: 'status-option-selesai' },
    };

    const statusList        = ['Pengecekan', 'Dalam Proses', 'Selesai'];
    const btnPembayaran     = document.getElementById('btnProsesPembayaran');
    let   currentStatus     = 'Pengecekan';
    let   isStatusDropOpen  = false;

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
    function selectStatus(newStatus) {
        if (newStatus === currentStatus) {
            closeStatusDropdown();
            return;
        }

        const prevStatus = currentStatus;
        currentStatus    = newStatus;

        applyStatusStyle(newStatus);
        closeStatusDropdown();

        // Simpan ke localStorage
        const id   = getAntrianId();
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        const idx  = list.findIndex(item => item.id === id);

        if (idx !== -1) {
            list[idx].status = newStatus;
            const now = new Date();
            list[idx].updated_at = now.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
            }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            localStorage.setItem('antrianList', JSON.stringify(list));
            document.getElementById('updatedAt').textContent = list[idx].updated_at;
        }

        Swal.fire({
            icon : 'success',
            title: 'Status diperbarui!',
            text : `Status berhasil diubah ke "${newStatus}"`,
            timer: 1800,
            showConfirmButton: false,
        });
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
        if (status === 'Selesai') {
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

    // ── Render detail ke halaman ──────────────────────────────────────────────
    function renderDetail(antrian) {
        document.getElementById('detailName').textContent     = antrian.name          || '-';
        document.getElementById('detailPhone').textContent    = antrian.phone         || '-';
        document.getElementById('detailAddress').textContent  = antrian.address       || '-';
        document.getElementById('detailCarModel').textContent    = antrian.car_model     || '-';
        document.getElementById('detailKmMasuk').textContent     = antrian.km_masuk      || '-';
        document.getElementById('detailEngineCode').textContent  = antrian.engine_code   || '-';
        document.getElementById('detailLicensePlate').textContent = antrian.license_plate || '-';

        const createdByName = antrian.created_by || 'Unknown';
        document.getElementById('createdByName').textContent    = createdByName;
        document.getElementById('createdByInitial').textContent = createdByName.charAt(0).toUpperCase();
        document.getElementById('createdAt').textContent        = antrian.created_at || '-';
        document.getElementById('updatedAt').textContent        = antrian.updated_at || '-';

        const editUrl = "{{ route('antrian-pengerjaan.edit', ':id') }}".replace(':id', antrian.id);
        document.getElementById('btnEditData').setAttribute('href', editUrl);
        document.getElementById('btnEditData').addEventListener('click', (e) => {
            e.preventDefault();
            sessionStorage.setItem('currentAntrianId', antrian.id);
            window.location.href = editUrl;
        });

        currentStatus = antrian.status || 'Pengecekan';
        applyStatusStyle(currentStatus);

        renderSukuCadang(antrian.suku_cadang || []);
    }

    // ── Render suku cadang ────────────────────────────────────────────────────
    function renderSukuCadang(list) {
        const container = document.getElementById('sukuCadangContainer');
        const emptyEl   = document.getElementById('sukuCadangEmpty');

        Array.from(container.querySelectorAll('.sc-item')).forEach(el => el.remove());

        if (!list || list.length === 0) {
            emptyEl.style.display = 'block';
            return;
        }
        emptyEl.style.display = 'none';

        list.forEach(sc => {
            const div = document.createElement('div');
            div.className = 'sc-item flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2]';
            div.setAttribute('data-scid', sc.id);
            div.innerHTML = `
                <div>
                    <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(sc.nama)}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(sc.deskripsi || '-')}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(sc.harga || '-')}</p>
                        <p class="text-[11px] text-gray-400">${escHtml(sc.jumlah || '-')} • ${escHtml(sc.tanggal || '-')}</p>
                        <p class="text-[11px] text-gray-400">Supplier: ${escHtml(sc.supplier || '-')}</p>
                    </div>
                    <button type="button"
                        onclick="konfirmasiEditSC(${sc.id})"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#EAF2FF] border border-[#B1D3FF] text-[#1273EB] hover:bg-[#D4E8FF] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </button>
                    <button type="button"
                        onclick="konfirmasiHapusSC(${sc.id})"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
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

    // ── Hapus antrian dari localStorage ──────────────────────────────────────
    function handleHapus() {
        const id = getAntrianId();
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
            if (id !== null) {
                let list = JSON.parse(localStorage.getItem('antrianList') || '[]');
                list = list.filter(item => item.id !== id);
                saveAntrianList(list);
            }
            await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
            window.location.href = "{{ route('antrian-pengerjaan.index') }}";
        });
    }

    // ── Proses pembayaran ─────────────────────────────────────────────────────
    function handleProsesPembayaran() {
        Swal.fire({ icon: 'info', title: 'Proses Pembayaran', text: 'Fitur ini belum tersedia.' });
    }

    // ── Inisialisasi halaman ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const id = getAntrianId();

        if (id === null) {
            Swal.fire('Error', 'ID antrian tidak ditemukan!', 'error').then(() => {
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            });
            return;
        }

        const antrian = getAntrianById(id);
        if (!antrian) {
            Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error').then(() => {
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            });
            return;
        }

        renderDetail(antrian);

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