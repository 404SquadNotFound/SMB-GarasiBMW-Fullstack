<?php

namespace App\Http\Services;

use App\Models\ServiceTransaction;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfNota
{
    /**
     * Generate nota pembayaran PDF dari data transaksi + jasa service dari frontend.
     *
     * @param  int|string  $transactionId
     * @param  array       $jasaList      [['nama' => string, 'biaya' => int], ...]
     * @param  string      $metode        Metode pembayaran yang dipilih
     * @param  string      $copy          'admin' | 'customer' | 'both'
     * @return \Illuminate\Http\Response
     */
    public function generatePreview($transactionId, array $jasaList = [], string $metode = '-', string $copy = 'both', bool $download = false)
    {
        $transaction = ServiceTransaction::with([
            'vehicle.customer',
            'vehicle.carType',
            'items.sparepart',
        ])->findOrFail($transactionId);

        $items = $transaction->items ?? collect();

        // Subtotal suku cadang (Parts dari DB)
        $subtotalParts = $items->where('item_type', 'Parts')->sum('subtotal');

        // Subtotal jasa service (dari frontend — belum disimpan ke DB)
        $subtotalService = collect($jasaList)->sum('biaya');

        $total = $subtotalParts + $subtotalService;

        // Ambil DP dari transaksi
        $dpAmount = ($transaction->status_payment === 'dp' && $transaction->dp_amount)
            ? (float) $transaction->dp_amount
            : 0;
        $totalAfterDp = max(0, $total - $dpAmount);

        $nomorNota = 'TRX-' . now()->format('Ymd') . str_pad($transactionId, 4, '0', STR_PAD_LEFT);

        // Mapping cabang (kolom string di tabel)
        $cabangMap = [
            'PELAJAR_PEJUANG' => 'Pelajar Pejuang',
        ];
        $cabangLabel = $cabangMap[$transaction->branch ?? ''] ?? ($transaction->branch ?? 'Pusat');

        $data = [
            'transaction'     => $transaction,
            'items'           => $items,
            'jasaList'        => collect($jasaList),
            'subtotalParts'   => $subtotalParts,
            'subtotalService' => $subtotalService,
            'subtotal'        => $total,
            'dpAmount'        => $dpAmount,
            'total'           => $totalAfterDp,
            'copy'            => $copy,
            'metode'          => $metode,
            'tanggal'         => now()->locale('id')->translatedFormat('d F Y'),
            'nomorNota'       => $nomorNota,
            'cabangLabel'     => $cabangLabel,
        ];

        $pdf = Pdf::loadView('pdf.nota_pembayaran', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 72,
                'enable_css_float'     => false,
            ]);

        $filename = 'Nota-' . $nomorNota . '.pdf';

        return $download ? $pdf->download($filename) : $pdf->stream($filename);
    }

    /**
     * Generate nota pembayaran PDF (legacy — hanya dari data DB).
     *
     * @param  int|string  $transactionId
     * @param  string      $copy  'admin' | 'customer' | 'both'
     * @return \Illuminate\Http\Response
     */
    public function generate($transactionId, string $copy = 'both')
    {
        $transaction = ServiceTransaction::with([
            'vehicle.customer',
            'vehicle.carType',
            'items.sparepart',
        ])->findOrFail($transactionId);

        $items = $transaction->items ?? collect();

        $subtotalParts   = $items->where('item_type', 'Parts')->sum('subtotal');
        $subtotalService = $items->where('item_type', 'Service')->sum('subtotal');
        $total           = $subtotalParts + $subtotalService;

        // Ambil DP dari transaksi
        $dpAmount = ($transaction->status_payment === 'dp' && $transaction->dp_amount)
            ? (float) $transaction->dp_amount
            : 0;
        $totalAfterDp = max(0, $total - $dpAmount);

        $nomorNota = 'TRX-' . now()->format('Ymd') . str_pad($transactionId, 4, '0', STR_PAD_LEFT);

        $cabangMap = [
            'PELAJAR_PEJUANG' => 'Pelajar Pejuang',
        ];
        $cabangLabel = $cabangMap[$transaction->branch ?? ''] ?? ($transaction->branch ?? 'Pusat');

        $data = [
            'transaction'     => $transaction,
            'items'           => $items,
            'jasaList'        => collect(),
            'subtotalParts'   => $subtotalParts,
            'subtotalService' => $subtotalService,
            'subtotal'        => $total,
            'dpAmount'        => $dpAmount,
            'total'           => $totalAfterDp,
            'copy'            => $copy,
            'metode'          => $transaction->payment_method ?? '-',
            'tanggal'         => now()->locale('id')->translatedFormat('d F Y'),
            'nomorNota'       => $nomorNota,
            'cabangLabel'     => $cabangLabel,
        ];

        $pdf = Pdf::loadView('pdf.nota_pembayaran', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 72,
                'enable_css_float'     => false,
            ]);

        $filename = 'Nota-' . $nomorNota . '.pdf';

        return $pdf->stream($filename);
    }
}