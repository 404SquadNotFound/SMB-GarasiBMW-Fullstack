<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Response;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use Illuminate\Database\Eloquent\Builder;

class ExportService
{
    /**
     * * @param string $fileName Nama file output
     * @param array $headers Array nama kolom header
     * @param Builder $query Query builder dari model (sudah dengan relasi)
     * @param callable $mapRow Fungsi spesifik untuk format baris per model
     */
    public function exportToExcel(string $fileName, array $headers, Builder $query, callable $mapRow)
    {
        return Response::streamDownload(function () use ($headers, $query, $mapRow) {
            $writer = new Writer();
            $writer->openToFile('php://output'); // Vercel safe

            $writer->addRow(Row::fromValues($headers));

            $query->chunk(500, function ($items) use ($writer, $mapRow) {
                foreach ($items as $item) {
                    $writer->addRow(Row::fromValues($mapRow($item)));
                }
            });

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}