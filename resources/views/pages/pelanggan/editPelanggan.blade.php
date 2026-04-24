@extends('layouts.master')

@section('title', 'Edit Pelanggan')
@section('title_header', 'Data Pelanggan')

{{-- 1. Ikon Header --}}
@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Mengubah Data Pelanggan')

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
                    <input type="text" x-model="formData.phone_number" placeholder="Masukkan nomor telepon"
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
                    <div class="flex flex-col space-y-5">
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Model Mobil</label>
                            <select x-model="tempCar.car_type_id" @change="updateAvailableEngines" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C]">
                                <option value="">-- Pilih Model BMW --</option>
                                <template x-for="type in carTypes" :key="type.car_type_id">
                                    <option :value="type.car_type_id" x-text="type.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
                            <select x-model="tempCar.engine_name" :disabled="!tempCar.car_type_id" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] disabled:bg-gray-50">
                                <option value="">-- Pilih Mesin --</option>
                                <template x-for="eng in availableEngines" :key="eng">
                                    <option :value="eng" x-text="eng"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Kode Transmisi</label>
                            <input type="text" x-model="tempCar.transmission" placeholder="A4Q" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Tahun Mobil</label>
                            <input type="number" x-model="tempCar.year" placeholder="1945" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
                            <input type="text" x-model="tempCar.license_plate" placeholder="B 1040 JAW" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-[#213F5C] mb-2">KM Masuk Bengkel</label>
                            <input type="number" x-model="tempCar.km_reading" placeholder="6969" class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="addCarToList" class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8]" x-text="editIndex !== null ? 'Simpan Perubahan' : 'Simpan'"></button>
                        <button type="button" @click="closeForm()" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-xl font-bold text-[14px] hover:bg-gray-50">Batal</button>
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
        'submitBtnText' => 'Update Data',
        'submitBtnId' => 'submitUpdateBtn',
        'submitBtnIcon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>'
    ])

    <script>
        function customerForm() {
            return {
                formData: { name: '', phone_number: '', address: '' },
                tempCar: { car_type_id: '', engine_name: '', transmission: '', year: '', license_plate: '', km_reading: '' },
                cars: [],
                carTypes: [],
                availableEngines: [],
                showForm: false,
                editIndex: null,
                token: localStorage.getItem('access_token'),
                id: window.location.pathname.split('/').pop(),

                async init() {
                    try {
                        // 1. Load Car Types
                        const resTypes = await fetch('/api/car-types', {
                            headers: { 'Authorization': `Bearer ${this.token}`, 'Accept': 'application/json' }
                        });
                        const resultTypes = await resTypes.json();
                        this.carTypes = resultTypes.data.data || resultTypes.data || [];

                        // 2. Load Data Pelanggan Existing
                        const resCust = await fetch(`/api/customers/${this.id}`, {
                            headers: { 'Authorization': `Bearer ${this.token}`, 'Accept': 'application/json' }
                        });
                        const resultCust = await resCust.json();

                        if (resCust.ok) {
                            const data = resultCust.data;
                            this.formData.name = data.name;
                            this.formData.phone_number = data.phone_number;
                            this.formData.address = data.address;

                            this.cars = data.vehicles.map(v => ({
                                car_type_id: v.car_type_id,
                                car_name: v.model,
                                engine_name: v.engine_code,
                                transmission: v.production_code,
                                year: v.production_code,
                                license_plate: v.license_plate,
                                km_reading: v.odometer
                            }));
                        }
                    } catch (e) { console.error("Gagal load data edit", e); }

                    // Link tombol sidebar ke fungsi update
                    document.getElementById('submitBtnApi').onclick = (e) => {
                        e.preventDefault();
                        this.submitUpdateData();
                    };
                },

                updateAvailableEngines() {
                    const selected = this.carTypes.find(t => t.car_type_id == this.tempCar.car_type_id);
                    this.availableEngines = (selected && selected.engine_code) ? selected.engine_code.split(',').map(s => s.trim()) : [];
                    this.tempCar.engine_name = '';
                },

                openForm() {
                    this.editIndex = null;
                    this.tempCar = { car_type_id: '', engine_name: '', transmission: '', year: '', license_plate: '', km_reading: '' };
                    this.showForm = true;
                },

                closeForm() { this.showForm = false; this.editIndex = null; },

                editCarFromList(index) {
                    this.editIndex = index;
                    this.tempCar = { ...this.cars[index] };
                    this.updateAvailableEngines();
                    this.showForm = true;
                },

                addCarToList() {
                    if (!this.tempCar.car_type_id || !this.tempCar.license_plate) return Swal.fire('Data Belum Lengkap!', 'Cek isian lu brok.', 'warning');
                    const model = this.carTypes.find(t => t.car_type_id == this.tempCar.car_type_id);
                    const carData = { ...this.tempCar, car_name: model ? model.name : '' };
                    if (this.editIndex !== null) { this.cars[this.editIndex] = carData; }
                    else { this.cars.push(carData); }
                    this.closeForm();
                },

                removeCar(index) { this.cars.splice(index, 1); },

                async submitUpdateData() {
                    if (!this.formData.name || this.cars.length === 0) return Swal.fire('Error', 'Nama dan minimal 1 mobil wajib ada!', 'error');
                    Swal.fire({ title: 'Mengupdate...', didOpen: () => Swal.showLoading() });
                    try {
                        const res = await fetch(`/api/customers/${this.id}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${this.token}` },
                            body: JSON.stringify({ ...this.formData, cars: this.cars })
                        });
                        if (res.ok) {
                            await Swal.fire({ icon: 'success', title: 'Berhasil diupdate!', timer: 2000, showConfirmButton: false });
                            window.location.href = `/pelanggan/detail/${this.id}`;
                        } else {
                            const err = await res.json(); Swal.fire('Gagal!', err.message, 'error');
                        }
                    } catch (e) { Swal.fire('Error!', 'Koneksi bermasalah', 'error'); }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection