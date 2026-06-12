<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Nota Pembayaran {{ $nomorNota }}</title>
    <style>
        /* ───────────────────────────────────────────
       DomPDF kalkulasi page box di 72 DPI.
       @page margin reliable setelah dpi disamakan ke 72.
    ─────────────────────────────────────────── */
        @page {
            margin-top: 12mm;
            margin-bottom: 10mm;
            margin-left: 13mm;
            margin-right: 13mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            color: #222;
            background: #fff;
        }

        /* Pembungkus utama per halaman */
        .pdf-container {
            width: 100%;
        }

        /* Paksa ganti halaman SETELAH container, hanya untuk non-terakhir */
        .pdf-container-break {
            page-break-after: always;
            break-after: page;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            word-wrap: break-word;
        }

        .nota-copy {
            width: 100%;
            overflow: hidden;
        }

        .copy-separator {
            border: none;
            border-top: 1.5px dashed #aaa;
            margin: 5px 0;
            width: 100%;
            height: 1px;
        }

        /* HEADER */
        .brand {
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }

        .brand-sub {
            font-size: 8px;
            color: #666;
        }

        .title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7.5px;
            font-weight: bold;
            color: #fff;
        }

        .badge-admin {
            background: #1273EB;
        }

        .badge-customer {
            background: #16A34A;
        }

        .meta {
            font-size: 7.5px;
            color: #555;
            line-height: 1.4;
        }

        /* DIVIDERS */
        .line-bold {
            border-top: 1.5px solid #222;
            margin-top: 2px;
        }

        .line-thin {
            border-top: 1px solid #ddd;
        }

        /* SECTION */
        .sec {
            font-size: 7.5px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2px 0 1px;
        }

        /* INFO */
        .lbl {
            font-size: 7px;
            color: #999;
            text-transform: uppercase;
        }

        .val {
            font-size: 8.5px;
            font-weight: bold;
            color: #111;
            padding-bottom: 1px;
        }

        /* RINCIAN */
        .tbl-head td {
            font-weight: bold;
            color: #555;
            border-bottom: 1px solid #bbb;
            padding: 2px;
            font-size: 8px;
        }

        .tbl-row td {
            padding: 2px;
            border-bottom: 1px solid #eee;
            font-size: 8.5px;
            word-break: break-word;
        }

        .tbl-empty td {
            height: 11px;
            padding: 0;
            border-bottom: 1px solid #eee;
            line-height: 11px;
            font-size: 1px;
        }

        .ar {
            text-align: right;
        }

        .ac {
            text-align: center;
        }

        /* FOOTER */
        .metode-lbl {
            font-size: 7px;
            color: #666;
        }

        .metode-val {
            background: #1273EB;
            color: #fff;
            font-size: 7.5px;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 2px;
        }

        .sum-lbl {
            font-size: 7.5px;
            color: #555;
            text-align: right;
        }

        .sum-val {
            font-size: 7.5px;
            font-weight: bold;
            text-align: right;
        }

        .total-line {
            border-top: 1.5px solid #222;
        }

        .total-lbl {
            font-size: 8.5px;
            font-weight: bold;
            text-align: right;
        }

        .total-val {
            font-size: 9.5px;
            font-weight: bold;
            color: #1273EB;
            text-align: right;
        }

        /* TTD */
        .ttd-cell {
            text-align: center;
            font-size: 7.5px;
            color: #444;
            vertical-align: top;
            padding-top: 2px;
        }

        .ttd-space {
            display: block;
            height: 18px;
        }

        .ttd-name {
            display: block;
            border-top: 1px solid #666;
            padding-top: 2px;
            font-weight: bold;
            font-size: 8.5px;
            color: #111;
            width: 75%;
            margin: 0 auto;
        }

        /* WATERMARK */
        .wm {
            text-align: center;
            font-size: 7px;
            color: #ccc;
            padding-top: 3px;
        }
    </style>
</head>

<body>

    @php
        $customer = $transaction->vehicle?->customer;
        $vehicle = $transaction->vehicle;

        $allItems = collect();
        foreach ($items as $item) {
            $allItems->push([
                'nama' => $item->item_name,
                'qty' => $item->qty ?? 1,
                'harga' => $item->price ?? 0,
                'subtotal' => $item->subtotal ?? 0,
            ]);
        }
        foreach ($jasaList as $jasa) {
            $allItems->push([
                'nama' => $jasa['nama'] ?? '-',
                'qty' => 1,
                'harga' => $jasa['biaya'] ?? 0,
                'subtotal' => $jasa['biaya'] ?? 0,
            ]);
        }

        $PER_PAGE = 10;
        $totalItemsCount = $allItems->count();
        $totalPages = max(1, ceil($totalItemsCount / $PER_PAGE));
        $copies = $copy === 'both' ? ['admin', 'customer'] : [$copy];
    @endphp

    @for($pageIndex = 0; $pageIndex < $totalPages; $pageIndex++)
        @php
            $start = $pageIndex * $PER_PAGE;
            $pageItems = $allItems->slice($start, $PER_PAGE);
            $emptyRows = max(0, $PER_PAGE - $pageItems->count());
        @endphp

        <div class="pdf-container{{ $pageIndex < $totalPages - 1 ? ' pdf-container-break' : '' }}">
            <!-- Debug Info: Total Items = {{ $allItems->count() }}, Total Pages = {{ $totalPages }} -->

            @foreach($copies as $idx => $copyType)
                @if($idx > 0)
                    <div class="copy-separator"></div>
                @endif

                <div class="nota-copy">

                    {{-- HEADER --}}
                    <table>
                        <tr>
                            <td style="width:50%">
                                <span class="brand">GARASIBMW</span><br>
                                <span class="brand-sub">Cabang: {{ $cabangLabel }}</span>
                            </td>
                            <td style="width:50%; text-align:right">
                                <span class="title">Nota Pembayaran</span>
                                <span
                                    class="badge {{ $copyType === 'admin' ? 'badge-admin' : 'badge-customer' }}">{{ strtoupper($copyType) }}</span><br>
                                <span class="meta">No. {{ $nomorNota }}<br>Tanggal: {{ $tanggal }}</span>
                            </td>
                        </tr>
                    </table>
                    <div class="line-bold"></div>

                    {{-- PELANGGAN --}}
                    <div class="sec">Informasi Pelanggan</div>
                    <table>
                        <tr>
                            <td style="width:25%">
                                <span class="lbl">Nama</span><br>
                                <span class="val">{{ $customer?->name ?? '-' }}</span>
                            </td>
                            <td style="width:25%">
                                <span class="lbl">Telepon</span><br>
                                <span class="val">{{ $customer?->phone_number ?? '-' }}</span>
                            </td>
                            <td style="width:50%">
                                <span class="lbl">Alamat</span><br>
                                <span class="val">{{ $customer?->address ?? '-' }}</span>
                            </td>
                        </tr>
                    </table>
                    <div class="line-thin"></div>

                    {{-- KENDARAAN --}}
                    <div class="sec">Informasi Kendaraan</div>
                    <table>
                        <tr>
                            <td style="width:28%">
                                <span class="lbl">Model</span><br>
                                <span class="val">{{ $vehicle?->model ?? '-' }}</span>
                            </td>
                            <td style="width:22%">
                                <span class="lbl">Plat</span><br>
                                <span class="val">{{ $vehicle?->license_plate ?? '-' }}</span>
                            </td>
                            <td style="width:22%">
                                <span class="lbl">Mesin</span><br>
                                <span class="val">{{ $vehicle?->engine_code ?? '-' }}</span>
                            </td>
                            <td style="width:28%">
                                <span class="lbl">KM</span><br>
                                <span class="val">{{ $transaction->km_masuk ?? '-' }}</span>
                            </td>
                        </tr>
                    </table>
                    <div class="line-thin"></div>

                    {{-- RINCIAN LAYANAN --}}
                    <div class="sec">Rincian Layanan @if($totalPages > 1) (Halaman {{ $pageIndex + 1 }} dari {{ $totalPages }})
                    @endif</div>
                    <table>
                        <tr class="tbl-head">
                            <td style="width:45%">Item</td>
                            <td class="ac" style="width:10%">Qty</td>
                            <td class="ar" style="width:22%">Harga</td>
                            <td class="ar" style="width:23%">Total</td>
                        </tr>
                        @foreach($pageItems as $item)
                            <tr class="tbl-row">
                                <td>{{ $item['nama'] }}</td>
                                <td class="ac">{{ $item['qty'] }}</td>
                                <td class="ar">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="ar">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @for($i = 0; $i < $emptyRows; $i++)
                            <tr class="tbl-empty">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    </table>

                    {{-- FOOTER: Metode + Summary --}}
                    <table style="margin-top:4px">
                        <tr>
                            <td style="width:45%; vertical-align:top">
                                <span class="metode-lbl">Metode Pembayaran</span><br>
                                <span class="metode-val">{{ $metode }}</span>
                            </td>
                            <td style="width:55%; vertical-align:top">
                                <table>
                                    <tr>
                                        <td class="sum-lbl" style="width:55%">Subtotal Suku Cadang:</td>
                                        <td class="sum-val" style="width:45%">Rp
                                            {{ number_format($subtotalParts, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="sum-lbl">Biaya Jasa Service:</td>
                                        <td class="sum-val">Rp {{ number_format($subtotalService, 0, ',', '.') }}</td>
                                    </tr>
                                    @if(isset($dpAmount) && $dpAmount > 0)
                                    <tr>
                                        <td class="sum-lbl">Subtotal:</td>
                                        <td class="sum-val">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="sum-lbl" style="color:#B45309;">Down Payment:</td>
                                        <td class="sum-val" style="color:#B45309;">- Rp {{ number_format($dpAmount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2">
                                            <div class="total-line"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="total-lbl">{{ isset($dpAmount) && $dpAmount > 0 ? 'SISA BAYAR:' : 'TOTAL:' }}</td>
                                        <td class="total-val">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    {{-- TTD --}}
                    <table style="margin-top:8px">
                        <tr>
                            <td class="ttd-cell" style="width:50%">
                                Pelanggan,
                                <span class="ttd-space"></span>
                                <span class="ttd-name">{{ $customer?->name ?? '-' }}</span>
                            </td>
                            <td class="ttd-cell" style="width:50%">
                                Penerima,
                                <span class="ttd-space"></span>
                                <span class="ttd-name">Staff GARASIBMW</span>
                            </td>
                        </tr>
                    </table>

                    <div class="wm">&copy; {{ now()->year }} GARASIBMW | 404SquadNotFound</div>

                </div>{{-- end .nota-copy --}}
            @endforeach

        </div>{{-- end .pdf-container --}}

    @endfor

</body>

</html>