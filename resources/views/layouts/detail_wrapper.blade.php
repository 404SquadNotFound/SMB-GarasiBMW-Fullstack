{{-- resources/views/layouts/detail_wrapper.blade.php --}}
<div class="block w-full space-y-6">
    {{-- Card Judul --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm w-full">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @yield('detail_icon')
                <div>
                    <h1 class="text-xl font-bold text-[#213F5C]">@yield('detail_title')</h1>
                </div>
            </div>

            <a href="{{ $backUrl ?? '#' }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-[#213F5C] font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
                Kembali ke List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 pb-10 w-full">
        <div class="col-span-9">
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">{{ $sectionTitle ?? 'Detail Informasi' }}</h2>
                </div>

                <div class="p-8 space-y-6">
                    @yield('detail_content')
                </div>
            </div>
        </div>

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

                <div class="space-y-5">
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Created By</p>
                        <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                            <div
                                class="user-initial-box w-10 h-10 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[14px]">
                                ?
                            </div>
                            <div class="overflow-hidden">
                                <p class="user-name-box text-[13px] font-bold text-[#213F5C] truncate">Loading...</p>
                                <p class="user-role-box text-[11px] text-gray-400 font-medium italic">...</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-gray-50">
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Created Date</p>
                            <p class="created-date-text text-[13px] font-bold text-[#213F5C]">Loading...</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Last Updated</p>
                            <p class="updated-date-text text-[13px] font-bold text-[#213F5C]">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ $editUrl ?? '#' }}"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10">
                        </path>
                    </svg>
                    Edit Data
                </a>

                <button type="button" id="btnHapusData"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FF4D4D] text-white rounded-xl font-bold text-[15px] hover:bg-[#E63939] transition-all shadow-lg shadow-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.34 9m-4.72 0l-.34-9m9.27-2.31a44.59 44.59 0 00-4.53-.13m-10.83 0a44.59 44.59 0 00-4.53.13m15.23 0l-1.23-1.87a3.375 3.375 0 00-3.06-1.55h-4.06a3.375 3.375 0 00-3.06 1.55l-1.23 1.87m12.13 0H6.25">
                        </path>
                    </svg>
                    Hapus Data
                </button>

                <a href="{{ $backUrl ?? '#' }}"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FFF5F5] text-[#FF4D4D] border border-[#FFE0E0] rounded-xl font-bold text-[15px] hover:bg-[#FFEBEB] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PENGISI DATA OTOMATIS --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Ambil data user dari localStorage
        const name = localStorage.getItem('user_name') || 'User';
        const role = localStorage.getItem('user_role') || 'Administrator';

        // Isi ke elemen-elemen di Quick Info
        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role.replace('_', ' '));
        document.querySelectorAll('.user-initial-box').forEach(el => el.innerText = name.charAt(0).toUpperCase());
    });
</script>