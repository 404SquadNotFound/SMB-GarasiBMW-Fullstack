@extends('layouts.master')

@section('title', 'Edit Servis Mobil')
@section('title_header', 'Manajemen Servis | Edit Servis')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#F59E0B] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-amber-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
        </svg>
    </div>
@endsection

@section('form_title', 'Edit Data Servis Mobil')

@section('form_fields')
    <form id="editServisForm" class="space-y-6">

        {{-- Informasi Pemilik Kendaraan --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Lengkap <span
                        class="text-red-500">*</span></label>
                <input type="text" id="owner_name" required placeholder="Masukkan nama lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Telepon <span
                        class="text-red-500">*</span></label>
                <input type="text" id="phone_number" required placeholder="Masukkan nomor telepon"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Alamat <span
                        class="text-red-500">*</span></label>
                <input type="text" id="address" required placeholder="Masukkan alamat lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>
        </div>

        {{-- Informasi Mobil Pelanggan --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 .001M13 16H9m4 0h2m2 0h1a1 1 0 001-1v-3.657a1 1 0 00-.293-.707l-2.828-2.828A1 1 0 0015.172 8H13" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Mobil</label>
                <input type="text" id="car_model" placeholder="Masukkan model mobil"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
                <input type="text" id="plate_number" placeholder="Masukkan nomor polisi"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                <input type="text" id="engine_code" placeholder="Masukkan kode mesin"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Km Masuk Mobil</label>
                <input type="text" id="km_in" inputmode="numeric" pattern="[0-9]*" placeholder="Masukkan kilometer"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Cabang Bengkel</label>
                <select id="branch_id"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="" disabled selected>Pilih Cabang</option>
                </select>
            </div>
        </div>

        {{-- Penggunaan Suku Cadang --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h3>
            </div>

            {{-- Container for spare parts rows --}}
            <div id="sparePartsContainer" class="space-y-3">
                {{-- Rows will be populated from API --}}
            </div>

            <button type="button" id="addSparePartBtn"
                class="w-full py-3 bg-[#1273EB] hover:bg-[#0F63CC] text-white text-[14px] font-semibold rounded-xl flex items-center justify-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Suku Cadang
            </button>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('manajemen-servis.index'),
        'submitBtnText' => 'Simpan Perubahan'
    ])

    <script>
        const servisId = {{ $id }};
        const token = localStorage.getItem('access_token');

        // Cache all available spare parts for populating selects
        let allSpareParts = [];

        // ── Blokir input non-angka ──────────────────────────────────────
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('keydown', (e) => {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });
            input.addEventListener('paste', (e) => {
                const pasted = e.clipboardData.getData('text');
                if (!/^[0-9]+$/.test(pasted)) e.preventDefault();
            });
        });

        // ── Helper: buat baris suku cadang ─────────────────────────────
        function createSparePartRow(selectedPartId = '', qty = '') {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 p-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl spare-part-row';
            row.innerHTML = `
                <div class="flex-1">
                    <select class="spare-part-select w-full px-4 py-2.5 bg-white border border-[#E5E9F2] rounded-lg outline-none text-[13px] text-[#213F5C]">
                        <option value="" disabled>Pilih Suku Cadang</option>
                        ${allSpareParts.map(p => `
                            <option value="${p.id}" ${p.id == selectedPartId ? 'selected' : ''}>
                                ${p.part_code} - ${p.part_name}
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div class="w-24">
                    <input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="Qty" value="${qty}"
                        class="spare-part-qty w-full px-4 py-2.5 bg-white border border-[#E5E9F2] rounded-lg outline-none text-[13px] text-[#213F5C] placeholder-gray-400">
                </div>
                <button type="button" onclick="removeSparePartRow(this)"
                    class="w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            // Blokir non-angka pada qty input baru
            const qtyInput = row.querySelector('.spare-part-qty');
            qtyInput.addEventListener('keydown', (e) => {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });
            qtyInput.addEventListener('paste', (e) => {
                const pasted = e.clipboardData.getData('text');
                if (!/^[0-9]+$/.test(pasted)) e.preventDefault();
            });

            return row;
        }

        function removeSparePartRow(btn) {
            btn.closest('.spare-part-row').remove();
        }

        document.getElementById('addSparePartBtn').addEventListener('click', () => {
            document.getElementById('sparePartsContainer').appendChild(createSparePartRow());
        });

        // ── Load semua spare parts & branches terlebih dahulu ──────────
        async function loadSpareParts() {
            try {
                const response = await fetch('/api/spare-parts', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await response.json();
                allSpareParts = result.data ?? result;
            } catch (err) {
                console.error('Gagal memuat suku cadang:', err);
            }
        }

        async function loadBranches(selectedBranchId = null) {
            try {
                const response = await fetch('/api/branches', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await response.json();
                const branches = result.data ?? result;
                const select = document.getElementById('branch_id');
                branches.forEach(branch => {
                    const opt = document.createElement('option');
                    opt.value = branch.id;
                    opt.textContent = branch.name;
                    if (branch.id == selectedBranchId) opt.selected = true;
                    select.appendChild(opt);
                });
            } catch (err) {
                console.error('Gagal memuat cabang:', err);
            }
        }

        // ── Load data existing saat halaman dibuka ──────────────────────
        (async () => {
            // Muat spare parts & data servis secara paralel
            await loadSpareParts();

            try {
                const response = await fetch(`/api/service-management/${servisId}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });

                const result = await response.json();

                if (response.ok) {
                    const d = result.data ?? result;

                    // Isi field pemilik & mobil
                    document.getElementById('owner_name').value   = d.owner_name   ?? '';
                    document.getElementById('phone_number').value = d.phone_number ?? '';
                    document.getElementById('address').value      = d.address      ?? '';
                    document.getElementById('car_model').value    = d.car_model    ?? '';
                    document.getElementById('plate_number').value = d.plate_number ?? '';
                    document.getElementById('engine_code').value  = d.engine_code  ?? '';
                    document.getElementById('km_in').value        = d.km_in        ?? '';

                    // Load branches dengan pre-select cabang yang tersimpan
                    await loadBranches(d.branch_id ?? null);

                    // Render baris suku cadang yang sudah ada
                    const container = document.getElementById('sparePartsContainer');
                    container.innerHTML = '';
                    const spareParts = d.spare_parts ?? [];
                    if (spareParts.length > 0) {
                        spareParts.forEach(item => {
                            container.appendChild(createSparePartRow(item.spare_part_id, item.quantity));
                        });
                    }
                } else {
                    Swal.fire('Gagal', 'Data tidak ditemukan.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
            }
        })();

        // ── Submit update ───────────────────────────────────────────────
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();

            // Kumpulkan baris suku cadang
            const sparePartRows = document.querySelectorAll('#sparePartsContainer .spare-part-row');
            const spareParts = [];
            sparePartRows.forEach(row => {
                const partId = row.querySelector('.spare-part-select').value;
                const qty    = row.querySelector('.spare-part-qty').value;
                if (partId && qty) {
                    spareParts.push({ spare_part_id: partId, quantity: Number(qty) });
                }
            });

            const data = {
                owner_name:   document.getElementById('owner_name').value,
                phone_number: document.getElementById('phone_number').value,
                address:      document.getElementById('address').value,
                car_model:    document.getElementById('car_model').value,
                plate_number: document.getElementById('plate_number').value,
                engine_code:  document.getElementById('engine_code').value,
                km_in:        Number(document.getElementById('km_in').value) || null,
                branch_id:    document.getElementById('branch_id').value || null,
                spare_parts:  spareParts,
            };

            try {
                Swal.fire({
                    title: 'Menyimpan perubahan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch(`/api/service-management/${servisId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data servis mobil berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('manajemen-servis.index') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Update',
                        text: result.message || 'Cek kembali inputan yang diisi!'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            }
        };
    </script>
@endsection