<?php

namespace App\Http\Services;

use App\Models\ServiceTransaction;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfNota
{
    /**
     * Generate nota pembayaran PDF.
     *
     * @param  int|string  $transactionId
     * @param  string      $copy  'admin' | 'customer' | 'both'
     * @return \Illuminate\Http\Response
     */
    public function generate($transactionId, string $copy = 'both')
    {
        $transaction = ServiceTransaction::with([
            'vehicle.customer',
            'items',
            'cabang',
        ])->findOrFail($transactionId);

        $items = $transaction->items ?? collect();

        $subtotalParts    = $items->where('item_type', 'Parts')->sum('subtotal');
        $subtotalService  = $items->where('item_type', 'Service')->sum('subtotal');
        $total            = $subtotalParts + $subtotalService;

        $nomorNota = 'TRX-' . now()->format('Ymd') . '-' . str_pad($transactionId, 4, '0', STR_PAD_LEFT);

        $data = [
            'transaction'     => $transaction,
            'items'           => $items,
            'subtotalParts'   => $subtotalParts,
            'subtotalService' => $subtotalService,
            'total'           => $total,
            'copy'            => $copy,
            'tanggal'         => now()->locale('id')->translatedFormat('d F Y'),
            'nomorNota'       => $nomorNota,
        ];

        $pdf = Pdf::loadView('pdf.nota_pembayaran', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 150,
            ]);

        $filename = 'Nota-' . $nomorNota . '.pdf';

        return $pdf->stream($filename);
        // Ganti ->stream($filename) dengan ->download($filename) jika ingin force download
    }
}