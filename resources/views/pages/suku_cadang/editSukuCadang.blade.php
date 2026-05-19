@extends('layouts.master')

@section('title', 'Edit Suku Cadang')
@section('title_header', 'Master Data | Suku Cadang')

@section('form_icon')
    <div class="w-12 h-12 bg-[#F59E0B] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-amber-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
        </svg>
    </div>
@endsection

@section('form_title', 'Edit Data Suku Cadang')

@section('form_fields')
    <div class="space-y-6" x-data="sparepartEditForm()" x-init="init()">

        {{-- BOX 1: INFORMASI SUKU CADANG --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path>
                </svg>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Suku Cadang</h2>
            </div>
            <div class="p-8 space-y-6">

                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Barang <span class="text-xs font-normal text-gray-400">(Otomatis)</span></label>
                    <input type="text" x-model="formData.item_code" readonly
                        class="w-full px-5 py-3.5 bg-gray-100 border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Suku Cadang <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.name" placeholder="Masukkan nama suku cadang"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                </div>

                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select x-model="formData.item_category_id"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                        <option value="">Pilih Kategori</option>
                        <template x-for="cat in categories" :key="cat.category_id">
                            <option :value="cat.category_id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        {{-- BOX 2: INFORMASI MOBIL --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2"></path>
                        <path d="M13 16H6l-2-5h15l-2 5zM5 11L6.5 6h11L19 11" stroke-width="2"></path>
                    </svg>
                </div>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Mobil</h2>
            </div>
            <div class="p-8 space-y-4">
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tipe Mobil</label>
                    <select x-model="formData.car_type_id" @change="onCarTypeChange()"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C]">
                        <option value="">-- Semua Tipe Mobil --</option>
                        <template x-for="car in carTypes" :key="car.car_type_id">
                            <option :value="car.car_type_id" x-text="`${car.chassis_number} - ${car.name} (${car.series})`"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                    <select x-model="formData.engine_code" @change="onEngineChange()"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C]">
                        <option value="">-- Semua Kode Mesin --</option>
                        <template x-for="eng in availableEngines" :key="eng">
                            <option :value="eng" x-text="eng"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        {{-- BOX 3: STOK & HARGA (Multi-entry) --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2"></path>
                    </svg>
                </div>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Stok & Harga</h2>
            </div>
            <div class="p-8 space-y-6">

                {{-- Daftar entri stok --}}
                <template x-for="(stock, index) in stocks" :key="index">
                    <div class="bg-white border border-[#E5E9F2] rounded-3xl p-8 flex items-center shadow-sm mb-4">
                        <div class="w-fit">
                            <h4 class="text-[18px] font-bold text-[#213F5C]"
                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(stock.selling_price)"></h4>
                            <p class="text-[13px] text-gray-400 font-bold mt-1"
                                x-text="stock.supplier_name || 'Tanpa Supplier'"></p>
                        </div>
                        <div class="flex-1"></div>
                        <div class="text-right mr-10">
                            <p class="text-[20px] font-bold text-[#213F5C]" x-text="stock.quantity + ' pcs'"></p>
                            <p class="text-[12px] text-gray-400 font-bold uppercase"
                                x-text="'HPP: Rp ' + new Intl.NumberFormat('id-ID').format(stock.cost_off_sell) + ' | ' + stock.date"></p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="editStockFromList(index)"
                                class="p-2 text-blue-500 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <button type="button" @click="removeStock(index)"
                                class="p-2 text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Tombol tambah entri stok --}}
                <button type="button" x-show="!showStockForm" @click="openStockForm()"
                    class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Tambah Entri Stok
                </button>

                {{-- FORM INPUT STOK --}}
                <div x-show="showStockForm" class="bg-[#F8FAFF] border border-[#D1E4FF] rounded-3xl p-8 space-y-6" x-transition x-cloak>
                    <h3 class="text-[14px] font-bold text-[#213F5C]"
                        x-text="editStockIndex !== null ? 'Ubah Entri Stok' : 'Tambahkan Entri Stok'"></h3>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">HPP <span class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.cost_off_sell" placeholder="Contoh: 500000"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Harga Jual <span class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.selling_price" placeholder="Contoh: 1000000"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.quantity" placeholder="Contoh: 10"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" x-model="tempStock.date"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Supplier</label>
                            <select x-model="tempStock.supplier_id"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C]">
                                <option value="">-- Tanpa Supplier --</option>
                                <template x-for="sup in suppliers" :key="sup.supplier_id">
                                    <option :value="sup.supplier_id" x-text="sup.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="addStockToList()"
                            class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8]"
                            x-text="editStockIndex !== null ? 'Simpan Perubahan' : 'Simpan'"></button>
                        <button type="button" @click="closeStockForm()"
                            class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-xl font-bold text-[14px] hover:bg-gray-50">Batal</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('suku-cadang.index'),
        'submitBtnText' => 'Simpan Perubahan',
        'submitBtnId' => 'submitBtnApi',
    ])

    <script>
        function sparepartEditForm() {
            return {
                formData: { item_code: '', name: '', item_category_id: '', car_type_id: '', engine_code: '' },
                tempStock: { cost_off_sell: '', selling_price: '', quantity: '', date: '', supplier_id: '' },
                stocks: [],          // semua entri stok (lama + baru)
                deletedStockIds: [], // stock_id yang dihapus user
                suppliers: [],
                categories: [],
                carTypes: [],
                allEngines: [],
                availableEngines: [],
                showStockForm: false,
                editStockIndex: null,
                token: localStorage.getItem('access_token'),
                partId: window.location.pathname.split('/').pop(),

                async init() {
                    const headers = { 'Authorization': `Bearer ${this.token}`, 'Accept': 'application/json' };

                    // Load car types
                    try {
                        const res = await fetch('/api/car-types?limit=200', { headers });
                        const r = await res.json();
                        this.carTypes = r.data?.data ?? r.data ?? [];
                        // Extract semua engine codes unik
                        const engineSet = new Set();
                        this.carTypes.forEach(car => {
                            if (car.engine_code) {
                                car.engine_code.split(',').map(e => e.trim()).filter(Boolean).forEach(e => engineSet.add(e));
                            }
                        });
                        this.allEngines = [...engineSet].sort();
                        this.availableEngines = this.allEngines;
                    } catch (e) { console.error('Gagal fetch car-types', e); }

                    // Load suppliers
                    try {
                        const res = await fetch('/api/suppliers?limit=200', { headers });
                        const r = await res.json();
                        this.suppliers = r.data?.data ?? r.data ?? [];
                    } catch (e) { console.error('Gagal fetch suppliers', e); }

                    // Load categories
                    try {
                        const res = await fetch('/api/item-categories?limit=200', { headers });
                        const r = await res.json();
                        this.categories = r.data?.data ?? r.data ?? [];
                    } catch (e) { console.error('Gagal fetch categories', e); }

                    // Load data sparepart existing (termasuk stocks)
                    try {
                        const res = await fetch(`/api/spareparts/${this.partId}`, { headers });
                        const r = await res.json();
                        if (res.ok) {
                            const d = r.data ?? r;
                            this.formData.item_code        = d.item_code        ?? '';
                            this.formData.name             = d.name             ?? '';
                            this.formData.item_category_id = d.item_category_id ?? '';
                            this.formData.car_type_id      = d.car_type_id      ?? '';
                            // Set engine options berdasarkan car_type yang dimuat
                            if (this.formData.car_type_id) {
                                const car = this.carTypes.find(c => c.car_type_id == this.formData.car_type_id);
                                if (car && car.engine_code) {
                                    this.availableEngines = car.engine_code.split(',').map(e => e.trim()).filter(Boolean);
                                }
                            }

                            // Load entri stok
                            const rawStocks = d.stocks ?? [];
                            if (rawStocks.length > 0) {
                                this.stocks = rawStocks.map(st => {
                                    const sup = this.suppliers.find(s => s.supplier_id == st.supplier_id);
                                    return {
                                        stock_id:      st.stock_id,
                                        cost_off_sell: st.cost_off_sell,
                                        selling_price: st.selling_price,
                                        quantity:      st.quantity,
                                        date:          st.date,
                                        supplier_id:   st.supplier_id ?? '',
                                        supplier_name: sup ? sup.name : (st.supplier?.name ?? ''),
                                    };
                                });
                            } else {
                                // Fallback: buat 1 baris dari data utama sparepart
                                const sup = this.suppliers.find(s => s.supplier_id == d.supplier_id);
                                this.stocks = [{
                                    stock_id:      null,
                                    cost_off_sell: d.cost_off_sell ?? '',
                                    selling_price: d.selling_price ?? '',
                                    quantity:      d.quantity ?? '',
                                    date:          d.date ?? '',
                                    supplier_id:   d.supplier_id ?? '',
                                    supplier_name: sup ? sup.name : '',
                                }];
                            }
                        } else {
                            Swal.fire('Gagal', 'Data tidak ditemukan.', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
                    }

                    // Hook tombol simpan di sidebar
                    document.getElementById('submitBtnApi').onclick = (e) => {
                        e.preventDefault();
                        this.submitUpdateData();
                    };
                },

                onCarTypeChange() {
                    this.formData.engine_code = '';
                    if (!this.formData.car_type_id) {
                        this.availableEngines = this.allEngines;
                        return;
                    }
                    const car = this.carTypes.find(c => c.car_type_id == this.formData.car_type_id);
                    if (car && car.engine_code) {
                        this.availableEngines = car.engine_code.split(',').map(e => e.trim()).filter(Boolean);
                    } else {
                        this.availableEngines = [];
                    }
                },

                onEngineChange() {
                    if (!this.formData.engine_code) {
                        this.formData.car_type_id = '';
                        this.availableEngines = this.allEngines;
                        return;
                    }
                    const filtered = this.carTypes.filter(car =>
                        car.engine_code && car.engine_code.split(',').map(e => e.trim()).includes(this.formData.engine_code)
                    );
                    if (filtered.length === 1) this.formData.car_type_id = filtered[0].car_type_id;
                },

                openStockForm() {
                    this.editStockIndex = null;
                    this.tempStock = { cost_off_sell: '', selling_price: '', quantity: '', date: '', supplier_id: '' };
                    this.showStockForm = true;
                },

                closeStockForm() { this.showStockForm = false; this.editStockIndex = null; },

                editStockFromList(index) {
                    this.editStockIndex = index;
                    this.tempStock = { ...this.stocks[index] };
                    this.showStockForm = true;
                },

                addStockToList() {
                    if (!this.tempStock.cost_off_sell || !this.tempStock.selling_price || !this.tempStock.quantity || !this.tempStock.date) {
                        return Swal.fire('Data Belum Lengkap!', 'HPP, Harga Jual, Jumlah, dan Tanggal wajib diisi.', 'warning');
                    }
                    const sup = this.suppliers.find(s => s.supplier_id == this.tempStock.supplier_id);
                    const stockData = {
                        ...this.tempStock,
                        cost_off_sell: Number(this.tempStock.cost_off_sell),
                        selling_price: Number(this.tempStock.selling_price),
                        quantity:      Number(this.tempStock.quantity),
                        supplier_name: sup ? sup.name : '',
                    };
                    if (this.editStockIndex !== null) {
                        this.stocks[this.editStockIndex] = stockData;
                        this.stocks = [...this.stocks]; // trigger Alpine reactivity
                    } else {
                        this.stocks.push(stockData);
                    }
                    this.closeStockForm();
                },

                removeStock(index) {
                    const stockId = this.stocks[index].stock_id;
                    if (stockId) this.deletedStockIds.push(stockId);
                    this.stocks.splice(index, 1);
                },

                async submitUpdateData() {
                    if (!this.formData.name.trim()) {
                        return Swal.fire('Error', 'Nama suku cadang wajib diisi!', 'error');
                    }
                    if (!this.formData.item_category_id) {
                        return Swal.fire('Error', 'Kategori wajib dipilih!', 'error');
                    }
                    if (this.stocks.length === 0) {
                        return Swal.fire('Error', 'Minimal harus ada 1 entri stok!', 'error');
                    }

                    const firstStock = this.stocks[0];
                    const cat = this.categories.find(c => c.category_id == this.formData.item_category_id);

                    const sparepartData = {
                        item_code:        this.formData.item_code,
                        name:             this.formData.name,
                        category:         cat ? cat.name : '',
                        item_category_id: this.formData.item_category_id,
                        car_type_id:      this.formData.car_type_id || null,
                        supplier_id:      firstStock.supplier_id || null,
                        cost_off_sell:    firstStock.cost_off_sell,
                        selling_price:    firstStock.selling_price,
                        quantity:         this.stocks.reduce((s, st) => s + st.quantity, 0),
                        date:             firstStock.date,
                    };

                    Swal.fire({ title: 'Menyimpan perubahan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    try {
                        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${this.token}` };

                        // 1. Update data sparepart utama
                        const res1 = await fetch(`/api/spareparts/${this.partId}`, {
                            method: 'PUT', headers,
                            body: JSON.stringify(sparepartData)
                        });
                        const result1 = await res1.json();
                        if (!res1.ok) {
                            let msg = result1.message || 'Gagal update.';
                            if (result1.errors) msg = Object.values(result1.errors).flat().join('\n');
                            return Swal.fire({ icon: 'error', title: 'Gagal Update', text: msg });
                        }

                        // 2. Hapus stok yang di-remove user
                        for (const sid of this.deletedStockIds) {
                            await fetch(`/api/spareparts/${this.partId}/stocks/${sid}`, {
                                method: 'DELETE', headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${this.token}` }
                            });
                        }

                        // 3. Update/create tiap baris stok
                        for (const st of this.stocks) {
                            const payload = {
                                cost_off_sell: st.cost_off_sell,
                                selling_price: st.selling_price,
                                quantity:      st.quantity,
                                date:          st.date,
                                supplier_id:   st.supplier_id || null,
                            };
                            if (st.stock_id) {
                                await fetch(`/api/spareparts/${this.partId}/stocks/${st.stock_id}`, {
                                    method: 'PUT', headers, body: JSON.stringify(payload)
                                });
                            } else {
                                await fetch(`/api/spareparts/${this.partId}/stocks`, {
                                    method: 'POST', headers, body: JSON.stringify(payload)
                                });
                            }
                        }

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data suku cadang & stok berhasil diperbarui.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        window.location.href = "{{ route('suku-cadang.index') }}";

                    } catch (e) {
                        console.error(e);
                        Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
                    }
                }
            }
        }
    </script>

    <style>[x-cloak] { display: none !important; }</style>
@endsection