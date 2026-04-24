@extends('layouts.master')

@section('title', 'Gaji Karyawan')

@section('title_header', 'Gaji Karyawan')

@section('table_header')
    <th class="px-6 py-5">Nama Karyawan</th>
    <th class="px-6 py-5">Pendapata</th>
    <th class="px-6 py-5">Penalti</th>
    <th class="px-6 py-5">Tabungan</th>
    <th class="px-6 py-5">Role</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    @php
        // Sementara masih data dummy, nanti ini dihapus jika sudah pakai data dari Controller
        $data = [
            ['nama' => 'Edsel Septa Haryanto', 'pendapatan' => 'Rp 10.000.000', 'penalti' => 'Rp 500.000', 'tabungan' => 'Rp 1.000.000', 'role' => 'Developer'],
            ['nama' => 'Edsel Septa Haryanto', 'pendapatan' => 'Rp 10.000.000', 'penalti' => 'Rp 500.000', 'tabungan' => 'Rp 1.000.000', 'role' => 'Developer'],
            ['nama' => 'Edsel Septa Haryanto', 'pendapatan' => 'Rp 10.000.000', 'penalti' => 'Rp 500.000', 'tabungan' => 'Rp 1.000.000', 'role' => 'Developer'],
        ];
    @endphp

    @foreach ($data as $item)
        <tr class="hover:bg-[#F9FCFF] transition-colors group">
            <td class="px-6 py-[18px] font-bold text-[#213F5C]">{{ $item['nama'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['pendapatan'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['penalti'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['tabungan'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['role'] }}</td>
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
        'placeholder' => 'Cari Gaji Karyawan...',
        'searchUrl' => '#',
        'filterModalId' => 'modalFilterPayroll',
        'exportExcelUrl' => '#',
        'exportPdfUrl' => '#',
        'addUrl' => route('payroll.create'),
        'btnText' => 'Tambah Gaji Karyawan'
    ])

            @include('layouts.table_wrapper', [
                // Data dummy, nanti ini dihapus jika sudah pakai data dari Controller
                'from' => 1,
                'to' => 1,
                'total' => 1
            ])
@endsection