<?php

namespace Tests\Unit;

use App\Http\Services\SupplierService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\Supplier;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

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
                $this->assertStringStartsWith('data_supplier_', $fileName);
                $this->assertStringEndsWith('.xlsx', $fileName);

                $row = $mapRow($supplier);
                $this->assertEquals(1, $row[0]);
                $this->assertEquals('Garasi BMW Supplier', $row[1]);
                $this->assertEquals('Genuine Parts', $row[2]);
                $this->assertEquals($supplier->created_at->format('d-m-Y'), $row[3]);

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->supplierService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
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
                $this->assertEquals('-', $row[2]);

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->supplierService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
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
                $this->assertStringStartsWith('data_supplier_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);
                $this->assertEquals('Laporan Data Supplier GarasiBMW', $options['title']);

                $row = $mapRow($supplier);
                $this->assertEquals(3, $row['ID']);
                $this->assertEquals('PT Garasi BMW', $row['Nama']);
                $this->assertEquals('OEM Supplier', $row['Deskripsi']);
                $this->assertEquals($supplier->created_at->format('d-m-Y'), $row['Tanggal']);

                return response()->make('fake-pdf-content', 200);
            });

        $response = $this->supplierService->downloadPdf();
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
                $this->assertEquals('-', $row['Deskripsi']);

                return response()->make('fake-pdf-content', 200);
            });

        $response = $this->supplierService->downloadPdf();
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}