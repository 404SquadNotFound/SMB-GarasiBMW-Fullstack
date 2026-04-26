{{-- resources/views/layouts/partials/action_bar.blade.php --}}

<div class="flex items-center justify-between mb-5">
    <div class="relative w-[340px]">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#627D98]" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="{{ $placeholder ?? 'Cari data...' }}"
            class="w-full pl-10 pr-4 py-3 bg-white border border-[#D9E2EC] rounded-[10px] outline-none shadow-sm focus:border-bmw-blue focus:ring-1 focus:ring-bmw-blue/20 transition-all text-[14px]">
    </div>

    <div class="flex items-center gap-2.5">
        <button type="button" onclick="toggleModal('{{ $filterModalId ?? 'modalFilter' }}')"
            class="flex items-center gap-2 px-5 py-[11px] bg-white border border-[#D9E2EC] rounded-[10px] font-bold text-[13px] text-[#213F5C] shadow-sm hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 text-[#627D98]" fill="none" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                </path>
            </svg>
            Filter
        </button>

        <div class="relative group">
            <button type="button"
                class="flex items-center gap-2 px-5 py-[11px] bg-white border border-[#D9E2EC] rounded-[10px] font-bold text-[13px] text-[#213F5C] shadow-sm hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 text-[#627D98]" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="absolute right-0 top-full w-40 pt-2.5 hidden group-hover:block z-50">
                <div class="bg-white border border-[#D9E2EC] rounded-[10px] shadow-lg overflow-hidden">
                    <a href="{{ $exportExcelUrl ?? '#' }}"
                        class="block px-4 py-3 text-[13px] text-[#213F5C] hover:bg-gray-50 font-semibold transition-colors">
                        Export Excel (.xlsx)
                    </a>
                    <a href="{{ $exportPdfUrl ?? '#' }}"
                        class="block px-4 py-3 text-[13px] text-[#213F5C] hover:bg-gray-50 font-semibold border-t border-[#F0F4F8] transition-colors">
                        Export PDF (.pdf)
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ $addUrl ?? '#' }}"
            class="flex items-center gap-2 px-5  py-[11px] bg-[#1273EB] text-white rounded-[10px] font-bold text-[13px] shadow-sm hover:bg-[#0E62CC] transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            {{ $btnText ?? 'Tambah' }}
        </a>
    </div>
</div>