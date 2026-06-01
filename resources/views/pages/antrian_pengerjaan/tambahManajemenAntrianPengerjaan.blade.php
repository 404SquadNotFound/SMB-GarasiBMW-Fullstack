{{-- resources/views/pages/antrian_pengerjaan/tambahManajemenAntrianPengerjaan.blade.php --}}
@extends('layouts.master')

@section('title', 'Tambah Antrian Pengerjaan')
@section('title_header', 'Layanan Servis | Antrian Pengerjaan')

@section('content')
    <div class="block w-full space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm w-full">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-[#213F5C]">Menambahkan Mobil Masuk</h1>
            </div>
        </div>

        {{-- Grid 2 kolom --}}
        <div class="grid grid-cols-12 gap-6 pb-10 w-full" x-data="antrianTambahForm()" x-init="init()">

            {{-- Kolom Kiri --}}
            <div class="col-span-9 space-y-6">

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
                            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Cari Pelanggan <span
                                    class="text-red-500">*</span></label>
                            <div class="relative" @click.stop id="customerDropdownWrapper">
                                <div class="relative">
                                    <input type="text" x-model="customerSearch" @input="filterCustomers"
                                        @focus="showCustomerDropdown = true; if(!customersData.length) loadCustomers()"
                                        placeholder="Ketik nama atau nomor telepon pelanggan..." autocomplete="off"
                                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300 pr-10"
                                        :class="showCustomerDropdown ? 'border-[#1273EB]' : ''">
                                    <span x-show="selectedCustomer" @click="clearCustomer()"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-400 text-lg leading-none">×</span>
                                </div>
                                <div x-show="showCustomerDropdown && filteredCustomers.length > 0" x-cloak
                                    class="absolute z-50 left-0 right-0 mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden dropdown-scroll-wrap">
                                    <div class="max-h-[260px] overflow-y-auto dropdown-scroll">
                                        <template x-for="c in filteredCustomers" :key="c.id">
                                            <div @click="selectCustomer(c)"
                                                class="px-5 py-3.5 hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors">
                                                <p class="text-[14px] font-bold text-[#213F5C]" x-text="c.nama"></p>
                                                <p class="text-[12px] text-gray-400"
                                                    x-text="c.telepon + (c.vehicles?.length ? ' • ' + c.vehicles.length + ' kendaraan' : '')">
                                                </p>
                                            </div>
                                        </template>
                                        <div x-show="filteredCustomers.length === 0"
                                            class="p-4 text-center text-[13px] text-gray-400">Pelanggan tidak ditemukan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info pelanggan terpilih (readonly) --}}
                        <div x-show="selectedCustomer" x-cloak
                            class="bg-[#F0F7FF] border border-[#B1D3FF] rounded-xl p-4 space-y-1">
                            <p class="text-[13px] font-bold text-[#1273EB]" x-text="selectedCustomer?.nama"></p>
                            <p class="text-[12px] text-gray-500"
                                x-text="selectedCustomer ? '📞 ' + selectedCustomer.telepon : ''"></p>
                            <p class="text-[12px] text-gray-400"
                                x-text="selectedCustomer ? '📍 ' + (selectedCustomer.alamat || '-') : ''"></p>
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
                        <div>
                            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pilih Kendaraan <span
                                    class="text-red-500">*</span></label>

                            {{-- Hint jika belum pilih customer --}}
                            <div x-show="!selectedCustomer"
                                class="py-3 px-4 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-[13px] text-gray-400 text-center">
                                Pilih pelanggan terlebih dahulu
                            </div>

                            {{-- Dropdown kendaraan --}}
                            <div x-show="selectedCustomer" x-cloak class="relative" @click.stop>
                                <input type="text" x-model="vehicleSearch" @input="filterVehicles"
                                    @focus="showVehicleDropdown = true" placeholder="Cari model atau nopol kendaraan..."
                                    autocomplete="off"
                                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300 pr-10"
                                    :class="showVehicleDropdown ? 'border-[#1273EB]' : ''">
                                <span x-show="selectedVehicle" @click="clearVehicle()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-400 text-lg leading-none">×</span>
                                <div x-show="showVehicleDropdown" x-cloak
                                    class="absolute z-50 left-0 right-0 mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                                    <div class="max-h-[220px] overflow-y-auto dropdown-scroll">
                                        <template x-for="v in filteredVehicles" :key="v.id">
                                            <div @click="selectVehicle(v)"
                                                class="px-5 py-3.5 hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors">
                                                <p class="text-[14px] font-bold text-[#213F5C]" x-text="v.model"></p>
                                                <p class="text-[12px] text-gray-400"
                                                    x-text="v.license_plate + (v.engine_code ? ' • ' + v.engine_code : '')">
                                                </p>
                                            </div>
                                        </template>
                                        <div x-show="filteredVehicles.length === 0"
                                            class="p-4 text-center text-[13px] text-gray-400">Tidak ada kendaraan ditemukan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info kendaraan terpilih (readonly) --}}
                        <div x-show="selectedVehicle" x-cloak
                            class="bg-[#F0F7FF] border border-[#B1D3FF] rounded-xl p-4 space-y-1">
                            <p class="text-[13px] font-bold text-[#1273EB]" x-text="selectedVehicle?.model"></p>
                            <p class="text-[12px] text-gray-500"
                                x-text="selectedVehicle ? '🚗 ' + selectedVehicle.license_plate : ''"></p>
                            <p class="text-[12px] text-gray-400"
                                x-text="selectedVehicle?.engine_code ? '⚙️ ' + selectedVehicle.engine_code : ''"></p>
                        </div>

                        {{-- KM Masuk --}}
                        <div>
                            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">KM Masuk</label>
                            <input type="number" id="km_masuk" x-model="kmMasuk" placeholder="Contoh: 15000"
                                min="0"
                                @keydown="if(['-','e','E','+'].includes($event.key)) $event.preventDefault()"
                                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[14px] text-[#213F5C] placeholder-gray-300">
                        </div>
                    </div>
                </div>

                {{-- =========================================================
             BOX 3 : Cabang Yang Digunakan
            ========================================================= --}}
                <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm">
                    <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                        <div class="w-8 h-8 bg-[#F1F5F9] rounded-lg flex items-center justify-center text-[#213F5C]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-[16px] font-bold text-[#213F5C]">Cabang Yang Digunakan</h2>
                    </div>

                    <div class="p-8 space-y-5">
                        <div>
                            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pilih Cabang <span
                                    class="text-red-500">*</span></label>
                            <div class="relative" @click.stop>
                                <input type="text" readonly :value="selectedCabang ? selectedCabang.label : ''"
                                    @click="showCabangDropdown = !showCabangDropdown"
                                    placeholder="Pilih cabang bengkel..."
                                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] cursor-pointer focus:border-[#1273EB]"
                                    :class="showCabangDropdown ? 'border-[#1273EB] ring-2 ring-[#1273EB]/10' : ''">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 transition-transform duration-200"
                                        :class="showCabangDropdown ? 'rotate-180' : ''" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                                <div x-show="showCabangDropdown" x-cloak
                                    class="absolute z-50 w-full mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                                    <div class="max-h-[220px] overflow-y-auto dropdown-scroll">
                                        <template x-for="cab in cabangOptions" :key="cab.value">
                                            <div @click="selectCabang(cab)"
                                                class="px-5 py-3.5 text-[14px] font-semibold text-[#213F5C] hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors"
                                                :class="selectedCabang?.value === cab.value ? 'bg-[#EAF2FF] text-[#1273EB]' : ''"
                                                x-text="cab.label">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info cabang terpilih --}}
                        <div x-show="selectedCabang" x-cloak class="bg-[#F0F7FF] border border-[#B1D3FF] rounded-xl p-4">
                            <p class="text-[13px] font-bold text-[#1273EB]"
                                x-text="selectedCabang ? '📍 ' + selectedCabang.label : ''"></p>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
             BOX 4 : Penggunaan Suku Cadang
            ========================================================= --}}
                <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm">
                    <div class="flex items-center gap-3 p-6 border-b border-gray-100">
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

                    <div class="p-8 flex flex-col gap-4">

                        {{-- Daftar suku cadang yang sudah ditambah --}}
                        <template x-for="item in sukuCadangItems" :key="item.id">
                            <div
                                class="flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[12px] border border-[#E5E9F2]">
                                <div>
                                    <p class="text-[13px] font-bold text-[#213F5C]" x-text="item.nama"></p>
                                    <p class="text-[11px] text-gray-400 mt-0.5" x-text="item.deskripsi || '-'"></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-[12px] font-bold text-[#213F5C]"
                                        x-text="item.jumlah + ' pcs'"></span>
                                    <span class="text-[12px] text-gray-400" x-text="item.harga"></span>
                                    <button type="button" @click="hapusSukuCadang(item.id)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Tombol Tambah --}}
                        <button type="button" x-show="!showFormSukuCadang" @click="openSukuCadangForm()"
                            class="w-full py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] flex items-center justify-center gap-2 shadow-lg shadow-blue-100 hover:bg-[#0E59B8] transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3"
                                viewBox="0 0 24 24">
                                <path d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            Tambah Suku Cadang
                        </button>

                        {{-- =====================================================
                     FORM SUKU CADANG — 3 step bertahap
                    ===================================================== --}}
                        <div x-show="showFormSukuCadang" x-cloak
                            class="bg-[#EEF5FF] border-2 border-[#B8D4FF] rounded-2xl p-6 space-y-5">

                            {{-- Header form --}}
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#4A7CC7]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-[14px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</span>
                            </div>

                            {{-- ── STEP 1: Cari Nama Barang ── --}}
                            <div class="space-y-2">
                                <label class="block text-[13px] font-semibold text-[#213F5C]">
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-[#1273EB] text-white rounded-full text-[10px] font-bold mr-1">1</span>
                                    Cari Nama Barang
                                </label>
                                <div class="relative" @click.stop>
                                    <input type="text" x-model="barangSearch" @input="onBarangInput"
                                        @focus="onBarangFocus" placeholder="Ketik nama atau kode barang..."
                                        autocomplete="off"
                                        class="w-full px-5 py-3 bg-white border border-white rounded-2xl outline-none text-[13px] text-[#213F5C] placeholder-gray-300 pr-9 shadow-sm transition-all"
                                        :class="showBarangDropdown ? 'border-[#1273EB] ring-2 ring-[#1273EB]/20' : ''">
                                    <span x-show="selectedBarang" @click.stop="clearBarang()"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-400 cursor-pointer text-xl leading-none select-none">×</span>
                                    {{-- Dropdown --}}
                                    <div x-show="showBarangDropdown" x-cloak
                                        class="absolute z-[60] left-0 right-0 mt-1 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                                        <div class="max-h-[260px] overflow-y-auto dropdown-scroll">
                                            <div x-show="filteredBarang.length === 0"
                                                class="p-4 text-center text-gray-400 text-[13px]"
                                                x-text="barangList.length === 0 ? 'Memuat data...' : 'Barang tidak ditemukan'">
                                            </div>
                                            <template x-for="b in filteredBarang" :key="b.id">
                                                <div @click.stop="selectBarang(b)"
                                                    class="px-5 py-3.5 hover:bg-[#F0F7FF] cursor-pointer border-b border-gray-50 last:border-0 transition-colors"
                                                    :class="selectedBarang?.id === b.id ? 'bg-[#EAF2FF]' : ''">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-[13px] font-semibold text-[#213F5C]"
                                                            x-text="b.nama"></span>
                                                        <span class="text-[12px] font-bold text-[#1273EB]"
                                                            x-text="b.harga"></span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center mt-0.5 text-[11px] text-gray-400">
                                                        <span>Sisa stok: <span class="font-semibold text-[#213F5C]"
                                                                x-text="b.stok"></span></span>
                                                        <span x-text="'Supplier: ' + (b.supplier || '-')"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                {{-- Info barang terpilih --}}
                                <div x-show="selectedBarang" x-cloak
                                    class="px-4 py-3 bg-white rounded-2xl shadow-sm border border-[#E5E9F2]">
                                    <p class="text-[13px] font-bold text-[#213F5C]" x-text="selectedBarang?.nama"></p>
                                    <p class="text-[11px] text-gray-400 mt-0.5"
                                        x-text="'Supplier: ' + (selectedBarang?.supplier || '-') + '  ·  Total stok: ' + (selectedBarang?.stok ?? 0) + ' pcs'">
                                    </p>
                                </div>
                            </div>

                            {{-- ── STEP 2: Pilih Stok Tersedia (muncul setelah pilih barang) ── --}}
                            <div x-show="selectedBarang" x-cloak class="space-y-2">
                                <label class="block text-[13px] font-semibold text-[#213F5C]">
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-[#1273EB] text-white rounded-full text-[10px] font-bold mr-1">2</span>
                                    Pilih Stok Tersedia (Tanggal Masuk &amp; Harga)
                                </label>

                                <div x-show="loadingBatch"
                                    class="px-5 py-3 bg-white rounded-2xl text-[13px] text-gray-400 shadow-sm">
                                    Memuat stok tersedia...
                                </div>

                                <div x-show="!loadingBatch" class="space-y-2">
                                    <template x-for="batch in batchList" :key="batch.id">
                                        <div @click="selectBatch(batch)"
                                            class="flex items-center gap-3 px-5 py-3.5 bg-white rounded-2xl cursor-pointer shadow-sm transition-all border-2"
                                            :class="selectedBatch?.id === batch.id ?
                                                'border-[#1273EB] bg-[#EAF2FF]' :
                                                'border-transparent hover:border-[#B8D4FF]'">
                                            {{-- Custom radio --}}
                                            <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
                                                :class="selectedBatch?.id === batch.id ? 'border-[#1273EB] bg-[#1273EB]' :
                                                    'border-gray-300 bg-white'">
                                                <div x-show="selectedBatch?.id === batch.id"
                                                    class="w-2 h-2 rounded-full bg-white"></div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[13px] font-bold text-[#213F5C]"
                                                    x-text="batch.tanggal_masuk"></p>
                                                <p class="text-[11px] text-gray-400 mt-0.5"
                                                    x-text="'HPP: ' + batch.harga_beli + '  ·  Jual: ' + batch.harga_jual + '  ·  Sisa: ' + batch.sisa + ' pcs'">
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="batchList.length === 0"
                                        class="px-5 py-4 bg-white rounded-2xl text-[13px] text-gray-400 text-center shadow-sm">
                                        Tidak ada stok tersedia untuk barang ini
                                    </div>
                                </div>
                            </div>

                            {{-- ── STEP 3: Jumlah (muncul setelah pilih batch) ── --}}
                            <div x-show="selectedBatch" x-cloak class="space-y-2">
                                <label class="block text-[13px] font-semibold text-[#213F5C]">
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-[#1273EB] text-white rounded-full text-[10px] font-bold mr-1">3</span>
                                    Jumlah Stok Yang Digunakan
                                </label>
                                <input type="number" x-model="inputJumlah" :max="selectedBatch?.sisa ?? 9999"
                                    placeholder="Contoh : 1" min="1"
                                    @keydown="if(['-','e','E','+'].includes($event.key)) $event.preventDefault()"
                                    class="w-full px-5 py-3 bg-white border border-white rounded-2xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/20 transition-all text-[13px] text-[#213F5C] placeholder-gray-300 shadow-sm">
                                <p class="text-[11px] text-gray-400 mt-1 ml-1"
                                    x-text="selectedBatch ? 'Maksimal ' + selectedBatch.sisa + ' pcs dari stok ini' : ''">
                                </p>
                            </div>

                            {{-- ── Tombol Simpan / Batal ── --}}
                            <div class="flex gap-3 pt-1">
                                <button type="button" @click="simpanSukuCadang()"
                                    class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-2xl font-bold text-[14px] hover:bg-[#0E59B8] shadow-md shadow-blue-200 transition-all">
                                    Simpan
                                </button>
                                <button type="button" @click="batalSukuCadang()"
                                    class="px-8 py-3.5 bg-white border border-gray-200 text-gray-500 rounded-2xl font-bold text-[14px] hover:bg-gray-50 transition-all shadow-sm">
                                    Batal
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-span-3 space-y-6">

                {{-- Quick Info --}}
                <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b border-gray-50">
                        <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-bold text-[#213F5C] text-[15px]">Quick Info</h3>
                    </div>
                    <div class="space-y-4">
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                        <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                            <div class="w-10 h-10 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[13px]"
                                x-text="userInitial">?</div>
                            <div class="overflow-hidden">
                                <p class="text-[13px] font-bold text-[#213F5C] truncate" x-text="userName">Loading...</p>
                                <p class="text-[11px] text-gray-400 font-medium italic" x-text="userRole">...</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm space-y-3">
                    <button type="button" @click="submitData()"
                        class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                        </svg>
                        Simpan Data
                    </button>

                    <a href="{{ route('antrian-pengerjaan.index') }}"
                        class="w-full flex items-center justify-center gap-2 py-4 bg-white text-gray-500 border border-gray-200 rounded-xl font-bold text-[15px] hover:bg-gray-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .dropdown-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown-scroll::-webkit-scrollbar-track {
            background: transparent;
            margin: 4px 0;
        }

        .dropdown-scroll::-webkit-scrollbar-thumb {
            background: #D1E4FF;
            border-radius: 99px;
        }

        .dropdown-scroll::-webkit-scrollbar-thumb:hover {
            background: #1273EB;
        }
    </style>

    <script>
        function antrianTambahForm() {
            return {
                token: localStorage.getItem('access_token'),
                isDirty: false,

                // User info
                userName: 'Loading...',
                userRole: '...',
                userInitial: '?',

                // Customer
                customerSearch: '',
                customersData: [],
                filteredCustomers: [],
                showCustomerDropdown: false,
                selectedCustomer: null,
                customerDebounce: null,

                // Vehicle
                vehicleSearch: '',
                currentVehicles: [],
                filteredVehicles: [],
                showVehicleDropdown: false,
                selectedVehicle: null,

                // KM
                kmMasuk: '',

                // Cabang
                cabangOptions: [{
                        value: '1',
                        label: 'Pelajar Pejuang'
                    },
                    {
                        value: '2',
                        label: 'Ahmad Yani'
                    },
                ],
                showCabangDropdown: false,
                selectedCabang: null,

                // Barang (master nama barang)
                barangList: [],
                barangSearch: '',
                filteredBarang: [],
                showBarangDropdown: false,
                selectedBarang: null,

                // Batch / stok tersedia
                batchList: [],
                selectedBatch: null,
                loadingBatch: false,

                inputJumlah: '',
                showFormSukuCadang: false,
                sukuCadangItems: [],

                // ── init ──────────────────────────────────────────────────
                async init() {
                    this.userName = localStorage.getItem('user_name') || 'User';
                    this.userRole = localStorage.getItem('user_role') || 'Staff';
                    this.userInitial = this.userName.charAt(0).toUpperCase();

                    await this.loadBarangList();

                    // Global click-outside: tutup semua dropdown
                    document.addEventListener('click', () => {
                        this.showCustomerDropdown = false;
                        this.showVehicleDropdown = false;
                        this.showCabangDropdown = false;
                        this.showBarangDropdown = false;
                    });

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') e.preventDefault();
                    });

                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty) {
                            e.preventDefault();
                            e.returnValue = '';
                        }
                    });
                },

                // ── Customer ──────────────────────────────────────────────
                async loadCustomers(keyword = '') {
                    try {
                        const params = keyword ? `?search=${encodeURIComponent(keyword)}` : '';
                        const res = await fetch(`/api/customers-for-antrian${params}`, {
                            headers: {
                                'Authorization': `Bearer ${this.token}`
                            }
                        });
                        const result = await res.json();
                        this.customersData = res.ok ? (result.data ?? []) : [];
                    } catch {
                        this.customersData = [];
                    }
                    this.filteredCustomers = this.customersData;
                },

                filterCustomers() {
                    const kw = this.customerSearch.trim().toLowerCase();
                    this.showCustomerDropdown = true;

                    if (!kw) {
                        this.filteredCustomers = this.customersData;
                        return;
                    }

                    const local = this.customersData.filter(c =>
                        c.nama.toLowerCase().includes(kw) || c.telepon.toLowerCase().includes(kw)
                    );

                    if (local.length > 0) {
                        this.filteredCustomers = local;
                    } else {
                        clearTimeout(this.customerDebounce);
                        this.customerDebounce = setTimeout(async () => {
                            await this.loadCustomers(kw);
                        }, 350);
                    }
                },

                selectCustomer(c) {
                    this.selectedCustomer = c;
                    this.customerSearch = `${c.nama} - ${c.telepon}`;
                    this.showCustomerDropdown = false;
                    this.currentVehicles = c.vehicles ?? [];
                    this.filteredVehicles = this.currentVehicles;
                    this.clearVehicle();
                    this.isDirty = true;
                },

                clearCustomer() {
                    this.selectedCustomer = null;
                    this.customerSearch = '';
                    this.showCustomerDropdown = false;
                    this.currentVehicles = [];
                    this.filteredVehicles = [];
                    this.clearVehicle();
                },

                // ── Vehicle ───────────────────────────────────────────────
                filterVehicles() {
                    const kw = this.vehicleSearch.trim().toLowerCase();
                    this.showVehicleDropdown = true;
                    if (!kw) {
                        this.filteredVehicles = this.currentVehicles;
                        return;
                    }
                    this.filteredVehicles = this.currentVehicles.filter(v =>
                        (v.model || '').toLowerCase().includes(kw) ||
                        (v.license_plate || '').toLowerCase().includes(kw)
                    );
                },

                selectVehicle(v) {
                    this.selectedVehicle = v;
                    this.vehicleSearch = `${v.model} - ${v.license_plate}`;
                    this.showVehicleDropdown = false;
                    if (!this.kmMasuk && v.odometer) {
                        this.kmMasuk = v.odometer;
                    }
                    this.isDirty = true;
                },

                clearVehicle() {
                    this.selectedVehicle = null;
                    this.vehicleSearch = '';
                    this.showVehicleDropdown = false;
                    this.filteredVehicles = this.currentVehicles;
                },

                // ── Cabang ────────────────────────────────────────────────
                selectCabang(cab) {
                    this.selectedCabang = cab;
                    this.showCabangDropdown = false;
                    this.isDirty = true;
                },

                // ── Barang ────────────────────────────────────────────────
                async loadBarangList() {
                    try {
                        const res = await fetch('/api/spareparts-for-antrian', {
                            headers: {
                                'Authorization': `Bearer ${this.token}`
                            }
                        });
                        const result = await res.json();
                        if (res.ok && result.data) {
                            this.barangList = result.data;
                            this.filteredBarang = result.data;
                        }
                    } catch (e) {
                        console.error('Gagal load barang:', e);
                    }
                },

                onBarangFocus() {
                    this.filteredBarang = this.barangList;
                    this.showBarangDropdown = true;
                },

                onBarangInput() {
                    const kw = this.barangSearch.trim().toLowerCase();
                    this.showBarangDropdown = true;
                    this.filteredBarang = kw ?
                        this.barangList.filter(b =>
                            b.nama.toLowerCase().includes(kw) ||
                            (b.kode ?? '').toLowerCase().includes(kw)) :
                        this.barangList;
                },

                selectBarang(b) {
                    this.selectedBarang = b;
                    this.barangSearch = b.nama;
                    this.showBarangDropdown = false;
                    this.selectedBatch = null;
                    this.batchList = [];
                    this.inputJumlah = '';
                    this.loadBatchList(b.id);
                },

                clearBarang() {
                    this.selectedBarang = null;
                    this.barangSearch = '';
                    this.filteredBarang = this.barangList;
                    this.showBarangDropdown = false;
                    this.selectedBatch = null;
                    this.batchList = [];
                    this.inputJumlah = '';
                },

                // ── Batch / Stok tersedia ─────────────────────────────────
                async loadBatchList(sparepartId) {
                    this.loadingBatch = true;
                    this.batchList = [];
                    try {
                        const res = await fetch(`/api/spareparts/${sparepartId}/stocks`, {
                            headers: {
                                'Authorization': `Bearer ${this.token}`
                            }
                        });
                        const result = await res.json();
                        console.log('STOCKS response:', JSON.stringify(result, null, 2)); // <-- lihat ini
                        const raw = result.data ?? result.stocks ?? result ?? [];
                        this.batchList = (Array.isArray(raw) ? raw : []).map(b => ({
                            id: b.id,
                            tanggal_masuk: b.tanggal_masuk ?? b.date ?? b.created_at ?? '-',
                            harga_beli: b.harga_beli ?? b.hpp ?? b.purchase_price ?? b.cost_price ?? '-',
                            harga_jual: b.harga_jual ?? b.harga ?? b.selling_price ?? b.price ?? '-',
                            sisa: b.sisa ?? b.stock ?? b.quantity ?? b.qty ?? 0,
                        }));
                        console.log('batchList mapped:', this.batchList); // <-- dan ini
                    } catch (e) {
                        console.error('Gagal load stocks:', e);
                    }
                    this.loadingBatch = false;
                },

                selectBatch(batch) {
                    this.selectedBatch = batch;
                    this.inputJumlah = '';
                },

                // ── Form suku cadang ──────────────────────────────────────
                openSukuCadangForm() {
                    this.showFormSukuCadang = true;
                    this.inputJumlah = '';
                    this.clearBarang();
                },

                batalSukuCadang() {
                    this.showFormSukuCadang = false;
                    this.clearBarang();
                },

                simpanSukuCadang() {
                    if (!this.selectedBarang) {
                        Swal.fire('Oops!', 'Pilih nama barang terlebih dahulu!', 'warning');
                        return;
                    }
                    if (!this.selectedBatch) {
                        Swal.fire('Oops!', 'Pilih stok tersedia (tanggal masuk & harga)!', 'warning');
                        return;
                    }
                    const jumlah = parseInt(this.inputJumlah) || 1;
                    if (jumlah > this.selectedBatch.sisa) {
                        Swal.fire('Oops!', `Jumlah melebihi sisa stok (${this.selectedBatch.sisa} pcs)!`, 'warning');
                        return;
                    }
                    this.sukuCadangItems.push({
                        id: Date.now(),
                        sparepart_id: this.selectedBarang.id,
                        batch_id: this.selectedBatch.id,
                        nama: this.selectedBarang.nama,
                        deskripsi: 'Masuk: ' + this.selectedBatch.tanggal_masuk,
                        harga: this.selectedBatch.harga_jual,
                        jumlah,
                    });
                    this.showFormSukuCadang = false;
                    this.clearBarang();
                    this.isDirty = true;
                },

                hapusSukuCadang(id) {
                    this.sukuCadangItems = this.sukuCadangItems.filter(i => i.id !== id);
                    this.isDirty = true;
                },

                // ── Submit ────────────────────────────────────────────────
                async submitData() {
                    if (!this.selectedCustomer) {
                        Swal.fire('Oops!', 'Pilih pelanggan terlebih dahulu!', 'warning');
                        return;
                    }
                    if (!this.selectedVehicle) {
                        Swal.fire('Oops!', 'Pilih kendaraan pelanggan!', 'warning');
                        return;
                    }

                    Swal.fire({
                        title: 'Menyimpan data...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const items = this.sukuCadangItems
                        .filter(sc => sc.sparepart_id)
                        .map(sc => ({
                            sparepart_id: sc.sparepart_id,
                            quantity: parseInt(sc.jumlah) || 1,
                        }));

                    const payload = {
                        customer_id: this.selectedCustomer.id,
                        vehicle_id: this.selectedVehicle.id,
                        km_masuk: this.kmMasuk ? parseInt(this.kmMasuk) : null,
                        cabang_id: this.selectedCabang ? this.selectedCabang.value : null,
                        items,
                    };

                    try {
                        const res = await fetch('/api/transactions', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${this.token}`,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        });
                        const result = await res.json();
                        if (res.ok && result.status === 'success') {
                            this.isDirty = false;
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                        } else {
                            Swal.fire('Gagal!', result.message ?? 'Terjadi kesalahan.', 'error');
                        }
                    } catch (err) {
                        console.error(err);
                        Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
                    }
                },
            };
        }
    </script>
@endsection
