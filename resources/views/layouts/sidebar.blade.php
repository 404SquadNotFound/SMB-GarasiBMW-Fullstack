<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

<aside class="w-[280px] flex flex-col flex-shrink-0 min-h-screen shadow-sm">
    <div class="bg-bmw-dark p-[24px] h-[100px] flex items-center gap-[12px]">
        <div class="w-[42px] h-[42px] rounded-full border border-white/20 p-1 flex items-center justify-center bg-white/5">
            <img src="{{ asset('assets/login-assets/login-logo.png') }}" class="w-full h-full object-contain" alt="Logo">
        </div>
        <div class="text-white">
            <h1 class="text-[15px] font-bold leading-tight uppercase tracking-wide">Sistem Manajemen</h1>
            <p class="text-[11px] font-medium text-white/70">Bengkel GARASIBMW</p>
        </div>
    </div>

    <nav class="flex-1 bg-bmw-light-bg p-[16px] space-y-[4px]">

        {{-- Beranda --}}
        <a href="#"
            class="flex items-center gap-[14px] px-[16px] py-[12px] rounded-[10px] text-[#213F5C] hover:bg-white/50 transition-all group">
            <svg class="w-[22px] h-[22px] text-[#213F5C]/70 group-hover:text-bmw-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-bold text-[15px]">Beranda</span>
        </a>

        {{-- Layanan Servis --}}
        <div class="pt-[4px]" x-data="{ open: {{ request()->is('pelanggan*', 'manajemen-servis*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-[16px] py-[12px] rounded-[10px] text-[#213F5C] font-bold">
                <div class="flex items-center gap-[14px]">
                    <svg class="w-[22px] h-[22px] {{ request()->is('pelanggan*', 'manajemen-servis*') ? 'text-bmw-blue' : 'text-[#213F5C]/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-[15px]">Layanan Servis</span>
                </div>
                <svg :class="open ? 'rotate-180' : 'rotate-0'"
                    class="w-[14px] h-[14px] {{ request()->is('pelanggan*', 'manajemen-servis*') ? 'text-bmw-blue' : 'text-[#213F5C]/40' }} transition-transform duration-300"
                    fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-[4px] space-y-[2px]">
                <div class="mx-[12px]">
                    <a href="{{ route('manajemen-servis.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('manajemen-servis*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Penerimaan Servis
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="#"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('#') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Antrian Pengerjaan
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="#"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('#') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Riwayat Transaksi
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('pelanggan.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('pelanggan*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Data Pelanggan
                    </a>
                </div>
            </div>
        </div>

        {{-- Manajemen Stok --}}
        <div class="pt-[4px]" x-data="{ open: {{ request()->is('suku-cadang*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-[16px] py-[12px] rounded-[10px] text-[#213F5C] font-bold">
                <div class="flex items-center gap-[14px]">
                    <svg class="w-[22px] h-[22px] {{ request()->is('suku-cadang*') ? 'text-bmw-blue' : 'text-[#213F5C]/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-[15px]">Manajemen Stok</span>
                </div>
                <svg :class="open ? 'rotate-180' : 'rotate-0'"
                    class="w-[14px] h-[14px] {{ request()->is('suku-cadang*') ? 'text-bmw-blue' : 'text-[#213F5C]/40' }} transition-transform duration-300"
                    fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-[4px] space-y-[2px]">
                <div class="mx-[12px]">
                    <a href="{{ route('suku-cadang.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('suku-cadang*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Suku Cadang
                    </a>
                </div>
            </div>
        </div>

        {{-- Kepegawaian --}}
        <div class="pt-[4px]" x-data="{ open: {{ request()->is('manajemen-pegawai*', 'laporan-absensi*', 'izin-terlambat*', 'payroll*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-[16px] py-[12px] rounded-[10px] text-[#213F5C] font-bold">
                <div class="flex items-center gap-[14px]">
                    <svg class="w-[22px] h-[22px] {{ request()->is('manajemen-pegawai*', 'laporan-absensi*', 'izin-terlambat*', 'payroll*') ? 'text-bmw-blue' : 'text-[#213F5C]/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-[15px]">Kepegawaian</span>
                </div>
                <svg :class="open ? 'rotate-180' : 'rotate-0'"
                    class="w-[14px] h-[14px] {{ request()->is('manajemen-pegawai*', 'laporan-absensi*', 'izin-terlambat*', 'payroll*') ? 'text-bmw-blue' : 'text-[#213F5C]/40' }} transition-transform duration-300"
                    fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-[4px] space-y-[2px]">
                <div class="mx-[12px]">
                    <a href="{{ route('manajemen-pegawai.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('manajemen-pegawai*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Data Pegawai
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('laporan-absensi.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('laporan-absensi*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Laporan Absensi
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('izin-terlambat.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('izin-terlambat*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Pendataan Izin
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('payroll.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('payroll*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Penggajian
                    </a>
                </div>
            </div>
        </div>

        {{-- Master Data --}}
        <div class="pt-[4px]" x-data="{ open: {{ request()->is('jenis-mobil*', 'jenis-mesin*', 'kategori-sparepart*', 'supplier*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-[16px] py-[12px] rounded-[10px] text-[#213F5C] font-bold">
                <div class="flex items-center gap-[14px]">
                    <svg class="w-[22px] h-[22px] {{ request()->is('jenis-mobil*', 'jenis-mesin*', 'kategori-sparepart*', 'supplier*') ? 'text-bmw-blue' : 'text-[#213F5C]/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="text-[15px]">Master Data</span>
                </div>
                <svg :class="open ? 'rotate-180' : 'rotate-0'"
                    class="w-[14px] h-[14px] {{ request()->is('jenis-mobil*', 'jenis-mesin*', 'kategori-sparepart*', 'supplier*') ? 'text-bmw-blue' : 'text-[#213F5C]/40' }} transition-transform duration-300"
                    fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-[4px] space-y-[2px]">
                <div class="mx-[12px]">
                    <a href="{{ route('jenis-mobil.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('jenis-mobil*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Jenis Mobil
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('jenis-mesin.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('jenis-mesin*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Jenis Mesin
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('kategori-sparepart.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('kategori-sparepart*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Kategori Barang
                    </a>
                </div>
                <div class="mx-[12px]">
                    <a href="{{ route('supplier.index') }}"
                        class="block pl-[40px] py-[12px] text-[14px] font-medium transition-colors rounded-[10px]
                        {{ request()->is('supplier*') ? 'text-[#213F5C] font-bold bg-bmw-active-btn' : 'text-[#526D82] hover:text-bmw-blue' }}">
                        Supplier
                    </a>
                </div>
            </div>
        </div>
    </nav>
</aside>