@extends('layouts.master')

@section('title', 'Tambah Suku Cadang')
@section('title_header', 'Master Data | Suku Cadang')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Data Suku Cadang')

@section('form_fields')
    <div class="space-y-6" x-data="sparepartForm()" x-init="init()">

        {{-- BOX 1: INFORMASI SUKU CADANG --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path>
                </svg>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Suku Cadang</h2>
            </div>
            <div class="p-8 space-y-6">

                {{-- 1. Kategori dulu --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kategori <span
                            class="text-red-500">*</span></label>
                    <div class="relative" @click.stop>
                        <input type="text" x-model="categorySearch" @input="filterCategories"
                            @focus="showCategoryDropdown = true" placeholder="Ketik untuk cari kategori..."
                            class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] focus:border-[#1273EB]"
                            :class="showCategoryDropdown ? 'border-[#1273EB]' : ''">
                        <div x-show="showCategoryDropdown && filteredCategories.length > 0" x-cloak
                            class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto dropdown-scroll">
                            <template x-for="cat in filteredCategories" :key="cat.category_id">
                                <div @click="selectCategory(cat)"
                                    class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                    :class="formData.item_category_id == cat.category_id ? 'bg-[#EAF2FF] text-[#1273EB]' : ''"
                                    x-text="cat.name"></div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 2. Kode Barang (auto generate setelah kategori dipilih) --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Barang
                        <span class="text-xs font-normal text-gray-400">(otomatis)</span>
                    </label>
                    <input type="text" :value="itemCodePreview" readonly placeholder="Pilih kategori dulu..."
                        class="w-full px-5 py-3.5 bg-gray-100 border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-gray-500 cursor-not-allowed">
                </div>

                {{-- 3. Nama Suku Cadang --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Suku Cadang <span
                            class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.name" placeholder="Masukkan nama suku cadang"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                </div>

            </div>
        </div>

        {{-- BOX 2: INFORMASI MOBIL --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-visible">
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

                {{-- Tipe Mobil --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tipe Mobil</label>
                    <div class="relative" @click.stop>
                        <input type="text" x-model="carSearch" @input="filterCarTypes" @focus="showCarDropdown = true"
                            placeholder="Ketik untuk cari tipe mobil..."
                            class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] focus:border-[#1273EB]"
                            :class="showCarDropdown ? 'border-[#1273EB]' : ''">
                        <div x-show="showCarDropdown && filteredCarTypes.length > 0" x-cloak
                            class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto dropdown-scroll">
                            <div @click="selectCarType(null)"
                                class="px-5 py-3 text-[13px] font-semibold text-gray-400 hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50">
                                -- Semua Tipe Mobil --</div>
                            <template x-for="car in filteredCarTypes" :key="car.car_type_id">
                                <div @click="selectCarType(car)"
                                    class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                    :class="formData.car_type_id == car.car_type_id ? 'bg-[#EAF2FF] text-[#1273EB]' : ''"
                                    x-text="`${car.chassis_number} - ${car.name} (${car.series})`"></div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Kode Mesin --}}
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                    <div class="relative" @click.stop>
                        <input type="text" readonly :value="formData.engine_code || ''"
                            @click="showEngineDropdown = !showEngineDropdown"
                            :placeholder="availableEngines.length ? 'Pilih kode mesin...' : 'Pilih tipe mobil dulu'"
                            class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] cursor-pointer focus:border-[#1273EB]"
                            :class="showEngineDropdown ? 'border-[#1273EB]' : ''">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="showEngineDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                        <div x-show="showEngineDropdown && availableEngines.length > 0" x-cloak
                            class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto dropdown-scroll">
                            <div @click="formData.engine_code = ''; showEngineDropdown = false; onEngineChange()"
                                class="px-5 py-3 text-[13px] font-semibold text-gray-400 hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50">
                                -- Semua Kode Mesin --</div>
                            <template x-for="eng in availableEngines" :key="eng">
                                <div @click="formData.engine_code = eng; showEngineDropdown = false; onEngineChange()"
                                    class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                    :class="formData.engine_code === eng ? 'bg-[#EAF2FF] text-[#1273EB]' : ''"
                                    x-text="eng"></div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- BOX 3: STOK & HARGA --}}
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
                                x-text="'HPP: Rp ' + new Intl.NumberFormat('id-ID').format(stock.cost_off_sell) + ' | ' + stock.date">
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="editStockFromList(index)"
                                class="p-2 text-blue-500 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </button>
                            <button type="button" @click="removeStock(index)"
                                class="p-2 text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" x-show="!showStockForm" @click="openStockForm()"
                    class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Tambah Entri Stok
                </button>

                <div x-show="showStockForm" class="bg-[#F8FAFF] border border-[#D1E4FF] rounded-3xl p-8 space-y-6"
                    x-transition x-cloak>
                    <h3 class="text-[14px] font-bold text-[#213F5C]"
                        x-text="editStockIndex !== null ? 'Ubah Entri Stok' : 'Tambahkan Entri Stok'"></h3>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">HPP <span
                                    class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.cost_off_sell" placeholder="Contoh: 500000"
                                x-on:keydown="if(['-','e','E'].includes($event.key)) $event.preventDefault()"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Harga Jual <span
                                    class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.selling_price" placeholder="Contoh: 1000000"
                                x-on:keydown="if(['-','e','E'].includes($event.key)) $event.preventDefault()"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Jumlah <span
                                    class="text-red-500">*</span></label>
                            <input type="number" x-model="tempStock.quantity" placeholder="Contoh: 10"
                                x-on:keydown="if(['-','e','E'].includes($event.key)) $event.preventDefault()"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Tanggal Masuk <span
                                    class="text-red-500">*</span></label>
                            <input type="date" x-model="tempStock.date"
                                :max="new Date().toISOString().split('T')[0]"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Supplier</label>
                            {{-- Supplier dropdown custom --}}
                            <div class="relative" @click.stop>
                                <input type="text" readonly
                                    :value="tempStock.supplier_id ? (suppliers.find(s => s.supplier_id == tempStock.supplier_id)
                                        ?.name || '') : ''"
                                    @click="showSupplierDropdown = !showSupplierDropdown"
                                    placeholder="-- Tanpa Supplier --"
                                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] cursor-pointer"
                                    :class="showSupplierDropdown ? 'border-[#1273EB]' : ''">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 transition-transform duration-200"
                                        :class="showSupplierDropdown ? 'rotate-180' : ''" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                                <div x-show="showSupplierDropdown" x-cloak
                                    class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto dropdown-scroll">
                                    <div @click="tempStock.supplier_id = ''; showSupplierDropdown = false"
                                        class="px-5 py-3 text-[13px] font-semibold text-gray-400 hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50">
                                        -- Tanpa Supplier --</div>
                                    <template x-for="sup in suppliers" :key="sup.supplier_id">
                                        <div @click="tempStock.supplier_id = sup.supplier_id; showSupplierDropdown = false"
                                            class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                            :class="tempStock.supplier_id == sup.supplier_id ? 'bg-[#EAF2FF] text-[#1273EB]' :
                                                ''"
                                            x-text="sup.name"></div>
                                    </template>
                                </div>
                            </div>
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
        'submitBtnText' => 'Simpan Data',
        'submitBtnId' => 'submitBtnApi',
    ])

    <script>
        function sparepartForm() {
            return {
                formData: {
                    name: '',
                    item_category_id: '',
                    car_type_id: '',
                    engine_code: ''
                },
                tempStock: {
                    cost_off_sell: '',
                    selling_price: '',
                    quantity: '',
                    date: '',
                    supplier_id: ''
                },
                stocks: [],
                suppliers: [],
                categories: [],
                filteredCategories: [],
                categorySearch: '',
                showCategoryDropdown: false,
                carTypes: [],
                filteredCarTypes: [],
                carSearch: '',
                showCarDropdown: false,
                showEngineDropdown: false,
                showSupplierDropdown: false,
                allEngines: [],
                availableEngines: [],
                showStockForm: false,
                editStockIndex: null,
                itemCodePreview: 'Pilih kategori dulu...',
                token: localStorage.getItem('access_token'),

                async init() {
                    const headers = {
                        'Authorization': `Bearer ${this.token}`,
                        'Accept': 'application/json'
                    };

                    try {
                        const res = await fetch('/api/car-types?limit=200', {
                            headers
                        });
                        const r = await res.json();
                        this.carTypes = r.data?.data ?? r.data ?? [];
                        const engineSet = new Set();
                        this.carTypes.forEach(car => {
                            if (car.engine_code) car.engine_code.split(',').map(e => e.trim()).filter(Boolean)
                                .forEach(e => engineSet.add(e));
                        });
                        this.allEngines = [...engineSet].sort();
                        this.availableEngines = this.allEngines;
                        this.filteredCarTypes = this.carTypes;
                    } catch (e) {
                        console.error('Gagal fetch car-types', e);
                    }

                    try {
                        const res = await fetch('/api/suppliers?limit=200', {
                            headers
                        });
                        const r = await res.json();
                        this.suppliers = r.data?.data ?? r.data ?? [];
                    } catch (e) {
                        console.error('Gagal fetch suppliers', e);
                    }

                    try {
                        const res = await fetch('/api/item-categories?limit=200', {
                            headers
                        });
                        const r = await res.json();
                        this.categories = r.data?.data ?? r.data ?? [];
                        this.filteredCategories = this.categories;
                    } catch (e) {
                        console.error('Gagal fetch categories', e);
                    }

                    const btn = document.getElementById('submitBtnApi');
                    if (btn) btn.onclick = (e) => {
                        e.preventDefault();
                        this.submitAllData();
                    };

                    document.addEventListener('click', () => {
                        this.showCarDropdown = false;
                        this.showEngineDropdown = false;
                        this.showCategoryDropdown = false;
                        this.showSupplierDropdown = false;
                    });
                },

                filterCategories() {
                    const q = this.categorySearch.toLowerCase();
                    this.filteredCategories = q ? this.categories.filter(c => c.name.toLowerCase().includes(q)) : this
                        .categories;
                    this.showCategoryDropdown = true;
                },

                selectCategory(cat) {
                    this.formData.item_category_id = cat.category_id;
                    this.categorySearch = cat.name;
                    this.showCategoryDropdown = false;
                    this.generateItemCode(cat);
                },

                async generateItemCode(cat) {
                    const prefix = this.getPrefixFromCategoryName(cat.name);
                    this.itemCodePreview = `${prefix}-... (loading)`;
                    try {
                        const res = await fetch(
                        `/api/spareparts?search=${encodeURIComponent(prefix + '-')}&limit=200`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.token}`
                            }
                        });
                        const r = await res.json();
                        const items = r.data?.data ?? r.data ?? [];
                        let maxNum = 0;
                        items.forEach(item => {
                            if (item.item_code && item.item_code.startsWith(prefix + '-')) {
                                const num = parseInt(item.item_code.split('-').pop()) || 0;
                                if (num > maxNum) maxNum = num;
                            }
                        });
                        this.itemCodePreview = `${prefix}-${maxNum + 1}`;
                    } catch (e) {
                        this.itemCodePreview = `${prefix}-1 (estimasi)`;
                    }
                },

                getPrefixFromCategoryName(name) {
                    const l = name.toLowerCase();
                    if (l.includes('oli') || l.includes('cairan')) return 'oil';
                    if (l.includes('pengereman') || l.includes('brake')) return 'brake';
                    if (l.includes('mesin') || l.includes('engine')) return 'eng';
                    if (l.includes('kaki') || l.includes('suspension')) return 'susp';
                    if (l.includes('elektrikal') || l.includes('electrical')) return 'elec';
                    const p = (l.split(' ')[0] || '').replace(/[^a-z]/g, '');
                    return p || 'item';
                },

                filterCarTypes() {
                    const q = this.carSearch.toLowerCase();
                    this.filteredCarTypes = q ? this.carTypes.filter(t =>
                        (t.chassis_number + ' ' + t.name + ' ' + t.series).toLowerCase().includes(q)
                    ) : this.carTypes;
                    this.showCarDropdown = true;
                },

                selectCarType(car) {
                    if (car) {
                        this.formData.car_type_id = car.car_type_id;
                        this.carSearch = `${car.chassis_number} - ${car.name} (${car.series})`;
                    } else {
                        this.formData.car_type_id = '';
                        this.carSearch = '';
                    }
                    this.showCarDropdown = false;
                    this.onCarTypeChange();
                },

                onCarTypeChange() {
                    this.formData.engine_code = '';
                    if (!this.formData.car_type_id) {
                        this.availableEngines = this.allEngines;
                        return;
                    }
                    const car = this.carTypes.find(c => c.car_type_id == this.formData.car_type_id);
                    this.availableEngines = (car && car.engine_code) ?
                        car.engine_code.split(',').map(e => e.trim()).filter(Boolean) :
                        [];
                },

                onEngineChange() {
                    if (!this.formData.engine_code) {
                        this.formData.car_type_id = '';
                        this.availableEngines = this.allEngines;
                        return;
                    }
                    const filtered = this.carTypes.filter(car =>
                        car.engine_code && car.engine_code.split(',').map(e => e.trim()).includes(this.formData
                            .engine_code)
                    );
                    if (filtered.length === 1) this.formData.car_type_id = filtered[0].car_type_id;
                },

                openStockForm() {
                    this.editStockIndex = null;
                    this.tempStock = {
                        cost_off_sell: '',
                        selling_price: '',
                        quantity: '',
                        date: '',
                        supplier_id: ''
                    };
                    this.showStockForm = true;
                },

                closeStockForm() {
                    this.showStockForm = false;
                    this.editStockIndex = null;
                },

                editStockFromList(index) {
                    this.editStockIndex = index;
                    this.tempStock = {
                        ...this.stocks[index]
                    };
                    this.showStockForm = true;
                },

                addStockToList() {
                    if (!this.tempStock.cost_off_sell || !this.tempStock.selling_price || !this.tempStock.quantity || !this
                        .tempStock.date) {
                        return Swal.fire('Data Belum Lengkap!', 'HPP, Harga Jual, Jumlah, dan Tanggal wajib diisi.',
                            'warning');
                    }

                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const inputDate = new Date(this.tempStock.date);
                    if (inputDate > today) {
                        return Swal.fire('Tanggal Tidak Valid!', 'Tanggal masuk barang tidak boleh di masa depan.',
                            'warning');
                    }

                    const sup = this.suppliers.find(s => s.supplier_id == this.tempStock.supplier_id);
                    const stockData = {
                        ...this.tempStock,
                        cost_off_sell: Number(this.tempStock.cost_off_sell),
                        selling_price: Number(this.tempStock.selling_price),
                        quantity: Number(this.tempStock.quantity),
                        supplier_name: sup ? sup.name : '',
                    };
                    if (this.editStockIndex !== null) {
                        this.stocks[this.editStockIndex] = stockData;
                        this.stocks = [...this.stocks];
                    } else {
                        this.stocks.push(stockData);
                    }
                    this.closeStockForm();
                },

                removeStock(index) {
                    this.stocks.splice(index, 1);
                },

                async submitAllData() {
                    if (!this.formData.name.trim()) return Swal.fire('Error', 'Nama suku cadang wajib diisi!', 'error');
                    if (!this.formData.item_category_id) return Swal.fire('Error', 'Kategori wajib dipilih!', 'error');
                    if (this.stocks.length === 0) return Swal.fire('Error', 'Minimal tambahkan 1 entri stok!', 'error');

                    const firstStock = this.stocks[0];
                    const cat = this.categories.find(c => c.category_id == this.formData.item_category_id);

                    const sparepartData = {
                        name: this.formData.name,
                        category: cat ? cat.name : '',
                        item_category_id: this.formData.item_category_id,
                        car_type_id: this.formData.car_type_id || null,
                        supplier_id: firstStock.supplier_id || null,
                        cost_off_sell: firstStock.cost_off_sell,
                        selling_price: firstStock.selling_price,
                        quantity: this.stocks.reduce((s, st) => s + st.quantity, 0),
                        date: firstStock.date,
                    };

                    Swal.fire({
                        title: 'Menyimpan data...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    try {
                        const res1 = await fetch('/api/spareparts', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.token}`
                            },
                            body: JSON.stringify(sparepartData)
                        });
                        const result1 = await res1.json();

                        if (!res1.ok) {
                            let msg = result1.message || 'Gagal menyimpan.';
                            if (result1.errors) msg = Object.values(result1.errors).flat().join('\n');
                            return Swal.fire({
                                icon: 'error',
                                title: 'Gagal Simpan',
                                text: msg
                            });
                        }

                        const sparepartId = result1.data?.sparepart_id;
                        const stockPayload = this.stocks.map(st => ({
                            cost_off_sell: st.cost_off_sell,
                            selling_price: st.selling_price,
                            quantity: st.quantity,
                            date: st.date,
                            supplier_id: st.supplier_id || null,
                        }));

                        await fetch(`/api/spareparts/${sparepartId}/stocks/bulk`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.token}`
                            },
                            body: JSON.stringify({
                                stocks: stockPayload
                            })
                        });

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Data suku cadang & ${this.stocks.length} entri stok berhasil disimpan.`,
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

    <style>
        [x-cloak] {
            display: none !important;
        }

        .dropdown-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .dropdown-scroll::-webkit-scrollbar-track {
            background: transparent;
            margin: 6px 0;
        }

        .dropdown-scroll::-webkit-scrollbar-thumb {
            background: #D1E4FF;
            border-radius: 99px;
        }

        .dropdown-scroll::-webkit-scrollbar-thumb:hover {
            background: #1273EB;
        }
    </style>
@endsection
