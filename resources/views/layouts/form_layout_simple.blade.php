{{-- resources/views/layouts/form_layout_simple.blade.php --}}
<div class="w-full grid grid-cols-12 gap-6">
    <div class="col-span-9">
        <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
            {{-- Header Section --}}
            <div class="flex items-center gap-3 p-6 border-b border-gray-100 bg-white">
                @yield($iconSlot)
                <h2 class="text-[16px] font-bold text-[#213F5C]">{{ $sectionTitle }}</h2>
            </div>

            {{-- Tempat Isi Konten Form/Detail --}}
            <div class="p-8 space-y-6">
                @yield($contentSlot)
            </div>
        </div>
    </div>

    <div class="col-span-3 space-y-6">
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6 pb-3 border-b border-gray-50">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-bold text-[#213F5C] text-[15px]">Quick Info</h3>
                </div>
                
                <div class="space-y-4">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Created By</p>
                    <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                        <div class="w-10 h-10 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[13px]">
                            E
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[13px] font-bold text-[#213F5C] truncate">Edsel Septa Haryanto</p>
                            <p class="text-[11px] text-gray-400 font-medium">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                    </svg>
                    {{ $submitBtnText ?? 'Simpan Data' }}
                </button>
                
                <a href="{{ $backUrl ?? '#' }}" class="w-full flex items-center justify-center gap-2 py-4 bg-[#FFF5F5] text-[#FF4D4D] border border-[#FFE0E0] rounded-xl font-bold text-[15px] hover:bg-[#FFEBEB] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
            </div>
        @endif
    </div>
</div>