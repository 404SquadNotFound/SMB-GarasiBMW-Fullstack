@extends('layouts.master')

@section('title', 'Pendataan Izin Keterlambatan')

@section('title_header', 'Pendataan Izin Keterlambatan')

@section('table_header')
    <th class="px-6 py-5">Nama Karyawan</th>
    <th class="px-6 py-5">Tanggal Terlambat</th>
    <th class="px-6 py-5">Alasan</th>
    <th class="px-6 py-5 text-center">Action</th>
@endsection

@section('table_body')
    @php
        // Sementara masih data dummy, nanti ini dihapus jika sudah pakai data dari Controller
        $data = [
            ['name' => 'Edsel Septa Haryanto', 'date' => '2024-06-01', 'reason' => 'Macet parah di jalan tol akibat kecelakaan.'],
            ['name' => 'Dewi Lestari', 'date' => '2024-06-02', 'reason' => 'Kendala keluarga mendadak, harus mengurus anak yang sakit.'],
            ['name' => 'Rizky Pratama', 'date' => '2024-06-03', 'reason' => 'Alarm tidak berbunyi, terlambat bangun.'],
            ['name' => 'Siti Nurhaliza', 'date' => '2024-06-04', 'reason' => 'Transportasi umum terlambat datang.'],
            ['name' => 'Ahmad Fauzi', 'date' => '2024-06-05', 'reason' => 'Kendala kesehatan, mengalami migrain parah.'],
        ];
    @endphp

    @foreach ($data as $item)
        <tr class="hover:bg-[#F9FCFF] transition-colors group">
            <td class="px-6 py-[18px] font-bold text-[#213F5C]">{{ $item['name'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['date'] }}</td>
            <td class="px-6 py-[18px] text-[#213F5C] font-semibold text-[13px]">{{ $item['reason'] }}</td>
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
        'placeholder' => 'Cari Izin Keterlambatan...',
        'searchUrl' => '#',
        'filterModalId' => 'modalFilterIzinKeterlambatan',
        'exportExcelUrl' => '#',
        'exportPdfUrl' => '#',
        'addUrl' => route('izin-terlambat.create'),
        'btnText' => 'Tambah Izin'
    ])

        @include('layouts.table_wrapper', [
            // Data dummy, nanti ini dihapus jika sudah pakai data dari Controller
            'from' => 1,
            'to' => 1,
            'total' => 1
        ])
@endsection