{{-- resources/views/pages/antrian_pengerjaan/editManajemenAntrianPengerjaan.blade.php --}}
@extends('layouts.master')

@section('title', 'Edit Antrian Pengerjaan')
@section('title_header', 'Layanan Servis | Antrian Pengerjaan')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
        </svg>
    </div>
@endsection

@section('form_title', 'Edit Data Mobil Masuk')

@section('form_fields')
<div class="space-y-6">

    {{-- =========================================================
         BOX 1 : Informasi Pemilik Kendaraan
    ========================================================= --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
            <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#1273EB]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h2>
        </div>

        <div class="p-8 space-y-6">
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" required placeholder="Masukkan nama lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" required placeholder="Masukkan nomor telepon"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" id="address" required placeholder="Masukkan alamat lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>
        </div>
    </div>

    {{-- =========================================================
         BOX 2 : Informasi Mobil Pelanggan
    ========================================================= --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
            <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16H6l-2-6h15l-1 4M3 11l1-4h14" />
                </svg>
            </div>
            <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h2>
        </div>

        <div class="p-8 space-y-6">
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Mobil</label>
                <input type="text" id="car_model" placeholder="Masukkan model mobil"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
                <input type="text" id="license_plate" placeholder="Masukkan nomor polisi"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                <input type="text" id="engine_code" placeholder="Masukkan kode mesin"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Km Masuk Mobil</label>
                <input type="text" id="km_masuk" placeholder="Masukkan kilometer"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
            </div>
        </div>
    </div>

    {{-- =========================================================
         BOX 3 : Penggunaan Suku Cadang
    ========================================================= --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
            <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h2 class="text-[16px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h2>
        </div>

        <div class="p-8 space-y-6">

            {{-- List item suku cadang yang sudah ada --}}
            <div id="sukuCadangList" class="space-y-4"></div>

            {{-- Tombol Tambah Suku Cadang --}}
            <button type="button" id="btnTambahSukuCadang"
                class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Suku Cadang
            </button>

            {{-- Form inline tambah suku cadang (hidden by default) --}}
            <div id="formSukuCadang" class="hidden bg-[#F8FAFF] border border-[#D1E4FF] rounded-3xl p-8 space-y-6">

                <h3 class="text-[14px] font-bold text-[#213F5C]">
                    Tambahkan Penggunaan Suku Cadang
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Nama Barang</label>
                        <input type="text" id="inputNamaBarang" placeholder="Contoh: Filter Oli BMW"
                            class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] text-[14px] text-[#213F5C]">
                    </div>

                    {{-- Custom Dropdown Stok --}}
                    <div>
                        <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Stok</label>
                        <div class="relative" id="stokDropdownWrapper">
                            <button type="button" id="stokDropdownTrigger"
                                class="w-full bg-white border border-[#E5E9F2] rounded-xl text-left transition-all focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 outline-none overflow-hidden"
                                onclick="toggleStokDropdown()">
                                <div id="stokDropdownContent" class="flex items-center justify-between min-h-[58px]">
                                    <span id="stokDropdownLabel" class="px-5 py-3.5 text-[13px] text-gray-400">Pilih Stok Yang Ingin Digunakan</span>
                                    <div class="px-4">
                                        <svg id="stokDropdownChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </button>
                            <div id="stokDropdownList"
                                class="hidden absolute z-50 left-0 right-0 mt-2 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                                <div id="stokDropdownItems" class="max-h-[380px] overflow-y-auto custom-scrollbar">
                                    {{-- Diisi oleh JS --}}
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="inputStok" value="">
                        <input type="hidden" id="inputStokLabel" value="">
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Jumlah Stok Yang Digunakan</label>
                        <input type="number" id="inputJumlah" placeholder="Contoh: 1" min="1"
                            class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] text-[14px] text-[#213F5C]">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" id="btnSimpanSukuCadang"
                        class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8] transition-all">
                        Simpan
                    </button>
                    <button type="button" id="btnBatalSukuCadang"
                        class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-xl font-bold text-[14px] hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                </div>
            </div>

            <input type="hidden" id="inputSukuCadangJSON" name="suku_cadang" value="[]">
        </div>
    </div>

</div>
@endsection

@section('content')
    @include('layouts.form_wrapper_antrian', [
        'backUrl'       => route('antrian-pengerjaan.index'),
        'submitBtnText' => 'Simpan Perubahan',
        'sectionTitle'  => 'Edit Informasi Data Servis',
    ])

    <script>
        // ── Ambil ID antrian dari sessionStorage ──────────────────────────────────
        function getAntrianId() {
            const fromSession = sessionStorage.getItem('currentAntrianId');
            if (fromSession) return parseInt(fromSession, 10);
            const segments = window.location.pathname.split('/').filter(Boolean);
            const lastSeg  = segments[segments.length - 1];
            const parsed   = parseInt(lastSeg, 10);
            return isNaN(parsed) ? null : parsed;
        }

        // ── Data dummy stok (TODO Backend: ganti dengan data dari API/Controller) ──
        const dummyStokList = [
            { id: 1, nama: 'Q8 Oils 5W40 Excel',  stok: 2, harga: 'Rp 700.000', jumlah_satuan: '1 pcs', tanggal: '01 Jan 2025', supplier: 'Milan Motors',  cabang: 'Pelajar Pejuang' },
            { id: 2, nama: 'Filter Oli BMW E46',   stok: 5, harga: 'Rp 150.000', jumlah_satuan: '1 pcs', tanggal: '15 Des 2024', supplier: 'AutoParts Indo', cabang: 'Pelajar Pejuang' },
            { id: 3, nama: 'Busi NGK Iridium',     stok: 8, harga: 'Rp 120.000', jumlah_satuan: '1 pcs', tanggal: '20 Des 2024', supplier: 'NGK Official',   cabang: 'Pelajar Pejuang' },
            { id: 4, nama: 'Besi ingridium',       stok: 8, harga: 'Rp 300.000', jumlah_satuan: '1 pcs', tanggal: '26 Des 2024', supplier: 'NGK Official',   cabang: 'Pelajar Pejuang' },
            { id: 5, nama: 'Oli Shell Helix',      stok: 4, harga: 'Rp 120.000', jumlah_satuan: '1 pcs', tanggal: '20 Jun 2024', supplier: 'Milan Motors',   cabang: 'Pelajar Pejuang' },
        ];

        // ── State ─────────────────────────────────────────────────────────────────
        let selectedStokId     = null;
        let selectedStokData   = null;
        let isStokDropdownOpen = false;
        let sukuCadangItems    = [];
        let isDirty            = false;

        // ── Helper escHtml ────────────────────────────────────────────────────────
        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        // ── Isi form dengan data yang sudah ada ───────────────────────────────────
        function populateForm(antrian) {
            document.getElementById('name').value          = antrian.name          || '';
            document.getElementById('phone').value         = antrian.phone         || '';
            document.getElementById('address').value       = antrian.address       || '';
            document.getElementById('car_model').value     = antrian.car_model     || '';
            document.getElementById('license_plate').value = antrian.license_plate || '';
            document.getElementById('engine_code').value   = antrian.engine_code   || '';
            document.getElementById('km_masuk').value      = antrian.km_masuk      || '';

            // Isi suku cadang
            sukuCadangItems = antrian.suku_cadang ? JSON.parse(JSON.stringify(antrian.suku_cadang)) : [];
            syncHiddenJSON();
            renderSukuCadang();
        }

        // ── Render suku cadang list ───────────────────────────────────────────────
        function renderSukuCadang() {
            const listEl = document.getElementById('sukuCadangList');
            listEl.innerHTML = '';

            if (sukuCadangItems.length === 0) return;

            sukuCadangItems.forEach(item => {
                const el = document.createElement('div');
                el.className = 'flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[12px] border border-[#E5E9F2]';
                el.innerHTML = `
                    <div>
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(item.nama)}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(item.deskripsi || '-')}
                            ${item.harga && item.harga !== '-' ? `<span class="ml-1 text-[#1273EB] font-semibold">• ${escHtml(item.harga)}</span>` : ''}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] font-bold text-[#213F5C]">${escHtml(item.jumlah)}</span>
                        <button type="button" onclick="hapusSukuCadang(${item.id})"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                listEl.appendChild(el);
            });
        }

        function hapusSukuCadang(id) {
            sukuCadangItems = sukuCadangItems.filter(i => i.id !== id);
            renderSukuCadang();
            syncHiddenJSON();
            isDirty = true;
        }

        function syncHiddenJSON() {
            document.getElementById('inputSukuCadangJSON').value = JSON.stringify(sukuCadangItems);
        }

        // ── Dropdown Stok ─────────────────────────────────────────────────────────
        function renderStokOptions(keyword = '') {
            const container    = document.getElementById('stokDropdownItems');
            container.innerHTML = '';

            const filteredList = dummyStokList.filter(s =>
                s.nama.toLowerCase().includes(keyword.toLowerCase())
            );

            if (filteredList.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-400 text-[14px]">Stok tidak ditemukan...</div>';
                return;
            }

            filteredList.forEach(stok => {
                const div = document.createElement('div');
                div.className = 'stok-option-item' + (selectedStokId === stok.id ? ' selected' : '');
                div.innerHTML = `
                    <div class="stok-option-left">
                        <span class="stok-option-left-label">Stok</span>
                        <span class="stok-option-left-value">${escHtml(String(stok.stok))}</span>
                    </div>
                    <div class="stok-option-right" style="padding: 14px 16px;">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-[15px] font-extrabold text-[#213F5C]">${escHtml(stok.nama)}</span>
                            <span class="text-[15px] font-extrabold text-[#1273EB]">— ${escHtml(stok.harga)}</span>
                            <span class="text-[13px] font-bold text-gray-400">(${escHtml(stok.jumlah_satuan)})</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[12px]">
                            <span class="text-[#64748B] font-bold">${escHtml(stok.tanggal)}</span>
                            <span class="text-gray-300">|</span>
                            <span class="text-gray-500">Supplier: <b class="text-[#4B5563]">${escHtml(stok.supplier)}</b></span>
                            <span class="text-gray-300">|</span>
                            <span class="text-gray-500">Cabang: <b class="text-[#4B5563]">${escHtml(stok.cabang)}</b></span>
                        </div>
                    </div>
                `;
                div.addEventListener('click', () => selectStok(stok));
                container.appendChild(div);
            });
        }

        function selectStok(stok) {
            selectedStokId   = stok.id;
            selectedStokData = stok;
            document.getElementById('inputStok').value       = stok.id;
            document.getElementById('inputStokLabel').value  = stok.nama;

            const namaBarang = document.getElementById('inputNamaBarang');
            if (namaBarang) namaBarang.value = stok.nama;

            const contentContainer = document.getElementById('stokDropdownContent');
            if (contentContainer) {
                contentContainer.innerHTML = `
                    <div class="flex items-stretch w-full">
                        <div class="stok-option-left" style="border-bottom:none;">
                            <span class="stok-option-left-label">Stok</span>
                            <span class="stok-option-left-value">${escHtml(String(stok.stok))}</span>
                        </div>
                        <div class="stok-option-right" style="padding: 12px 16px;">
                            <div class="text-[14px] font-extrabold text-[#213F5C] mb-1">
                                ${escHtml(stok.nama)} <span class="text-[#1273EB] ml-1">${escHtml(stok.harga)}</span>
                            </div>
                            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                                ${escHtml(stok.tanggal)} • ${escHtml(stok.supplier)} • ${escHtml(stok.cabang)}
                            </div>
                        </div>
                        <div class="flex items-center px-4">
                            <svg id="stokDropdownChevron" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                `;
            }
            closeStokDropdown();
        }

        function toggleStokDropdown() {
            isStokDropdownOpen ? closeStokDropdown() : openStokDropdown();
        }

        function openStokDropdown() {
            if (!isStokDropdownOpen) renderStokOptions();
            document.getElementById('stokDropdownList').classList.remove('hidden');
            const chevron = document.getElementById('stokDropdownChevron');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            isStokDropdownOpen = true;
        }

        function closeStokDropdown() {
            document.getElementById('stokDropdownList').classList.add('hidden');
            const chevron = document.getElementById('stokDropdownChevron');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            isStokDropdownOpen = false;
        }

        function resetStokDropdown() {
            selectedStokId   = null;
            selectedStokData = null;
            document.getElementById('inputStok').value      = '';
            document.getElementById('inputStokLabel').value = '';
            const contentContainer = document.getElementById('stokDropdownContent');
            if (contentContainer) {
                contentContainer.innerHTML = `
                    <span id="stokDropdownLabel" class="px-5 py-3.5 text-[13px] text-gray-400">Pilih Stok Yang Ingin Digunakan</span>
                    <div class="px-4">
                        <svg id="stokDropdownChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                `;
            }
            closeStokDropdown();
        }

        // Tutup dropdown klik di luar
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('stokDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) closeStokDropdown();
        });

        // ── Suku Cadang Form Listeners ────────────────────────────────────────────
        const btnTambah   = document.getElementById('btnTambahSukuCadang');
        const formSC      = document.getElementById('formSukuCadang');
        const btnSimpanSC = document.getElementById('btnSimpanSukuCadang');
        const btnBatalSC  = document.getElementById('btnBatalSukuCadang');

        btnTambah.addEventListener('click', () => {
            formSC.classList.remove('hidden');
            btnTambah.classList.add('hidden');
            document.getElementById('inputNamaBarang').value = '';
            document.getElementById('inputJumlah').value     = '';
            resetStokDropdown();
            requestAnimationFrame(() => {
                document.getElementById('inputNamaBarang')?.focus({ preventScroll: true });
            });
        });

        btnBatalSC.addEventListener('click', () => {
            formSC.classList.add('hidden');
            btnTambah.classList.remove('hidden');
            resetStokDropdown();
        });

        btnSimpanSC.addEventListener('click', () => {
            const nama   = document.getElementById('inputNamaBarang').value.trim();
            const jumlah = document.getElementById('inputJumlah').value.trim();

            if (!nama || !jumlah) {
                Swal.fire('Oops!', 'Nama barang dan jumlah wajib diisi!', 'warning');
                return;
            }

            const now       = new Date();
            const stokLabel = selectedStokData ? selectedStokData.nama     : '-';
            const harga     = selectedStokData ? selectedStokData.harga    : '-';
            const supplier  = selectedStokData ? selectedStokData.supplier : '-';
            const cabang    = selectedStokData ? selectedStokData.cabang   : '-';
            const stokJml   = selectedStokData ? selectedStokData.stok     : '-';
            const tanggal   = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

            sukuCadangItems.push({
                id        : Date.now(),
                nama,
                deskripsi : stokLabel,
                harga,
                jumlah    : jumlah + ' pcs',
                tanggal,
                supplier,
                cabang,
                stok      : stokJml,
            });

            renderSukuCadang();
            syncHiddenJSON();
            formSC.classList.add('hidden');
            btnTambah.classList.remove('hidden');
            resetStokDropdown();
            isDirty = true;
        });

        // Filter stok saat mengetik nama barang
        document.getElementById('inputNamaBarang').addEventListener('input', function (e) {
            const keyword = e.target.value.trim();
            if (keyword.length > 0) {
                renderStokOptions(keyword);
                document.getElementById('stokDropdownList').classList.remove('hidden');
                const chevron = document.getElementById('stokDropdownChevron');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                isStokDropdownOpen = true;
            } else {
                closeStokDropdown();
            }
        });

        // ── Dirty flag ────────────────────────────────────────────────────────────
        document.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', () => isDirty = true);
        });

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) { e.preventDefault(); e.returnValue = ''; }
        });

        // ── Submit — update data di localStorage ──────────────────────────────────
        document.getElementById('submitBtnApi').addEventListener('click', async (e) => {
            e.preventDefault();

            const nameVal    = document.getElementById('name').value.trim();
            const phoneVal   = document.getElementById('phone').value.trim();
            const addressVal = document.getElementById('address').value.trim();

            if (!nameVal || !phoneVal || !addressVal) {
                Swal.fire('Oops!', 'Nama, nomor telepon, dan alamat wajib diisi!', 'warning');
                return;
            }

            Swal.fire({ title: 'Menyimpan perubahan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const id   = getAntrianId();
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const idx  = list.findIndex(item => item.id === id);

            if (idx === -1) {
                Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error');
                return;
            }

            const now = new Date();
            const formattedDate = now.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
            }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            // Update field — pertahankan field yang tidak diedit (id, status, created_by, created_at)
            list[idx].name          = nameVal;
            list[idx].phone         = phoneVal;
            list[idx].address       = addressVal;
            list[idx].car_model     = document.getElementById('car_model').value.trim()     || '-';
            list[idx].license_plate = document.getElementById('license_plate').value.trim() || '-';
            list[idx].engine_code   = document.getElementById('engine_code').value.trim()   || '-';
            list[idx].km_masuk      = document.getElementById('km_masuk').value.trim()      || '-';
            list[idx].suku_cadang   = sukuCadangItems;
            list[idx].updated_at    = formattedDate;

            localStorage.setItem('antrianList', JSON.stringify(list));

            isDirty = false;

            await Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil diperbarui.', timer: 2000, showConfirmButton: false });

            // Kembali ke halaman detail setelah simpan
            const detailUrl = "{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', id);
            sessionStorage.setItem('currentAntrianId', id);
            window.location.href = detailUrl;
        });

        // ── Enter key — jangan trigger submit saat mengetik di input ──────────────
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const tag      = document.activeElement?.tagName?.toLowerCase();
                const isTyping = ['input', 'textarea', 'select'].includes(tag);
                if (isTyping) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                document.getElementById('submitBtnApi')?.click();
            }
        });

        // ── Inisialisasi: load data yang mau diedit ───────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const id = getAntrianId();

            if (id === null) {
                Swal.fire('Error', 'ID antrian tidak ditemukan!', 'error').then(() => {
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                });
                return;
            }

            const list    = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const antrian = list.find(item => item.id === id);

            if (!antrian) {
                Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error').then(() => {
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                });
                return;
            }

            populateForm(antrian);

            // Update backUrl ke halaman detail agar tombol Kembali tepat sasaran
            const backBtn = document.getElementById('btnBackForm');

            if (backBtn) {
                const detailUrl = "{{ route('antrian-pengerjaan.show', ':id') }}".replace(':id', id);
                backBtn.setAttribute('href', detailUrl);

                backBtn.addEventListener('click', (ev) => {
                    ev.preventDefault();
                    if (isDirty) {
                        Swal.fire({
                            title: 'Keluar tanpa menyimpan?',
                            text: 'Perubahan yang kamu buat belum disimpan.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Keluar',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#1273EB',
                        }).then(result => {
                            if (result.isConfirmed) {
                                isDirty = false;
                                window.location.href = detailUrl;
                            }
                        });
                    } else {
                        window.location.href = detailUrl;
                    }
                });
            }
        });
    </script>
@endsection