@extends('layouts.master')

@section('title', 'Edit Suku Cadang')
@section('title_header', 'Master Data | Suku Cadang')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#F59E0B] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-amber-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
        </svg>
    </div>
@endsection

@section('form_title', 'Edit Data Suku Cadang')

@section('form_fields')
    <form id="editSparePartForm" class="space-y-6">

        {{-- Informasi Suku Cadang --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Suku Cadang</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" id="item_code" required placeholder="Masukan kode barang"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Suku Cadang <span
                        class="text-red-500">*</span></label>
                <input type="text" id="name" required placeholder="Masukan nama suku cadang"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kategori <span
                        class="text-red-500">*</span></label>
                <select id="item_category_id" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="" disabled selected>Pilih Kategori</option>
                </select>
            </div>
        </div>

        {{-- Informasi Mobil Suku Cadang --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3M8 17l4 4m0 0l4-4m-4 4V11" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Mobil Suku Cadang</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tipe Mobil</label>
                <select id="car_type"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="">-- Semua Tipe Mobil --</option>
                </select>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                <select id="engine_code"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="">-- Semua Kode Mesin --</option>
                </select>
            </div>
        </div>

        {{-- Harga dan Stok --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3M8 17l4 4m0 0l4-4m-4 4V11" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Harga dan Stok</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Harga Pokok Produksi (HPP) <span class="text-red-500">*</span></label>
                <input type="text" id="cost_off_sell" required inputmode="numeric" pattern="[0-9]*" placeholder="Contoh: 500000"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Harga Jual <span
                        class="text-red-500">*</span></label>
                <input type="text" id="selling_price" required inputmode="numeric" pattern="[0-9]*"
                    placeholder="Contoh: 1000000"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Jumlah Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" id="quantity" required inputmode="numeric" pattern="[0-9]*" placeholder="Contoh: 10"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Supplier</label>
                <select id="supplier_id"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="" disabled selected>Pilih Supplier</option>
                </select>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tanggal Masuk <span
                        class="text-red-500">*</span></label>
                <input type="date" id="date" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
            </div>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('suku-cadang.index'),
        'submitBtnText' => 'Simpan Perubahan'
    ])

    <script>
        // Ambil ID dari URL (contoh: /suku-cadang/edit/3 -> 3)
        const pathArray = window.location.pathname.split('/');
        const partId = pathArray[pathArray.length - 1];
        const token = localStorage.getItem('access_token');

        // ─── Data stores ──────────────────────────────────────────
        let allCarTypes = [];
        let allEngines  = [];

        // ─── Blokir input non-angka ───────────────────────────────
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('keydown', (e) => {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
            });
            input.addEventListener('paste', (e) => {
                if (!/^[0-9]+$/.test(e.clipboardData.getData('text'))) e.preventDefault();
            });
        });

        // ─── Fetch & populate dropdowns + load existing data ──────
        document.addEventListener('DOMContentLoaded', async () => {
            const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

            // 1. Fetch car types
            try {
                const res = await fetch('/api/car-types?limit=200', { headers });
                const result = await res.json();
                allCarTypes = result.data?.data ?? result.data ?? [];

                const engineSet = new Set();
                allCarTypes.forEach(car => {
                    if (car.engine_code) {
                        car.engine_code.split(',').map(e => e.trim()).filter(Boolean).forEach(e => engineSet.add(e));
                    }
                });
                allEngines = [...engineSet].sort();

                populateCarTypes(allCarTypes);
                populateEngines(allEngines);
            } catch (e) { console.error('Gagal fetch car-types:', e); }

            // 2. Fetch suppliers
            try {
                const res = await fetch('/api/suppliers?limit=200', { headers });
                const result = await res.json();
                const suppliers = result.data?.data ?? result.data ?? [];
                const select = document.getElementById('supplier_id');
                suppliers.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.supplier_id;
                    opt.textContent = s.name;
                    select.appendChild(opt);
                });
            } catch (e) { console.error('Gagal fetch suppliers:', e); }

            // 3. Fetch item categories
            try {
                const res = await fetch('/api/item-categories?limit=200', { headers });
                const result = await res.json();
                const categories = result.data?.data ?? result.data ?? [];
                const select = document.getElementById('item_category_id');
                categories.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.category_id;
                    opt.textContent = c.name;
                    select.appendChild(opt);
                });
            } catch (e) { console.error('Gagal fetch item-categories:', e); }

            // 4. Load existing sparepart data
            try {
                const response = await fetch(`/api/spareparts/${partId}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });

                const result = await response.json();

                if (response.ok) {
                    const d = result.data ?? result;
                    document.getElementById('item_code').value       = d.item_code      ?? '';
                    document.getElementById('name').value            = d.name           ?? '';
                    document.getElementById('cost_off_sell').value   = d.cost_off_sell  ?? '';
                    document.getElementById('selling_price').value   = d.selling_price  ?? '';
                    document.getElementById('quantity').value        = d.quantity       ?? '';
                    document.getElementById('date').value            = d.date           ?? '';

                    if (d.supplier_id) document.getElementById('supplier_id').value = d.supplier_id;
                    if (d.item_category_id) document.getElementById('item_category_id').value = d.item_category_id;

                    if (d.car_type_id) document.getElementById('car_type').value = d.car_type_id;
                    // Note: engine_code is not saved in spareparts table anymore
                } else {
                    Swal.fire('Gagal', 'Data tidak ditemukan.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
            }
        });

        // ─── Populate helpers ─────────────────────────────────────
        function populateCarTypes(cars) {
            const select = document.getElementById('car_type');
            const currentVal = select.value;
            select.innerHTML = '<option value="">-- Semua Tipe Mobil --</option>';
            cars.forEach(car => {
                const opt = document.createElement('option');
                opt.value = car.car_type_id;
                opt.textContent = `${car.chassis_number} - ${car.name} (${car.series})`;
                select.appendChild(opt);
            });
            if (currentVal) select.value = currentVal;
        }

        function populateEngines(engines) {
            const select = document.getElementById('engine_code');
            const currentVal = select.value;
            select.innerHTML = '<option value="">-- Semua Kode Mesin --</option>';
            engines.forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                select.appendChild(opt);
            });
            if (currentVal) select.value = currentVal;
        }

        // ─── Cascading filter logic ───────────────────────────────
        document.getElementById('car_type').addEventListener('change', function() {
            const selectedCar = this.value;
            if (!selectedCar) {
                populateEngines(allEngines);
                return;
            }
            const car = allCarTypes.find(c => Number(c.car_type_id) === Number(selectedCar));
            if (car && car.engine_code) {
                const availableEngines = car.engine_code.split(',').map(e => e.trim()).filter(Boolean);
                populateEngines(availableEngines);
            }
        });

        document.getElementById('engine_code').addEventListener('change', function() {
            const selectedEngine = this.value;
            if (!selectedEngine) {
                populateCarTypes(allCarTypes);
                return;
            }
            const filteredCars = allCarTypes.filter(car =>
                car.engine_code && car.engine_code.split(',').map(e => e.trim()).includes(selectedEngine)
            );
            populateCarTypes(filteredCars);
        });

        // ── Submit update ───────────────────────────────────────────────
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();

            const categorySelect = document.getElementById('item_category_id');
            const data = {
                item_code:        document.getElementById('item_code').value,
                name:             document.getElementById('name').value,
                category:         categorySelect.selectedOptions[0]?.text || '',
                item_category_id: categorySelect.value || null,
                cost_off_sell:    Number(document.getElementById('cost_off_sell').value) || 0,
                selling_price:    Number(document.getElementById('selling_price').value),
                quantity:         Number(document.getElementById('quantity').value),
                supplier_id:      document.getElementById('supplier_id').value || null,
                car_type_id:      document.getElementById('car_type').value || null,
            };

            try {
                Swal.fire({
                    title: 'Menyimpan perubahan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                const response = await fetch(`/api/spareparts/${partId}`, {
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
                        text: 'Data suku cadang berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('suku-cadang.index') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Update',
                        text: result.message || 'Cek kembali inputan lu brok!'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            }
        };
    </script>
@endsection