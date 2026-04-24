@extends('layouts.master')

@section('title', 'Manajemen Servis Mobil')

@section('title_header', 'Manajemen Servis Mobil')

@section('table_header')
    <th class="px-6 py-5">Nama</th>
    <th class="px-6 py-5">Nomor Telepon</th>
    <th class="px-6 py-5">Nomor Polisi</th>
    <th class="px-6 py-5">Model Mobil</th>
    <th class="px-6 py-5">Kode Mesin</th>
    <th class="px-6 py-5">Status</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    @php
        // Sementara masih data dummy, nanti ini dihapus jika sudah pakai data dari Controller
        $data = [
            ['name' => 'Edsel Septa Haryanto', 'phone' => '081234567890', 'license_plate' => 'B 1234 CD', 'car_model' => 'BMW 320i E46', 'engine_code' => 'M54B30, M52B28, M52B25', 'status' => 'Pengecekan'],
            

        ];
    @endphp

    @foreach ($data as $item)
        <tr class="hover:bg-[#F9FCFF] transition-colors group">
            <td class="px-6 py-[18px] font-bold text-[#213F5C]">{{ $item['name'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['phone'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['license_plate'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['car_model'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['engine_code'] }}</td>
            <td class="px-6 py-[18px] text-center">
                <button
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Detail
                </button>
            </td>
        </tr>

    @endforeach
@endsection

@section('content')

    @include('layouts.action_bar', [
        // Ntar tambahin logika pencarian export sama postnya
        'placeholder' => 'Cari Manajemen Servis Mobil...',
        'searchUrl' => '#',
        'filterModalId' => 'modalFilterManajemenServisMobil',
        'exportExcelUrl' => '#',
        'exportPdfUrl' => '#',
        'addUrl' => route('manajemen-servis.create'),'',
        'btnText' => 'Tambah Servis Mobil'
    ])

        @include('layouts.table_wrapper', [
            // Data dummy, nanti ini dihapus jika sudah pakai data dari Controller
            'from' => 1,
            'to' => 1,
            'total' => 1
        ])
@endsection