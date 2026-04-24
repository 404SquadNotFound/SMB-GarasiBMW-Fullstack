@extends('layouts.master')

@section('title', 'Data Pelanggan')
@section('title_header', 'Data Pelanggan')

@section('table_header')
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nama</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nomor Telepon</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Alamat Pengguna</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Nomor Polisi</th>
    <th class="px-6 py-5 text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Model Mobil</th>
    <th class="px-6 py-5 text-center text-[13px] font-bold text-[#627D98] uppercase tracking-wider">Action</th>
@endsection

@section('table_body')
    <tbody id="customerTableBody">
        <tr>
            <td colspan="6" class="text-center py-20 text-gray-400 italic font-medium">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-[#1273EB] border-t-transparent rounded-full animate-spin"></div>
                    Memuat data pelanggan...
                </div>
            </td>
        </tr>
    </tbody>
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder' => 'Cari Akun...', 
        'addUrl' => route('pelanggan.create'), 
        'btnText' => 'Tambah Akun'
    ])
    @include('layouts.table_wrapper')

    <script>
        const token = localStorage.getItem('access_token');
        let timeout = null;

        async function fetchCustomers(search = '') {
            const tbody = document.getElementById('customerTableBody');
            const fromEl = document.getElementById('paginationFrom');
            const toEl = document.getElementById('paginationTo');
            const totalEl = document.getElementById('paginationTotal');

            try {
                const res = await fetch(`/api/customers?limit=10&search=${search}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();

                if (res.ok) {
                    tbody.innerHTML = '';
                    const items = result.data.data || result.data || [];

                    if (items.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-20 h-20 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="text-[16px] font-bold text-[#213F5C]">Pelanggan tidak ditemukan brok</h3>
                                        <p class="text-[13px] text-gray-400">Coba cari dengan nama atau nomor telepon lain.</p>
                                    </div>
                                </td>
                            </tr>`;
                        return;
                    }

                    items.forEach(c => {
                        // Logic nampilin semua nomor polisi & model mobil (Multiple support)
                        const plates = c.vehicles.length > 0 
                            ? c.vehicles.map(v => `<div class="mb-1 last:mb-0">${v.license_plate}</div>`).join('') 
                            : '<span class="text-gray-300">-</span>';
                        
                        const models = c.vehicles.length > 0 
                            ? c.vehicles.map(v => `<div class="mb-1 last:mb-0 font-bold">${v.model || (v.car_type ? v.car_type.name : '-')}</div>`).join('') 
                            : '<span class="text-gray-300">-</span>';

                        tbody.innerHTML += `
                            <tr class="hover:bg-[#F9FCFF] transition-colors border-b border-gray-50 last:border-0 group text-[14px]">
                                <td class="px-6 py-5 font-bold text-[#213F5C]">${c.name}</td>
                                <td class="px-6 py-5 text-[#213F5C] font-semibold">${c.phone_number}</td>
                                <td class="px-6 py-5 text-[#627D98] max-w-[200px] truncate font-medium">${c.address}</td>
                                <td class="px-6 py-5 text-[#213F5C] font-bold whitespace-nowrap">${plates}</td>
                                <td class="px-6 py-5 text-[#213F5C] whitespace-nowrap text-[13px]">${models}</td>
                                <td class="px-6 py-5 text-center">
                                    <a href="/pelanggan/detail/${c.customer_id}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>`;
                    });

                    // Update Pagination Text (Footer)
                    if(fromEl) fromEl.innerText = result.from || result.data.from || 0;
                    if(toEl) toEl.innerText = result.to || result.data.to || 0;
                    if(totalEl) totalEl.innerText = result.total || result.data.total || 0;
                }
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="6" class="py-10 text-center text-red-500 font-bold">API bermasalah brok, cek log!</td></tr>';
            }
        }

        // Search logic with Debounce
        document.getElementById('searchInput').addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchCustomers(e.target.value);
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', () => fetchCustomers());
    </script>
@endsection