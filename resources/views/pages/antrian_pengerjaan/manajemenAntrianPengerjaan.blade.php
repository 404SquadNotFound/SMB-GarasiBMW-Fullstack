{{-- resources/views/layanan-servis/antrian-pengerjaan/index.blade.php --}}
{{--
    TODO Backend:
    - Ganti $dummyData dengan variable $antrianList (Collection/Paginator) dari Controller
    - Ganti $from, $to, $total dengan $antrianList->firstItem(), ->lastItem(), ->total()
    - Tambahkan logika pencarian: ?search= di searchUrl
    - Tambahkan logika export Excel & PDF di exportExcelUrl & exportPdfUrl
--}}
@extends('layouts.master')
@section('title', 'Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

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
        // =============================================
        // TODO Backend: hapus $dummyData ini sepenuhnya
        // Ganti @foreach di bawah dengan $antrianList
        // =============================================
        $dummyData = [
            [
                'id'            => 1,
                'name'          => 'Edsel Septa Haryanto',
                'phone'         => '085155030650',
                'license_plate' => 'B 1040 JAW',
                'car_model'     => 'BMW E46 318i',
                'engine_code'   => 'N42',
                'status'        => 'Pengecekan',
            ],
            [
                'id'            => 2,
                'name'          => 'Abdul Aziz Saepurohmat',
                'phone'         => '081250353492',
                'license_plate' => 'D 1015 PRT',
                'car_model'     => 'BMW M3 GTR',
                'engine_code'   => 'R64',
                'status'        => 'Dalam Proses',
            ],
            [
                'id'            => 3,
                'name'          => 'Reza Indra Maulana',
                'phone'         => '081345304293',
                'license_plate' => 'H 5090 TI',
                'car_model'     => 'BMW M Hybrid V8',
                'engine_code'   => 'R32',
                'status'        => 'Selesai',
            ],
        ];
        // TODO Backend: ganti $dummyData dengan $antrianList (hasil dari Controller)
        $rows = $dummyData;
    @endphp

    @foreach ($rows as $item)
        <tr class="hover:bg-[#F9FCFF] transition-colors group">
            {{-- TODO Backend: $item['name'] → $item->name (jika pakai Eloquent model) --}}
            <td class="px-6 py-4.5 font-bold text-[#213F5C]">{{ $item['name'] }}</td>
            <td class="px-6 py-4.5 text-[#213F5C] font-semibold text-[13px]">{{ $item['phone'] }}</td>
            <td class="px-6 py-4.5 text-[#213F5C] font-semibold text-[13px]">{{ $item['license_plate'] }}</td>
            <td class="px-6 py-4.5 text-[#213F5C] font-semibold text-[13px]">{{ $item['car_model'] }}</td>
            <td class="px-6 py-4.5 text-[#213F5C] font-semibold text-[13px]">{{ $item['engine_code'] }}</td>

            {{-- Status Badge — warna otomatis sesuai status, tidak perlu diubah --}}
            <td class="px-6 py-4.5">
                @php
                    $statusConfig = match($item['status']) {
                        'Pengecekan'   => ['bg' => 'bg-[#FFF8EC]', 'text' => 'text-[#F59E0B]', 'border' => 'border-[#FDE68A]'],
                        'Dalam Proses' => ['bg' => 'bg-[#EAF2FF]', 'text' => 'text-[#1273EB]', 'border' => 'border-[#B1D3FF]'],
                        'Selesai'      => ['bg' => 'bg-[#EDFBF3]', 'text' => 'text-[#16A34A]', 'border' => 'border-[#A7F3D0]'],
                        default        => ['bg' => 'bg-[#F5F5F5]', 'text' => 'text-[#6B7280]',  'border' => 'border-[#E5E7EB]'],
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border
                    {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }}">
                    {{ $item['status'] }}
                </span>
            </td>

            {{-- Action --}}
            <td class="px-6 py-4.5 text-center">
                <a href="{{ route('antrian-pengerjaan.show', $item['id']) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#EAF2FF] text-[#1273EB] border border-[#B1D3FF] rounded-full text-[12px] font-bold hover:bg-[#D4E8FF] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail
                </a>
            </td>
        </tr>
    @endforeach
@endsection

@section('content')
    @include('layouts.action_bar', [
        'placeholder'    => 'Cari Antrian Pengerjaan...',
        'searchUrl'      => '#', // TODO Backend: ganti '#' dengan URL pencarian
        'filterModalId'  => 'modalFilterAntrianPengerjaan',
        'exportExcelUrl' => '#', // TODO Backend: ganti '#' dengan route export Excel
        'exportPdfUrl'   => '#', // TODO Backend: ganti '#' dengan route export PDF
        'addUrl'         => route('antrian-pengerjaan.create'),
        'btnText'        => 'Tambah Antrian',
    ])

    @include('layouts.table_wrapper', [
        // TODO Backend: ganti nilai dummy ini dengan:
        // 'from'  => $antrianList->firstItem(),
        // 'to'    => $antrianList->lastItem(),
        // 'total' => $antrianList->total(),
        'from'  => 1,
        'to'    => 3,
        'total' => 12,
    ])
@endsection