{{-- resources/views/layouts/partials/table_wrapper.blade.php --}}
<div class="bg-white rounded-xl border border-[#D9E2EC] shadow-sm flex flex-col overflow-hidden">
    <div class="flex-1 overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead class="bg-white border-b border-[#D9E2EC] sticky top-0 z-10">
                <tr class="text-[11px] font-bold text-[#627D98] uppercase tracking-[0.05em]">
                    @yield('table_header')
                </tr>
            </thead>
            <tbody id="mainTableBody" class="divide-y divide-[#F0F4F8]"> {{-- Tambah ID di sini --}}
                @yield('table_body')
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3.5 bg-[#F8FAFC] border-t border-[#D9E2EC] flex items-center justify-between">
        <p class="text-[13px] text-[#627D98] font-medium">
            Memperlihatkan <span id="paginationFrom">0</span> - <span id="paginationTo">0</span> dari total <span
                id="paginationTotal">0</span> data
        </p>
        <div class="flex items-center gap-1.5" id="paginationControls">
            {{-- Tombol navigasi akan dihandle JS --}}
        </div>
    </div>
</div>