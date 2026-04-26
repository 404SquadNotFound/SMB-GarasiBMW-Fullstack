<?php

namespace App\Http\Services;

use App\Models\Supplier;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class SupplierService
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
        $headers = ['ID', 'Nama Supplier', 'Deskripsi', 'Tanggal Dibuat'];
        $query = Supplier::query();
        $fileName = 'data_supplier_' . date('Ymd') . '.xlsx';

        return $this->excelService->exportToExcel($fileName, $headers, $query, function ($item) {
            return [
                $item->supplier_id,
                $item->name,
                $item->description ?? '-',
                $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function downloadPdf()
    {
        $query = Supplier::query();
        $fileName = 'data_supplier_' . date('Ymd') . '.pdf';

        return $this->pdfService->export(
            $fileName,
            $query,
            fn($item) => [
                'ID'          => $item->supplier_id,
                'Nama'        => $item->name,
                'Deskripsi'   => $item->description ?? '-',
                'Tanggal'     => $item->created_at->format('d-m-Y'),
            ],
            ['title' => 'Laporan Data Supplier GarasiBMW']
        );
    }
}