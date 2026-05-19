<?php

namespace App\Http\Services;

use App\Models\Employee;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class EmployeeService
{
    protected $excelService;
    protected $pdfService;

    public function __construct(
        ExportService $excelService,
        PdfExportService $pdfService
    ) {
        $this->excelService = $excelService;
        $this->pdfService = $pdfService;
    }

    public function downloadExcel()
    {
        $headers = [
            'ID',
            'Nama',
            'Email',
            'Tanggal Bergabung',
            'Tanggal Lahir',
            'Alamat',
            'Role',
            'Gaji Pokok',
            'Status',
            'Tanggal Dibuat'
        ];

        $query = Employee::query();

        $fileName = 'data_karyawan_' . date('Ymd') . '.xlsx';

        return $this->excelService->exportToExcel(
            $fileName,
            $headers,
            $query,
            function ($item) {
                return [
                    $item->employees_id,
                    $item->name,
                    $item->email,
                    $item->join_date
                        ? \Carbon\Carbon::parse($item->join_date)->format('d-m-Y')
                        : '-',
                    $item->birth_date
                        ? \Carbon\Carbon::parse($item->birth_date)->format('d-m-Y')
                        : '-',
                    $item->address ?? '-',
                    ucfirst(str_replace('_', ' ', $item->role)),
                    'Rp ' . number_format($item->base_salary, 0, ',', '.'),
                    $item->status ? 'Aktif' : 'Non-Aktif',
                    $item->created_at
                        ? $item->created_at->format('d-m-Y')
                        : '-',
                ];
            }
        );
    }

    public function downloadPdf()
    {
        $query = Employee::query();

        $fileName = 'data_karyawan_' . date('Ymd') . '.pdf';

        return $this->pdfService->export(
            $fileName,
            $query,
            fn($item) => [
                'ID'                => $item->employees_id,
                'Nama'              => $item->name,
                'Email'             => $item->email,
                'Tanggal Gabung'    => $item->join_date
                    ? \Carbon\Carbon::parse($item->join_date)->format('d-m-Y')
                    : '-',
                'Tanggal Lahir'     => $item->birth_date
                    ? \Carbon\Carbon::parse($item->birth_date)->format('d-m-Y')
                    : '-',
                'Alamat'            => $item->address ?? '-',
                'Role'              => ucfirst(str_replace('_', ' ', $item->role)),
                'Gaji Pokok'        => 'Rp ' . number_format($item->base_salary, 0, ',', '.'),
                'Status'            => $item->status ? 'Aktif' : 'Non-Aktif',
                'Tanggal Dibuat'    => $item->created_at
                    ? $item->created_at->format('d-m-Y')
                    : '-',
            ],
            [
                'title' => 'Laporan Data Karyawan GarasiBMW'
            ]
        );
    }
}