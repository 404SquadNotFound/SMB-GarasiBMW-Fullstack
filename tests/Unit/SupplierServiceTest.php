<?php

namespace Tests\Unit;

use App\Http\Services\SupplierService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\Supplier;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;
use Tests\TestCase;
use Mockery;
use RuntimeException;

class SupplierServiceTest extends TestCase
{
    protected $excelMock;
    protected $pdfMock;
    protected $supplierService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->excelMock = Mockery::mock(ExportService::class);
        $this->pdfMock   = Mockery::mock(PdfExportService::class);

        $this->supplierService = new SupplierService($this->excelMock, $this->pdfMock);
    }

    private function makeSupplier(array $attrs): Supplier
    {
        $supplier              = new Supplier();
        $supplier->supplier_id = $attrs['supplier_id'];
        $supplier->name        = $attrs['name'];
        $supplier->description = $attrs['description'] ?? null;
        $supplier->created_at  = $attrs['created_at'] ?? now();

        return $supplier;
    }

    public function test_download_excel_maps_data_correctly()
    {
        $supplier = $this->makeSupplier([
            'supplier_id' => 1,
            'name'        => 'Garasi BMW Supplier',
            'description' => 'Genuine Parts',
            'created_at'  => now(),
        ]);

        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($supplier) {
                $this->assertMatchesRegularExpression(
                    '/^data_supplier_\d{8}\.xlsx$/',
                    $fileName,
                    'Format nama file harus data_supplier_YYYYMMDD.xlsx'
                );

                $this->assertEquals(
                    ['ID', 'Nama Supplier', 'Deskripsi', 'Tanggal Dibuat'],
                    $headers,
                    'Headers tidak sesuai'
                );

                $row = $mapRow($supplier);
                $this->assertCount(4, $row, 'Row harus memiliki 4 kolom');
                $this->assertEquals(1, $row[0]);
                $this->assertEquals('Garasi BMW Supplier', $row[1]);
                $this->assertEquals('Genuine Parts', $row[2]);
                $this->assertEquals($supplier->created_at->format('d-m-Y'), $row[3]);

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->supplierService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_download_excel_uses_dash_when_description_is_null()
    {
        $supplier = $this->makeSupplier([
            'supplier_id' => 2,
            'name'        => 'Supplier Tanpa Deskripsi',
            'description' => null,
            'created_at'  => now(),
        ]);

        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($supplier) {
                $row = $mapRow($supplier);
                $this->assertEquals('-', $row[2], 'Deskripsi null harus ditampilkan sebagai tanda strip');

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->supplierService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_download_excel_filename_contains_today_date()
    {
        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName) {
                $expectedDate = date('Ymd');
                $this->assertStringContainsString(
                    $expectedDate,
                    $fileName,
                    'Nama file harus mengandung tanggal hari ini'
                );

                return new StreamedResponse(function () {}, 200);
            });

        $this->supplierService->downloadExcel();
    }

    public function test_download_excel_passes_correct_headers()
    {
        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers) {
                $this->assertEquals('ID', $headers[0]);
                $this->assertEquals('Nama Supplier', $headers[1]);
                $this->assertEquals('Deskripsi', $headers[2]);
                $this->assertEquals('Tanggal Dibuat', $headers[3]);

                return new StreamedResponse(function () {}, 200);
            });

        $this->supplierService->downloadExcel();
    }

    public function test_download_excel_propagates_exception_from_service()
    {
        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andThrow(new RuntimeException('Export gagal'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Export gagal');

        $this->supplierService->downloadExcel();
    }

    public function test_download_pdf_maps_data_correctly()
    {
        $supplier = $this->makeSupplier([
            'supplier_id' => 3,
            'name'        => 'PT Garasi BMW',
            'description' => 'OEM Supplier',
            'created_at'  => now(),
        ]);

        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($supplier) {
                $this->assertMatchesRegularExpression(
                    '/^data_supplier_\d{8}\.pdf$/',
                    $fileName,
                    'Format nama file harus data_supplier_YYYYMMDD.pdf'
                );

                $this->assertArrayHasKey('title', $options);
                $this->assertEquals('Laporan Data Supplier GarasiBMW', $options['title']);

                $row = $mapRow($supplier);
                $this->assertCount(4, $row, 'Row harus memiliki 4 key');
                $this->assertArrayHasKey('ID', $row);
                $this->assertArrayHasKey('Nama', $row);
                $this->assertArrayHasKey('Deskripsi', $row);
                $this->assertArrayHasKey('Tanggal', $row);

                $this->assertEquals(3, $row['ID']);
                $this->assertEquals('PT Garasi BMW', $row['Nama']);
                $this->assertEquals('OEM Supplier', $row['Deskripsi']);
                $this->assertEquals($supplier->created_at->format('d-m-Y'), $row['Tanggal']);

                return response()->make('fake-pdf-content', 200);
            });

        $response = $this->supplierService->downloadPdf();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_download_pdf_uses_dash_when_description_is_null()
    {
        $supplier = $this->makeSupplier([
            'supplier_id' => 4,
            'name'        => 'Supplier Kosong',
            'description' => null,
            'created_at'  => now(),
        ]);

        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($supplier) {
                $row = $mapRow($supplier);
                $this->assertEquals('-', $row['Deskripsi'], 'Deskripsi null harus ditampilkan sebagai tanda strip');

                return response()->make('fake-pdf-content', 200);
            });

        $response = $this->supplierService->downloadPdf();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_download_pdf_filename_contains_today_date()
    {
        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName) {
                $expectedDate = date('Ymd');
                $this->assertStringContainsString(
                    $expectedDate,
                    $fileName,
                    'Nama file PDF harus mengandung tanggal hari ini'
                );

                return response()->make('fake-pdf-content', 200);
            });

        $this->supplierService->downloadPdf();
    }

    public function test_download_pdf_options_has_correct_title()
    {
        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) {
                $this->assertIsArray($options);
                $this->assertArrayHasKey('title', $options);
                $this->assertEquals('Laporan Data Supplier GarasiBMW', $options['title']);

                return response()->make('fake-pdf-content', 200);
            });

        $this->supplierService->downloadPdf();
    }

    public function test_download_pdf_propagates_exception_from_service()
    {
        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andThrow(new RuntimeException('PDF export gagal'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('PDF export gagal');

        $this->supplierService->downloadPdf();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}