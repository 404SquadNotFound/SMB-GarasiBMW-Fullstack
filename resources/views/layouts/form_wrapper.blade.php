{{-- resources/views/layouts/form_wrapper.blade.php --}}
<div class="block w-full space-y-6">
    {{-- Card Judul --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm w-full">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @yield('form_icon')
                <div>
                    <h1 class="text-xl font-bold text-[#213F5C]">@yield('form_title')</h1>
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

    {{-- Form Content --}}
    <div class="grid grid-cols-12 gap-6 pb-10 w-full">
        @csrf
        @if(isset($method))
            @method($method)
        @endif

        {{-- Kolom Kiri --}}
        <div class="col-span-9">
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">{{ $sectionTitle ?? 'Informasi Data' }}</h2>
                </div>

                <div class="p-8 space-y-6">
                    @yield('form_fields')
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-span-3 space-y-6">
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6 pb-3 border-b border-gray-50">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-bold text-[#213F5C] text-[15px]">Quick Info</h3>
                </div>

                <div class="space-y-4">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Operator</p>
                    <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                        <div
                            class="user-initial-box w-10 h-10 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[13px]">
                            ?
                        </div>
                        <div class="overflow-hidden">
                            <p class="user-name-box text-[13px] font-bold text-[#213F5C] truncate">Loading...</p>
                            <p class="user-role-box text-[11px] text-gray-400 font-medium italic">...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <button type="button" id="submitBtnApi"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    {{ $submitBtnText ?? 'Simpan Data' }}
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const name = localStorage.getItem('user_name') || 'User';
        const role = localStorage.getItem('user_role') || 'Staff';

        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
        document.querySelectorAll('.user-initial-box').forEach(el => el.innerText = name.charAt(0).toUpperCase());
    });
</script>