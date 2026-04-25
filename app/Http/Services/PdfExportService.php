<?php

namespace App\Http\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

class PdfExportService
{
    public function export(
        string $fileName,
        Builder $query,
        callable $mapRow,
        array $options = []
    ) {
        $rows        = $query->get()->map($mapRow);
        $paper       = $options['paper']       ?? 'a4';
        $orientation = $options['orientation'] ?? 'portrait';
        $title       = $options['title']       ?? 'Laporan';

        // HTML langsung di sini, tidak perlu blade file
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body  { font-family: Arial, sans-serif; font-size: 12px; color: #213F5C; }
                .header    { text-align: center; margin-bottom: 20px; }
                .header h2 { margin: 0; font-size: 16px; }
                .header p  { margin: 4px 0; color: #555; font-size: 11px; }
                table      { width: 100%; border-collapse: collapse; }
                th { background-color: #1273EB; color: white; padding: 8px; text-align: left; font-size: 11px; }
                td { padding: 7px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
                tr:nth-child(even) { background-color: #F9FCFF; }
                .footer { margin-top: 20px; font-size: 10px; color: #999; text-align: right; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>' . e($title) . '</h2>
                <p>Dicetak pada: ' . now()->format('d M Y, H:i') . '</p>
                <p>Total: <strong>' . $rows->count() . '</strong> data</p>
            </div>
            <table>
                <thead>
                    <tr>' .
                        collect(array_keys($rows->first() ?? []))
                            ->map(fn($key) => '<th>' . e($key) . '</th>')
                            ->join('') .
                    '</tr>
                </thead>
                <tbody>' .
                    ($rows->isEmpty()
                        ? '<tr><td colspan="99" style="text-align:center;padding:20px;">Tidak ada data</td></tr>'
                        : $rows->map(fn($row) =>
                            '<tr>' .
                                collect($row)->map(fn($val) => '<td>' . e($val) . '</td>')->join('') .
                            '</tr>'
                          )->join('')
                    ) .
                '</tbody>
            </table>
            <div class="footer">
                Digenerate otomatis oleh sistem &mdash; ' . now()->format('Y') . '
            </div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paper, $orientation)
            ->setOptions([
                'defaultFont'          => 'Arial',
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 150,
            ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName,
            [
                'Content-Type'  => 'application/pdf',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}