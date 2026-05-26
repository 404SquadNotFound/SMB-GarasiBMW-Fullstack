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
    <div class="space-y-6">

        {{-- =========================================================
         BOX 1 : Informasi Pemilik Kendaraan
        ========================================================= --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#1273EB]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h2>
            </div>

            <div class="p-8 space-y-5">
                {{-- Searchable Customer Dropdown --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Cari Pelanggan <span class="text-red-500">*</span></label>
                    <div class="relative" id="customerDropdownWrapper">
                        <div class="relative">
                            <input type="text" id="customerSearch" placeholder="Ketik nama atau nomor telepon pelanggan..."
                                autocomplete="off"
                                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300 pr-10">
                            <span id="customerSearchClear" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-400 text-lg leading-none" onclick="clearCustomer()">×</span>
                        </div>
                        <div id="customerDropdownList" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                            <div id="customerDropdownItems" class="max-h-[260px] overflow-y-auto"></div>
                        </div>
                    </div>
                    <input type="hidden" id="selectedCustomerId">
                </div>

                {{-- Info pelanggan terpilih (readonly) --}}
                <div id="customerInfoBox" class="hidden bg-[#F0F7FF] border border-[#B1D3FF] rounded-xl p-4 space-y-1">
                    <p class="text-[13px] font-bold text-[#1273EB]" id="customerInfoName"></p>
                    <p class="text-[12px] text-gray-500" id="customerInfoPhone"></p>
                    <p class="text-[12px] text-gray-400" id="customerInfoAddress"></p>
                </div>
            </div>
        </div>

        {{-- =========================================================
         BOX 2 : Informasi Mobil Pelanggan
        ========================================================= --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm">
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

            <div class="p-8 space-y-5">
                {{-- Pilih Kendaraan --}}
                <div id="vehicleSectionWrapper">
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pilih Kendaraan <span class="text-red-500">*</span></label>
                    <div id="vehicleEmptyHint" class="py-3 px-4 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-[13px] text-gray-400 text-center">
                        Pilih pelanggan terlebih dahulu
                    </div>
                    <div class="relative" id="vehicleDropdownWrapper" class="hidden">
                        <div class="relative">
                            <input type="text" id="vehicleSearch" placeholder="Cari model atau nopol kendaraan..."
                                autocomplete="off"
                                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300 pr-10">
                            <span id="vehicleSearchClear" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-400 text-lg leading-none" onclick="clearVehicle()">×</span>
                        </div>
                        <div id="vehicleDropdownList" class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                            <div id="vehicleDropdownItems" class="max-h-[220px] overflow-y-auto"></div>
                        </div>
                    </div>
                    <input type="hidden" id="selectedVehicleId">
                </div>

                {{-- Info kendaraan terpilih (readonly) --}}
                <div id="vehicleInfoBox" class="hidden bg-[#F0F7FF] border border-[#B1D3FF] rounded-xl p-4 space-y-1">
                    <p class="text-[13px] font-bold text-[#1273EB]" id="vehicleInfoModel"></p>
                    <p class="text-[12px] text-gray-500" id="vehicleInfoPlate"></p>
                    <p class="text-[12px] text-gray-400" id="vehicleInfoEngine"></p>
                </div>

                {{-- KM Masuk --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">KM Masuk Mobil</label>
                    <input type="text" id="km_masuk" placeholder="Masukkan kilometer saat masuk"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
                </div>
            </div>
        </div>

        {{-- =========================================================
         BOX 3 : Penggunaan Suku Cadang (semua user)
        ========================================================= --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm">
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

                {{-- List item suku cadang --}}
                <div id="sukuCadangList" class="space-y-4"></div>

                {{-- Tombol tambah --}}
                <button type="button" id="btnTambahSukuCadang"
                    class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Tambah Suku Cadang
                </button>

                {{-- FORM TAMBAH SUKU CADANG --}}
                <div id="formSukuCadang" class="hidden bg-[#F8FAFF] border border-[#D1E4FF] rounded-3xl p-8 space-y-6">

                    <h3 class="text-[14px] font-bold text-[#213F5C]">
                        Tambahkan Penggunaan Suku Cadang
                    </h3>

                    <div class="space-y-5">
                        {{-- Dropdown stok / Suku Cadang --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Cari Suku Cadang</label>
                            <div class="relative" id="stokDropdownWrapper">
                                <div class="relative">
                                    <input type="text" id="stokSearch" placeholder="Pilih suku cadang yang ingin digunakan..."
                                        autocomplete="off"
                                        class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300 pr-10">
                                    <span id="stokSearchClear" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-400 text-lg leading-none" onclick="clearStok()">×</span>
                                </div>
                                <div id="stokDropdownList" class="hidden absolute z-50 left-0 right-0 mt-2 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                                    <div id="stokDropdownItems" class="max-h-[380px] overflow-y-auto custom-scrollbar"></div>
                                </div>
                            </div>

                            <input type="hidden" id="inputStok" value="">
                            <input type="hidden" id="inputStokLabel" value="">
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">
                                Jumlah Stok Yang Digunakan
                            </label>
                            <input type="number" id="inputJumlah" placeholder="Contoh : 1" min="1"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" id="btnSimpanSukuCadang"
                            class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8]">
                            Simpan
                        </button>
                        <button type="button" id="btnBatalSukuCadang"
                            class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-xl font-bold text-[14px] hover:bg-gray-50">
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
        'backUrl' => route('antrian-pengerjaan.index'),
        'submitBtnText' => 'Simpan Data',
        'sectionTitle' => 'Informasi Data Servis',
    ])

    <script>
        let isDirty = false;
        const token = localStorage.getItem('access_token');

        // ── State utama ───────────────────────────────────────────────────────────
        let selectedCustomer = null;
        let selectedVehicle  = null;
        let stokList         = [];
        let sukuCadangItems  = [];
        let selectedStokId   = null;
        let selectedStokData = null;
        let customerDebounce = null;
        let currentVehicles  = [];

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        // ═══════════════════════════════════════════════════════════════
        // CUSTOMER SEARCH
        // ═══════════════════════════════════════════════════════════════
        async function searchCustomers(keyword) {
            try {
                const params = keyword ? `?search=${encodeURIComponent(keyword)}` : '';
                const res = await fetch(`/api/customers-for-antrian${params}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                return res.ok ? (result.data ?? []) : [];
            } catch { return []; }
        }

        let customersData = [];

        function renderCustomerDropdown(list) {
            customersData = list;
            const container = document.getElementById('customerDropdownItems');
            const dropdown  = document.getElementById('customerDropdownList');
            container.innerHTML = '';

            if (list.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-[13px] text-gray-400">Pelanggan tidak ditemukan</div>';
            } else {
                list.forEach(c => {
                    const div = document.createElement('div');
                    div.className = 'px-5 py-3.5 hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors';
                    div.innerHTML = `
                        <p class="text-[14px] font-bold text-[#213F5C]">${escHtml(c.nama)}</p>
                        <p class="text-[12px] text-gray-400">${escHtml(c.telepon)} ${c.vehicles?.length ? '• ' + c.vehicles.length + ' kendaraan' : ''}</p>`;
                    div.addEventListener('click', () => selectCustomer(c));
                    container.appendChild(div);
                });
            }
            dropdown.classList.remove('hidden');
        }

        function selectCustomer(c) {
            selectedCustomer = c;
            document.getElementById('selectedCustomerId').value = c.id;
            document.getElementById('customerSearch').value    = `${c.nama} - ${c.telepon}`;
            document.getElementById('customerSearchClear').classList.remove('hidden');
            document.getElementById('customerDropdownList').classList.add('hidden');

            document.getElementById('customerInfoName').textContent    = c.nama;
            document.getElementById('customerInfoPhone').textContent   = '📞 ' + c.telepon;
            document.getElementById('customerInfoAddress').textContent = '📍 ' + (c.alamat || '-');
            document.getElementById('customerInfoBox').classList.remove('hidden');

            populateVehicleDropdown(c.vehicles ?? []);
            isDirty = true;
        }

        function clearCustomer() {
            selectedCustomer = null;
            document.getElementById('selectedCustomerId').value = '';
            document.getElementById('customerSearch').value     = '';
            document.getElementById('customerSearchClear').classList.add('hidden');
            document.getElementById('customerInfoBox').classList.add('hidden');
            document.getElementById('customerDropdownList').classList.add('hidden');

            clearVehicle();
            document.getElementById('vehicleEmptyHint').classList.remove('hidden');
            document.getElementById('vehicleDropdownWrapper').classList.add('hidden');
        }

        const customerSearchEl = document.getElementById('customerSearch');
        if (customerSearchEl) {
            customerSearchEl.addEventListener('focus', async function() {
                if (!customersData.length) {
                    customersData = await searchCustomers('');
                }
                renderCustomerDropdown(customersData);
                document.getElementById('customerDropdownList').classList.remove('hidden');
            });

            customerSearchEl.addEventListener('input', function() {
                const kw = this.value.trim().toLowerCase();
                document.getElementById('customerDropdownList').classList.remove('hidden');

                if (kw.length === 0) {
                    renderCustomerDropdown(customersData);
                    return;
                }

                const filtered = customersData.filter(c =>
                    c.nama.toLowerCase().includes(kw) ||
                    c.telepon.toLowerCase().includes(kw)
                );

                if (filtered.length > 0) {
                    renderCustomerDropdown(filtered);
                } else {
                    clearTimeout(customerDebounce);
                    customerDebounce = setTimeout(async () => {
                        const list = await searchCustomers(kw);
                        renderCustomerDropdown(list);
                    }, 350);
                }
            });
        }

        document.addEventListener('click', (e) => {
            if (!document.getElementById('customerDropdownWrapper')?.contains(e.target)) {
                document.getElementById('customerDropdownList')?.classList.add('hidden');
            }
            if (!document.getElementById('vehicleDropdownWrapper')?.contains(e.target)) {
                document.getElementById('vehicleDropdownList')?.classList.add('hidden');
            }
            if (!document.getElementById('stokDropdownWrapper')?.contains(e.target)) {
                document.getElementById('stokDropdownList')?.classList.add('hidden');
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // VEHICLE DROPDOWN
        // ═══════════════════════════════════════════════════════════════
        function populateVehicleDropdown(vehicles) {
            currentVehicles = vehicles;
            const hint    = document.getElementById('vehicleEmptyHint');
            const wrapper = document.getElementById('vehicleDropdownWrapper');

            hint.classList.add('hidden');
            wrapper.classList.remove('hidden');

            renderVehicleDropdown(vehicles);
        }

        function renderVehicleDropdown(list) {
            const container = document.getElementById('vehicleDropdownItems');
            const dropdown  = document.getElementById('vehicleDropdownList');
            container.innerHTML = '';

            if (list.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-[13px] text-gray-400">Tidak ada kendaraan ditemukan</div>';
            } else {
                list.forEach(v => {
                    const div = document.createElement('div');
                    div.className = 'px-5 py-3.5 hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors';
                    div.innerHTML = `
                        <p class="text-[14px] font-bold text-[#213F5C]">${escHtml(v.model)}</p>
                        <p class="text-[12px] text-gray-400">${escHtml(v.license_plate)} ${v.engine_code ? '• ' + v.engine_code : ''}</p>`;
                    div.addEventListener('click', () => selectVehicle(v));
                    container.appendChild(div);
                });
            }
            dropdown.classList.remove('hidden');
        }

        const vehicleSearchEl = document.getElementById('vehicleSearch');
        if (vehicleSearchEl) {
            vehicleSearchEl.addEventListener('focus', function() {
                renderVehicleDropdown(currentVehicles);
                document.getElementById('vehicleDropdownList').classList.remove('hidden');
            });

            vehicleSearchEl.addEventListener('input', function() {
                const kw = this.value.trim().toLowerCase();
                document.getElementById('vehicleDropdownList').classList.remove('hidden');

                if (kw.length === 0) {
                    renderVehicleDropdown(currentVehicles);
                    return;
                }

                const filtered = currentVehicles.filter(v =>
                    (v.model || '').toLowerCase().includes(kw) ||
                    (v.license_plate || '').toLowerCase().includes(kw)
                );
                renderVehicleDropdown(filtered);
            });
        }

        function clearVehicle() {
            selectedVehicle = null;
            document.getElementById('selectedVehicleId').value = '';
            const vs = document.getElementById('vehicleSearch');
            if (vs) vs.value = '';
            const vc = document.getElementById('vehicleSearchClear');
            if (vc) vc.classList.add('hidden');
            document.getElementById('vehicleInfoBox').classList.add('hidden');
            document.getElementById('vehicleDropdownList').classList.add('hidden');
            renderVehicleDropdown(currentVehicles);
        }

        function selectVehicle(v) {
            selectedVehicle = v;
            document.getElementById('selectedVehicleId').value       = v.id;
            const vs = document.getElementById('vehicleSearch');
            if (vs) vs.value = `${v.model} - ${v.license_plate}`;
            const vc = document.getElementById('vehicleSearchClear');
            if (vc) vc.classList.remove('hidden');

            document.getElementById('vehicleInfoModel').textContent  = v.model;
            document.getElementById('vehicleInfoPlate').textContent  = '🚗 ' + v.license_plate;
            document.getElementById('vehicleInfoEngine').textContent = v.engine_code ? '⚙️ ' + v.engine_code : '';
            document.getElementById('vehicleInfoBox').classList.remove('hidden');
            document.getElementById('vehicleDropdownList').classList.add('hidden');

            const kmEl = document.getElementById('km_masuk');
            if (kmEl && !kmEl.value) {
                kmEl.value = v.odometer ? v.odometer + ' Km' : '';
            }

            isDirty = true;
        }

        // ═══════════════════════════════════════════════════════════════
        // STOK DROPDOWN
        // ═══════════════════════════════════════════════════════════════
        async function loadStokList() {
            try {
                const res = await fetch('/api/spareparts-for-antrian', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                if (res.ok && result.data) stokList = result.data;
            } catch (e) { console.error('Gagal load stok:', e); }
        }

        function renderStokOptions(keyword = '') {
            const container = document.getElementById('stokDropdownItems');
            const dropdown  = document.getElementById('stokDropdownList');
            container.innerHTML = '';

            const filteredList = stokList.filter(stok =>
                stok.nama.toLowerCase().includes(keyword.toLowerCase()) ||
                stok.stok.toString().includes(keyword)
            );

            if (filteredList.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-400 text-[14px]">Stok tidak ditemukan...</div>';
            } else {
                filteredList.forEach(stok => {
                    const div = document.createElement('div');
                    div.className = 'stok-option-item hover:bg-[#F0F7FF] cursor-pointer p-4 border-b border-gray-50' + (selectedStokId === stok.id ? ' bg-blue-50' : '');
                    div.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[14px] font-bold text-[#213F5C]">${escHtml(stok.nama)}</span>
                            <span class="text-[14px] font-bold text-[#1273EB]">${escHtml(stok.harga)}</span>
                        </div>
                        <div class="flex justify-between items-center text-[12px]">
                            <span class="text-gray-500">Sisa Stok: <span class="font-bold text-[#213F5C]">${escHtml(String(stok.stok))}</span></span>
                            <span class="text-gray-400">Suplier: ${escHtml(stok.supplier || '-')}</span>
                        </div>
                    `;
                    div.addEventListener('click', () => selectStok(stok));
                    container.appendChild(div);
                });
            }
            dropdown.classList.remove('hidden');
        }

        const stokSearchEl = document.getElementById('stokSearch');
        if (stokSearchEl) {
            stokSearchEl.addEventListener('focus', function() {
                renderStokOptions(this.value.trim());
                document.getElementById('stokDropdownList').classList.remove('hidden');
            });

            stokSearchEl.addEventListener('input', function() {
                const kw = this.value.trim();
                document.getElementById('stokDropdownList').classList.remove('hidden');
                renderStokOptions(kw);
            });
        }

        function clearStok() {
            selectedStokId   = null;
            selectedStokData = null;
            document.getElementById('inputStok').value = '';
            const ss = document.getElementById('stokSearch');
            if (ss) ss.value = '';
            const sc = document.getElementById('stokSearchClear');
            if (sc) sc.classList.add('hidden');
            document.getElementById('stokDropdownList').classList.add('hidden');
            renderStokOptions('');
        }

        function selectStok(stok) {
            selectedStokId   = stok.id;
            selectedStokData = stok;
            document.getElementById('inputStok').value = stok.id;

            const ss = document.getElementById('stokSearch');
            if (ss) ss.value = `${stok.nama} - ${stok.harga} (Sisa: ${stok.stok})`;

            const sc = document.getElementById('stokSearchClear');
            if (sc) sc.classList.remove('hidden');

            document.getElementById('stokDropdownList').classList.add('hidden');
        }

        function resetStokDropdown() {
            clearStok();
        }

        // ═══════════════════════════════════════════════════════════════
        // SUKU CADANG FORM LOGIC
        // ═══════════════════════════════════════════════════════════════
        const btnTambah   = document.getElementById('btnTambahSukuCadang');
        const formSC      = document.getElementById('formSukuCadang');
        const btnSimpanSC = document.getElementById('btnSimpanSukuCadang');
        const btnBatalSC  = document.getElementById('btnBatalSukuCadang');
        const listEl      = document.getElementById('sukuCadangList');
        const hiddenJSON  = document.getElementById('inputSukuCadangJSON');

        btnTambah.addEventListener('click', () => {
            formSC.classList.remove('hidden');
            btnTambah.classList.add('hidden');
            document.getElementById('inputJumlah').value     = '';
            resetStokDropdown();
            requestAnimationFrame(() => document.getElementById('stokSearch')?.focus({ preventScroll: true }));
        });

        btnBatalSC.addEventListener('click', () => {
            formSC.classList.add('hidden');
            btnTambah.classList.remove('hidden');
            resetStokDropdown();
        });

        btnSimpanSC.addEventListener('click', () => {
            if (!selectedStokData) {
                Swal.fire('Oops!', 'Pilih suku cadang yang ingin digunakan!', 'warning');
                return;
            }

            const namaBarang = selectedStokData.nama;

            const jumlah = parseInt(document.getElementById('inputJumlah').value.trim()) || 1;

            sukuCadangItems.push({
                id           : Date.now(),
                sparepart_id : selectedStokData.id,
                nama         : namaBarang,
                deskripsi    : selectedStokData.nama,
                harga        : selectedStokData.harga,
                jumlah       : jumlah + ' pcs',
                tanggal      : selectedStokData.tanggal,
                supplier     : selectedStokData.supplier,
                stok         : selectedStokData.stok,
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

        // ── Dirty flag ────────────────────────────────────────────────────────────
        document.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', () => isDirty = true);
        });

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // ═══════════════════════════════════════════════════════════════
        // SUBMIT
        // ═══════════════════════════════════════════════════════════════
        document.getElementById('submitBtnApi').addEventListener('click', async (e) => {
            e.preventDefault();

            if (!selectedCustomer) {
                Swal.fire('Oops!', 'Pilih pelanggan terlebih dahulu!', 'warning'); return;
            }
            if (!selectedVehicle) {
                Swal.fire('Oops!', 'Pilih kendaraan pelanggan!', 'warning'); return;
            }

            Swal.fire({ title: 'Menyimpan data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const items = sukuCadangItems
                .filter(sc => sc.sparepart_id)
                .map(sc => ({
                    sparepart_id : sc.sparepart_id,
                    quantity     : parseInt(sc.jumlah) || 1
                }));

            const payload = {
                customer_id : selectedCustomer.id,
                vehicle_id  : selectedVehicle.id,
                km_masuk    : document.getElementById('km_masuk').value.trim() || null,
                items,
            };

            try {
                const res = await fetch('/api/transactions', {
                    method  : 'POST',
                    headers : {
                        'Content-Type'  : 'application/json',
                        'Authorization' : `Bearer ${token}`,
                        'Accept'        : 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const result = await res.json();
                if (res.ok && result.status === 'success') {
                    isDirty = false;
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 2000, showConfirmButton: false });
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                } else {
                    Swal.fire('Gagal!', result.message ?? 'Terjadi kesalahan.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); }
        });

        document.addEventListener('DOMContentLoaded', () => { loadStokList(); });
    </script>
@endsection