<?php

namespace App\Http\Services;

use App\Models\CarType;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class CarTypeService
{
    protected $excelService;
    protected $pdfService;

    public function __construct(ExportService $excelService, PdfExportService $pdfService)
    {
        $this->excelService = $excelService;
        $this->pdfService = $pdfService;
    }

    public function downloadExcel()
    {
        // 6 header sesuai 6 kolom di mapRow
        $headers = ['No. Chasis', 'Nama', 'Seri', 'Kode Mesin', 'Tipe Mesin', 'Dibuat Oleh'];

        // Tambah engineType di eager load
        $query = CarType::with(['engineType', 'creator']);

        $fileName = 'data_tipe_mobil_' . date('Ymd_His') . '.xlsx';

        return $this->excelService->exportToExcel(
            $fileName,
            $headers,
            $query,
            function ($item) {
                return [
                    $item->chassis_number,
                    $item->name,
                    $item->series,
                    $item->engine_code,
                    $item->engineType ? $item->engineType->name : '-',
                    $item->creator    ? $item->creator->name    : '-',
                ];
            }
        );
    }

    public function downloadPdf()
    {
        // Tambah engineType di eager load
        $query = CarType::with(['engineType', 'creator']);

        $fileName = 'data_tipe_mobil_' . date('Ymd_His') . '.pdf';

        return $this->pdfService->export(
            $fileName,
            $query,
            fn($item) => [
                'No. Chasis'  => $item->chassis_number,
                'Nama'        => $item->name,
                'Seri'        => $item->series,
                'Kode Mesin'  => $item->engine_code,
                'Tipe Mesin'  => $item->engineType ? $item->engineType->name : '-',
                'Dibuat Oleh' => $item->creator    ? $item->creator->name    : '-',
            ],
            [
                'title'       => 'Laporan Data Tipe Mobil GarasiBMW',
                'paper'       => 'a4',
                'orientation' => 'landscape',
            ]
        );
    }
}