{{-- resources/views/pages/antrian_pengerjaan/tambahManajemenAntrianPengerjaan.blade.php --}}
@extends('layouts.master')

@section('title', 'Tambah Antrian Pengerjaan')
@section('title_header', 'Layanan Servis | Antrian Pengerjaan')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Mobil Masuk')

@section('form_fields')
<div class="main-form-content">
    {{-- =========================================================
         SECTION 1 : Informasi Pemilik Kendaraan
    ========================================================= --}}
    <div class="space-y-5">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h3>
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" required placeholder="Masukkan nama lengkap"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Nomor Telepon <span class="text-red-500">*</span>
            </label>
            <input type="text" id="phone" required placeholder="Masukkan nomor telepon"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Alamat <span class="text-red-500">*</span>
            </label>
            <input type="text" id="address" required placeholder="Masukkan alamat lengkap"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
    </div>

    {{-- =========================================================
         SECTION 2 : Informasi Mobil Pelanggan
    ========================================================= --}}
    <div class="space-y-5 pt-2">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16H6l-2-6h15l-1 4M3 11l1-4h14" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h3>
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Mobil</label>
            <input type="text" id="car_model" placeholder="Masukkan model mobil"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
            <input type="text" id="license_plate" placeholder="Masukkan nomor polisi"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
            <input type="text" id="engine_code" placeholder="Masukkan kode mesin"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Km Masuk Mobil</label>
            <input type="text" id="km_masuk" placeholder="Masukkan kilometer"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
    </div>

    {{-- =========================================================
         SECTION 3 : Penggunaan Suku Cadang
    ========================================================= --}}
    <div class="space-y-4 pt-2">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h3>
        </div>

        {{-- List item suku cadang yang sudah ditambahkan --}}
        <div id="sukuCadangList" class="space-y-3"></div>

        {{-- Form inline tambah suku cadang (hidden by default) --}}
        <div id="formSukuCadang" class="hidden border border-[#E5E9F2] rounded-[14px] p-5 bg-[#F9FBFF] space-y-4">
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Barang</label>
                <input type="text" id="inputNamaBarang" placeholder="Contoh: Filter Oli BMW"
                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>

            {{-- ── Custom Dropdown Stok (sesuai desain Frame_64) ── --}}
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Stok</label>
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
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Jumlah Stok Yang Digunakan</label>
                <input type="number" id="inputJumlah" placeholder="Contoh: 1" min="1"
                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" id="btnSimpanSukuCadang"
                    class="flex-1 py-3 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] hover:bg-[#0E59B8] transition-all">
                    Simpan
                </button>
                <button type="button" id="btnBatalSukuCadang"
                    class="px-6 py-3 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all">
                    Batal
                </button>
            </div>
        </div>

        {{-- Tombol Tambah Suku Cadang --}}
        <button type="button" id="btnTambahSukuCadang"
            class="w-full flex items-center justify-center gap-2 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Suku Cadang
        </button>

        <input type="hidden" id="inputSukuCadangJSON" name="suku_cadang" value="[]">
    </div>
</div>
@endsection

@section('content')
    @include('layouts.form_wrapper_antrian', [
        'backUrl'       => route('antrian-pengerjaan.index'),
        'submitBtnText' => 'Simpan Data',
        'sectionTitle'  => 'Informasi Data Servis',
    ])

    <script>
        let isDirty = false;

        // ── Data dummy stok (TODO Backend: ganti dengan data dari API/Controller) ──
        const dummyStokList = [
            {
                id            : 1,
                nama          : 'Q8 Oils 5W40 Excel',
                stok          : 2,
                harga         : 'Rp 700.000',
                jumlah_satuan : '1 pcs',
                tanggal       : '01 Jan 2025',
                supplier      : 'Milan Motors',
                cabang        : 'Pelajar Pejuang',
            },
            {
                id            : 2,
                nama          : 'Filter Oli BMW E46',
                stok          : 5,
                harga         : 'Rp 150.000',
                jumlah_satuan : '1 pcs',
                tanggal       : '15 Des 2024',
                supplier      : 'AutoParts Indo',
                cabang        : 'Pelajar Pejuang',
            },
            {
                id            : 3,
                nama          : 'Busi NGK Iridium',
                stok          : 8,
                harga         : 'Rp 120.000',
                jumlah_satuan : '1 pcs',
                tanggal       : '20 Des 2024',
                supplier      : 'NGK Official',
                cabang        : 'Pelajar Pejuang',
            },
            {
                id            : 4,
                nama          : 'Besi ingridium',
                stok          : 8,
                harga         : 'Rp 300.000',
                jumlah_satuan : '1 pcs',
                tanggal       : '26 Des 2024',
                supplier      : 'NGK Official',
                cabang        : 'Pelajar Pejuang',
            },
            {
                id            : 5,
                nama          : 'Oli Shell Helix',
                stok          : 4,
                harga         : 'Rp 120.000',
                jumlah_satuan : '1 pcs',
                tanggal       : '20 Jun 2024',
                supplier      : 'Milan Motors',
                cabang        : 'Pelajar Pejuang',
            },
        ];

        // ── State dropdown stok ───────────────────────────────────────────────────
        let selectedStokId   = null;
        let selectedStokData = null;
        let isStokDropdownOpen = false;

        // ── Render opsi stok ke dalam dropdown ───────────────────────────────────
        function renderStokOptions(keyword = '') {
            const container = document.getElementById('stokDropdownItems');
            container.innerHTML = '';

            const filteredList = dummyStokList.filter(stok =>
                stok.nama.toLowerCase().includes(keyword.toLowerCase())
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

        // ── Pilih stok ────────────────────────────────────────────────────────────
        function selectStok(stok) {
            selectedStokId   = stok.id;
            selectedStokData = stok;

            document.getElementById('inputStok').value      = stok.id;
            document.getElementById('inputStokLabel').value = stok.nama;

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

        // ── Toggle / open / close dropdown ───────────────────────────────────────
        function toggleStokDropdown() {
            if (isStokDropdownOpen) {
                closeStokDropdown();
            } else {
                openStokDropdown();
            }
        }

        function openStokDropdown() {
            if (!isStokDropdownOpen) {
                renderStokOptions();
            }
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

        // Tutup dropdown ketika klik di luar
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('stokDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeStokDropdown();
            }
        });

        // ── Reset tampilan dropdown ke kondisi awal ───────────────────────────────
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

        // ── Suku Cadang State ─────────────────────────────────────────────────────
        let sukuCadangItems = [];

        const btnTambah   = document.getElementById('btnTambahSukuCadang');
        const formSC      = document.getElementById('formSukuCadang');
        const btnSimpanSC = document.getElementById('btnSimpanSukuCadang');
        const btnBatalSC  = document.getElementById('btnBatalSukuCadang');
        const listEl      = document.getElementById('sukuCadangList');
        const hiddenJSON  = document.getElementById('inputSukuCadangJSON');

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
        });

        function hapusSukuCadang(id) {
            sukuCadangItems = sukuCadangItems.filter(i => i.id !== id);
            renderSukuCadang();
            syncHiddenJSON();
        }

        function syncHiddenJSON() {
            hiddenJSON.value = JSON.stringify(sukuCadangItems);
        }

        function renderSukuCadang() {
            listEl.innerHTML = '';
            sukuCadangItems.forEach(item => {
                const el = document.createElement('div');
                el.className = 'flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[12px] border border-[#E5E9F2]';
                el.innerHTML = `
                    <div>
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(item.nama)}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(item.deskripsi || '-')}</p>
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

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        // ── Filter dropdown saat mengetik di inputNamaBarang ─────────────────────
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

        // ── Submit — simpan ke localStorage ───────────────────────────────────────
        document.getElementById('submitBtnApi').addEventListener('click', async (e) => {
            e.preventDefault();

            const nameVal    = document.getElementById('name').value.trim();
            const phoneVal   = document.getElementById('phone').value.trim();
            const addressVal = document.getElementById('address').value.trim();

            if (!nameVal || !phoneVal || !addressVal) {
                Swal.fire('Oops!', 'Nama, nomor telepon, dan alamat wajib diisi!', 'warning');
                return;
            }

            Swal.fire({ title: 'Menyimpan data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const list  = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const newId = list.length > 0 ? Math.max(...list.map(i => i.id)) + 1 : 1;

            const now = new Date();
            const formattedDate = now.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
            }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            const userName = localStorage.getItem('user_name') || 'Admin';

            const newItem = {
                id            : newId,
                name          : nameVal,
                phone         : phoneVal,
                address       : addressVal,
                car_model     : document.getElementById('car_model').value.trim()     || '-',
                license_plate : document.getElementById('license_plate').value.trim() || '-',
                engine_code   : document.getElementById('engine_code').value.trim()   || '-',
                km_masuk      : document.getElementById('km_masuk').value.trim()      || '-',
                status        : 'Pengecekan',
                created_by    : userName,
                created_at    : formattedDate,
                updated_at    : formattedDate,
                suku_cadang   : sukuCadangItems,
            };

            list.push(newItem);
            localStorage.setItem('antrianList', JSON.stringify(list));

            isDirty = false;

            await Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 2000, showConfirmButton: false });
            window.location.href = "{{ route('antrian-pengerjaan.index') }}";
        });

        // ── Enter key — FIX: jangan trigger submit saat user sedang mengetik di input ──
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
    </script>
@endsection