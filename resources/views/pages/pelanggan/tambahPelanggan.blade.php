@extends('layouts.master')

@section('title', 'Tambah Pelanggan')
@section('title_header', 'Data Pelanggan')

{{-- 1. Ikon Header --}}
@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Data Pelanggan Baru')

{{-- 2. Isi Form Utama (Kiri) --}}
@section('form_fields')
    <div class="space-y-6" x-data="customerForm()" x-init="init()">
        {{-- BOX 1: INFORMASI PRIBADI --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path>
                </svg>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Pribadi Pelanggan</h2>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.name" placeholder="Masukkan nama lengkap"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    {{-- FIX 1: maxlength + hanya angka --}}
                    <input type="text" x-model="formData.phone_number" maxlength="15"
                        x-on:input="formData.phone_number = $el.value.replace(/[^0-9]/g, '')"
                        placeholder="Masukkan nomor telepon"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Alamat <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.address" placeholder="Masukkan alamat lengkap"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl focus:border-[#1273EB] transition-all outline-none text-[#213F5C] font-semibold text-[14px]">
                </div>
            </div>
        </div>

        {{-- BOX 2: INFORMASI MOBIL --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-width="2"></path>
                    </svg>
                </div>
                <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h2>
            </div>
            <div class="p-8 space-y-6">
                <template x-for="(car, index) in cars" :key="index">
                    <div class="bg-white border border-[#E5E9F2] rounded-3xl p-8 flex items-center shadow-sm mb-4">
                        <div class="w-fit">
                            <h4 class="text-[18px] font-bold text-[#213F5C]" x-text="car.car_name"></h4>
                            <p class="text-[13px] text-gray-400 font-bold mt-1" x-text="car.license_plate"></p>
                        </div>
                        <div class="flex-1"></div>
                        <div class="text-right mr-10">
                            <p class="text-[20px] font-bold text-[#213F5C]" x-text="new Intl.NumberFormat('id-ID').format(car.km_reading) + ' km'"></p>
                            <p class="text-[12px] text-gray-400 font-bold uppercase" x-text="'Mesin: ' + (car.engine_name || '-') + ' | Th: ' + (car.year || '-')"></p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="editCarFromList(index)" class="p-2 text-blue-500 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button type="button" @click="removeCar(index)" class="p-2 text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" x-show="!showForm" @click="openForm()" class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    Tambah Mobil
                </button>

                {{-- FORM INPUT MOBIL --}}
                <div x-show="showForm" class="bg-[#F8FAFF] border border-[#D1E4FF] rounded-3xl p-8 space-y-6" x-transition x-cloak>
                    <h3 class="text-[14px] font-bold text-[#213F5C]" x-text="editIndex !== null ? 'Ubah Informasi Mobil Pelanggan' : 'Tambahkan Informasi Mobil Pelanggan'"></h3>
                    <div class="flex flex-col gap-5">
                        {{-- Model Mobil --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Model Mobil</label>
                            {{-- FIX 4: Dropdown — stopPropagation biar close-outside gak langsung nutup --}}
                            <div class="relative" @click.stop>
                                <input type="text" x-model="carSearch" @input="filterCarTypes" @focus="showCarDropdown = true"
                                    placeholder="Ketik untuk cari model BMW..."
                                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] focus:border-[#1273EB]">
                                <div x-show="showCarDropdown && filteredCarTypes.length > 0" x-cloak
                                    class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="type in filteredCarTypes" :key="type.car_type_id">
                                        <div @click="selectCarType(type)"
                                            class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                            x-text="type.name"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        {{-- Kode Mesin --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                            <div class="relative" @click.stop>
                                <input type="text" readonly
                                    :value="tempCar.engine_name || ''"
                                    :disabled="!tempCar.car_type_id"
                                    @click="if(tempCar.car_type_id) showEngineDropdown = !showEngineDropdown"
                                    :placeholder="tempCar.car_type_id ? 'Pilih kode mesin...' : 'Pilih model dulu'"
                                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] cursor-pointer disabled:bg-gray-50 disabled:cursor-not-allowed focus:border-[#1273EB]"
                                    :class="showEngineDropdown ? 'border-[#1273EB]' : ''">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400"
                                    :class="tempCar.car_type_id ? 'opacity-100' : 'opacity-30'">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="showEngineDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                                <div x-show="showEngineDropdown && availableEngines.length > 0" x-cloak
                                    class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-xl shadow-lg max-h-48 overflow-y-auto dropdown-scroll">
                                    <template x-for="eng in availableEngines" :key="eng">
                                        <div @click="tempCar.engine_name = eng; showEngineDropdown = false"
                                            class="px-5 py-3 text-[13px] font-semibold text-[#213F5C] hover:bg-[#EAF2FF] cursor-pointer border-b border-gray-50 last:border-0"
                                            :class="tempCar.engine_name === eng ? 'bg-[#EAF2FF] text-[#1273EB]' : ''"
                                            x-text="eng"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        {{-- Kode Transmisi --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Kode Transmisi</label>
                            <input type="text" x-model="tempCar.transmission" placeholder="A4Q"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                        {{-- Tahun Mobil --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Tahun Mobil</label>
                            <input type="number"
                                min="1928"
                                max="2026"
                                x-model.number="tempCar.year"
                                x-on:keydown="if(['-', 'e', 'E', '.'].includes($event.key)) $event.preventDefault()"
                                x-on:input="if ($el.value.length > 4) { $el.value = $el.value.slice(0, 4); tempCar.year = Number($el.value); }"
                                x-on:blur="if(tempCar.year && tempCar.year < 1928) tempCar.year = 1928"
                                placeholder="2020"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                        {{-- Nomor Polisi --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
                            {{-- FIX 1: Auto uppercase, hanya huruf/angka/spasi, max 10 char --}}
                            <input type="text" x-model="tempCar.license_plate" maxlength="8"
                                x-on:input="tempCar.license_plate = $el.value.toUpperCase().replace(/[^A-Z0-9 ]/g, '')"
                                placeholder="B1040JAW"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] tracking-widest font-semibold">
                        </div>
                        {{-- KM Masuk Bengkel --}}
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">KM Masuk Bengkel</label>
                            {{-- FIX 2: Hanya angka positif, format ribuan tampil di display card tapi input tetap bersih --}}
                            <input type="number"
                                min="0"
                                max="9999999"
                                x-model.number="tempCar.km_reading"
                                x-on:keydown="if(['-', 'e', 'E', '.'].includes($event.key)) $event.preventDefault()"
                                x-on:input="if($el.value < 0) { $el.value = 0; tempCar.km_reading = 0; }"
                                placeholder="50000"
                                class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="addCarToList"
                            class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8]"
                            x-text="editIndex !== null ? 'Simpan Perubahan' : 'Simpan'">Simpan</button>
                        <button type="button" @click="closeForm()"
                            class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-xl font-bold text-[14px] hover:bg-gray-50">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 3. Side Buttons & Script Logic --}}
@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('pelanggan.index'),
        'submitBtnText' => 'Simpan Data',
        'submitBtnId' => 'submitBtnApi',
        'submitBtnIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>'
    ])

    <script>
        function customerForm() {
            return {
                formData: { name: '', phone_number: '', address: '' },
                tempCar: { car_type_id: '', engine_name: '', transmission: '', year: '', license_plate: '', km_reading: '' },
                cars: [],
                carTypes: [],
                filteredCarTypes: [],
                carSearch: '',
                showCarDropdown: false,
                availableEngines: [],
                showEngineDropdown: false,
                showForm: false,
                editIndex: null,
                token: localStorage.getItem('access_token'),

                async init() {
                    try {
                        const res = await fetch('/api/car-types?limit=200', {
                            headers: { 'Authorization': `Bearer ${this.token}`, 'Accept': 'application/json' }
                        });
                        const result = await res.json();
                        this.carTypes = result.data || [];
                        this.filteredCarTypes = this.carTypes;
                    } catch (e) { console.error("Gagal muat model", e); }

                    // Hook tombol simpan di sidebar
                    const btn = document.getElementById('submitBtnApi');
                    if (btn) btn.onclick = (e) => { e.preventDefault(); this.submitAllData(); };

                    // FIX 4: Close dropdown saat klik di luar — pakai flag bukan closest
                    document.addEventListener('click', () => { this.showCarDropdown = false; this.showEngineDropdown = false; });
                },

                filterCarTypes() {
                    const q = this.carSearch.toLowerCase();
                    this.filteredCarTypes = q
                        ? this.carTypes.filter(t => t.name.toLowerCase().includes(q))
                        : this.carTypes;
                    this.showCarDropdown = true;
                },

                selectCarType(type) {
                    this.tempCar.car_type_id = type.car_type_id;
                    this.carSearch = type.name;
                    this.showCarDropdown = false;
                    this.updateAvailableEngines(true); // reset engine_name saat pilih model baru
                },

                // FIX 3: Parameter resetEngine — saat edit jangan reset nilai yang sudah ada
                updateAvailableEngines(resetEngine = true) {
                    const selected = this.carTypes.find(t => t.car_type_id == this.tempCar.car_type_id);
                    this.availableEngines = (selected && selected.engine_code)
                        ? selected.engine_code.split(',').map(s => s.trim())
                        : [];
                    if (resetEngine) this.tempCar.engine_name = '';
                },

                openForm() {
                    this.editIndex = null;
                    this.tempCar = { car_type_id: '', engine_name: '', transmission: '', year: '', license_plate: '', km_reading: '' };
                    this.carSearch = '';
                    this.availableEngines = [];
                    this.filteredCarTypes = this.carTypes;
                    this.showForm = true;
                },

                closeForm() {
                    this.showForm = false;
                    this.editIndex = null;
                    this.carSearch = '';
                    this.showCarDropdown = false;
                    this.showEngineDropdown = false;
                },

                // FIX 3: Edit mobil — load engines tanpa reset engine_name yang sudah terpilih
                editCarFromList(index) {
                    this.editIndex = index;
                    this.tempCar = { ...this.cars[index] };
                    this.carSearch = this.cars[index].car_name || '';
                    this.updateAvailableEngines(false); // false = pertahankan engine_name existing
                    this.showForm = true;
                },

                addCarToList() {
                    if (!this.tempCar.car_type_id || !this.tempCar.license_plate) {
                        return Swal.fire('Data Belum Lengkap!', 'Pilih model dan isi nomor polisi dulu.', 'warning');
                    }
                    // FIX 2: Pastikan km_reading tidak negatif sebelum disimpan
                    if (this.tempCar.km_reading < 0 || this.tempCar.km_reading === '') {
                        this.tempCar.km_reading = 0;
                    }
                    const model = this.carTypes.find(t => t.car_type_id == this.tempCar.car_type_id);
                    const carData = { ...this.tempCar, car_name: model ? model.name : '' };
                    if (this.editIndex !== null) {
                        this.cars[this.editIndex] = carData;
                        // FIX 3: Trigger reactivity Alpine setelah splice/assign
                        this.cars = [...this.cars];
                    } else {
                        this.cars.push(carData);
                    }
                    this.closeForm();
                },

                removeCar(index) { this.cars.splice(index, 1); },

                async submitAllData() {
                    if (!this.formData.name || this.cars.length === 0) {
                        return Swal.fire('Error', 'Nama pelanggan dan minimal 1 mobil wajib diisi!', 'error');
                    }
                    Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
                    try {
                        const res = await fetch('/api/customers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.token}`
                            },
                            body: JSON.stringify({ ...this.formData, cars: this.cars })
                        });
                        if (res.ok) {
                            await Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 2000, showConfirmButton: false });
                            window.location.href = "{{ route('pelanggan.index') }}";
                        } else {
                            const err = await res.json();
                            Swal.fire('Gagal!', err.message || 'Terjadi kesalahan.', 'error');
                        }
                    } catch (e) { Swal.fire('Error', 'Koneksi ke API bermasalah.', 'error'); }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection