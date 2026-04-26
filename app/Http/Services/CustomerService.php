<?php

namespace App\Http\Services;

use App\Models\Customer;
use App\Models\CarType;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;

class CustomerService
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
        $headers = ['ID', 'Nama', 'Nomor Telepon', 'Alamat', 'Daftar Kendaraan', 'Didaftarkan Oleh'];
        $query = Customer::with(['creator', 'vehicles']); 
        $fileName = 'data_pelanggan_' . date('Ymd') . '.xlsx';

        return $this->excelService->exportToExcel($fileName, $headers, $query, function ($item) {
            $daftarKendaraan = $item->vehicles->isNotEmpty() 
                ? $item->vehicles->map(fn($v) => $v->license_plate . ' (' . $v->model . ')')->implode(', ') 
                : 'Belum ada kendaraan';

            return [
                $item->customer_id,
                $item->name,
                $item->phone_number,
                $item->address,
                $daftarKendaraan,
                $item->creator ? $item->creator->name : '-',
            ];
        });
    }

    public function downloadPdf()
    {
        $query = Customer::with(['creator', 'vehicles']);
        $fileName = 'laporan_pelanggan_' . date('Ymd') . '.pdf';

        return $this->pdfService->export(
            $fileName,
            $query,
            fn($item) => [
                'ID' => $item->customer_id,
                'Nama' => $item->name,
                'telepon' => $item->phone_number,
                'Alamat' => $item->address,
                'Kendaraan' => $item->vehicles->isNotEmpty() 
                    ? $item->vehicles->map(fn($v) => $v->license_plate)->implode(', ') 
                    : '-',
                'Pendaftar' => $item->creator ? $item->creator->name : '-',
            ],
            ['title' => 'Laporan Data Pelanggan GarasiBMW']
        );
    }

    public function formatAndValidate(array $cars): array
    {
        $validPrefixes = [
            'A', 'B', 'D', 'E', 'F', 'T', 'Z', 'G', 'H', 'K', 'R', 'AA', 'AB', 'AD', 'L', 'M', 'N', 'P', 'S', 'W', 'AE', 'AG',
            'BL', 'BB', 'BK', 'BA', 'BM', 'BP', 'BG', 'BN', 'BE', 'BD', 'BH',
            'DK', 'DR', 'EA', 'DH', 'EB', 'ED',
            'KB', 'DA', 'KH', 'KT', 'KU',
            'DB', 'DL', 'DM', 'DN', 'DT', 'DD', 'DC', 'DP',
            'DE', 'DG', 'PA', 'PB', 'PD', 'PE', 'PG', 'PS', 'PT'
        ];

        $formattedCars = [];

        foreach ($cars as $index => $car) {
            $rawPlate = preg_replace('/[^A-Z0-9]/i', '', strtoupper($car['license_plate']));

            if (preg_match('/^([A-Z]{1,2})(\d{1,4})([A-Z]{0,3})$/', $rawPlate, $matches)) {
                $prefix = $matches[1];

                if (!in_array($prefix, $validPrefixes)) {
                    return [
                        'success' => false,
                        'message' => "Kode wilayah '{$prefix}' pada plat '{$car['license_plate']}' tidak dikenali di Indonesia. Silakan periksa kembali!"
                    ];
                }

                $car['license_plate'] = trim($matches[1] . ' ' . $matches[2] . ' ' . $matches[3]);
                $formattedCars[] = $car;

            } else {
                return [
                    'success' => false,
                    'message' => "Format nomor polisi '" . $car['license_plate'] . "' tidak valid! Gunakan format standar (Contoh: B 1020 JAW)."
                ];
            }
        }

        return [
            'success' => true,
            'data' => $formattedCars
        ];
    }
}

