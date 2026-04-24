@extends('layouts.master')

@section('table_header')
    <th class="px-6 py-5">Nama Supplier</th>
    <th class="px-6 py-5">Deskripsi</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    <tbody id="supplierTableBody">
        <tr><td colspan="3" class="text-center py-10 text-gray-400 italic">Memuat data...</td></tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Supplier...',
        'addUrl' => route('supplier.create'),
        'btnText' => 'Tambah Supplier'
    ])
    @include('layouts.table_wrapper')

    <script>
        let timeout = null;
        const token = localStorage.getItem('access_token');

        async function fetchSuppliers(search = '') {
            const tbody = document.getElementById('supplierTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            try {
                const res = await fetch(`/api/suppliers?limit=10&search=${search}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();

                if (res.ok) {
                    tbody.innerHTML = '';
                    const items = result.data || [];

                    if (items.length === 0) {
                        tbody.innerHTML = `<tr>
                                <td colspan="3" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-24 h-24 text-gray-200 mb-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0l-1.65-3.712A2.25 2.25 0 0016.597 2.5H7.403a2.25 2.25 0 00-2.003 1.288L3.75 7.5m16.5 0H3.75m16.5 0v1.5a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V7.5m10.5-1.125h.008v.008h-.008V6.375zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C] mb-1">Supplier tidak ditemukan</h3>
                                        <p class="text-[13px] text-gray-400 font-medium">Coba cari dengan nama supplier lain.</p>
                                    </div>
                                </td>
                            </tr>`;
                        fromEl.innerText = 0; toEl.innerText = 0; totalEl.innerText = 0;
                        return;
                    }

                    items.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors">
                                <td class="px-6 py-[18px] font-bold text-[#213F5C]">${item.name}</td>
                                <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">${item.description || '-'}</td>
                                <td class="px-6 py-[18px] text-center">
                                    <a href="/supplier/detail/${item.supplier_id}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF]">Detail</a>
                                </td>
                            </tr>`;
                    });
                    fromEl.innerText = result.from || 0; toEl.innerText = result.to || 0; totalEl.innerText = result.total || 0;
                }
            } catch (e) { console.error(e); }
        }

        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fetchSuppliers(e.target.value), 500);
        });

        document.addEventListener('DOMContentLoaded', () => fetchSuppliers());
    </script>
@endsection