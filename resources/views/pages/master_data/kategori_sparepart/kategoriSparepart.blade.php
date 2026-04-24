@extends('layouts.master')

@section('title', 'Kategori Sparepart')
@section('title_header', 'Master Data | Kategori Sparepart')

@section('table_header')
    <th class="px-6 py-5">Nama Kategori Sparepart</th>
    <th class="px-6 py-5">Deskripsi</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    <tbody id="categoryTableBody">
        <tr>
            <td colspan="3" class="text-center py-10 text-gray-400 italic">Memuat data kategori...</td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Kategori Sparepart...',
        'addUrl' => route('kategori-sparepart.create'),
        'btnText' => 'Tambah Kategori Sparepart'
    ])

    @include('layouts.table_wrapper')

    <script>
        let timeout = null;
        let currentSearch = '';
        const token = localStorage.getItem('access_token');

        // 1. Fungsi Fetch Data Utama
        async function fetchCategories(search = '') {
            const tbody = document.getElementById('categoryTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            if (!tbody) return;

            try {
                const url = `/api/item-categories?limit=10&search=${search}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                
                const result = await res.json();

                if (res.ok) {
                    const items = result.data || [];
                    tbody.innerHTML = '';

                    // 2. LOGIC EMPTY STATE DENGAN SVG
                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="3" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0l-1.65-3.712A2.25 2.25 0 0016.597 2.5H7.403a2.25 2.25 0 00-2.003 1.288L3.75 7.5m16.5 0H3.75m16.5 0v1.5a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V7.5m10.5-1.125h.008v.008h-.008V6.375zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Kategori tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cari dengan nama kategori lain.</p>
                                    </div>
                                </td>
                            </tr>`;
                        
                        if(fromEl) fromEl.innerText = 0; 
                        if(toEl) toEl.innerText = 0; 
                        if(totalEl) totalEl.innerText = 0;
                        return;
                    }

                    // 3. RENDER DATA (Jika Ada)
                    items.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors group">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.name}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.descriptions || '-'}</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/kategori-sparepart/detail/${item.category_id}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">
                                       Detail
                                    </a>
                                </td>
                            </tr>`;
                    });

                    // 4. Update Angka Pagination
                    if(fromEl) fromEl.innerText = result.from || 0;
                    if(toEl) toEl.innerText = result.to || 0;
                    if(totalEl) totalEl.innerText = result.total || 0;
                }
            } catch (e) { 
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-10 text-red-500 font-bold">Waduh, koneksi API kategori bermasalah brok!</td></tr>';
            }
        }

        // 5. Logic Debounce Search
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchCategories(e.target.value);
            }, 500);
        });

        // 6. Init Load
        document.addEventListener('DOMContentLoaded', () => {
            fetchCategories();
        });
    </script>
@endsection