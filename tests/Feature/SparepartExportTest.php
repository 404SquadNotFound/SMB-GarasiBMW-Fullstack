<?php

namespace Tests\Feature;

use App\Http\Controllers\SparepartController;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\ItemCategory;
use App\Models\Sparepart;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class SparepartExportTest extends TestCase
{
    private function makeSparepart(array $attrs, $category = null): Sparepart
    {
        $sparepart = new Sparepart();
        $sparepart->item_code     = $attrs['item_code'];
        $sparepart->name          = $attrs['name'];
        $sparepart->cost_off_sell = $attrs['cost_off_sell'];
        $sparepart->selling_price = $attrs['selling_price'];
        $sparepart->quantity      = $attrs['quantity'];

        if ($category) {
            $sparepart->setRelation('category', $category);
        }

        return $sparepart;
    }

    private function makeCategory(string $name): ItemCategory
    {
        $category = new ItemCategory();
        $category->name = $name;
        return $category;
    }

    // ── exportExcel ──────────────────────────────────────────────

    public function test_export_excel_returns_streamed_response()
    {
        $category  = $this->makeCategory('Pelumas');
        $sparepart = $this->makeSparepart([
            'item_code'     => 'SP-001',
            'name'          => 'Oli Mesin',
            'cost_off_sell' => 50000,
            'selling_price' => 75000,
            'quantity'      => 10,
        ], $category);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $this->assertEquals('Data_Suku_Cadang.xlsx', $fileName);
                $this->assertEquals(
                    ['Kode Barang', 'Nama Suku Cadang', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok'],
                    $headers
                );

                $row = $mapRow($sparepart);
                $this->assertEquals('SP-001',    $row[0]);
                $this->assertEquals('Oli Mesin', $row[1]);
                $this->assertEquals('Pelumas',   $row[2]);
                $this->assertEquals(50000,        $row[3]);
                $this->assertEquals(75000,        $row[4]);
                $this->assertEquals(10,           $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new SparepartController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_sparepart_without_category()
    {
        $sparepart = $this->makeSparepart([
            'item_code'     => 'SP-002',
            'name'          => 'Busi NGK',
            'cost_off_sell' => 20000,
            'selling_price' => 30000,
            'quantity'      => 5,
        ]); // tanpa category → fallback '-'

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('-', $row[2]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new SparepartController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_sparepart_with_string_category()
    {
        $sparepart = $this->makeSparepart([
            'item_code'     => 'SP-003',
            'name'          => 'Filter Udara',
            'cost_off_sell' => 35000,
            'selling_price' => 55000,
            'quantity'      => 8,
        ]);
        // category sebagai string (bukan relasi object)
        $sparepart->setAttribute('category', 'Filter');

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('Filter', $row[2]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new SparepartController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    // ── exportPdf ────────────────────────────────────────────────

    public function test_export_pdf_returns_response()
    {
        $category  = $this->makeCategory('Pelumas');
        $sparepart = $this->makeSparepart([
            'item_code'     => 'SP-PDF01',
            'name'          => 'Oli Mesin',
            'cost_off_sell' => 50000,
            'selling_price' => 75000,
            'quantity'      => 10,
        ], $category);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $q, $mapRow, $options) use ($sparepart) {
                $this->assertStringStartsWith('Laporan_Suku_Cadang_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);
                $this->assertEquals('Laporan Data Suku Cadang', $options['title']);
                $this->assertEquals('a4',       $options['paper']);
                $this->assertEquals('portrait', $options['orientation']);

                $row = $mapRow($sparepart);
                $this->assertEquals('SP-PDF01',  $row['Kode Barang']);
                $this->assertEquals('Oli Mesin', $row['Nama Suku Cadang']);
                $this->assertEquals('Pelumas',   $row['Kategori']);
                $this->assertStringStartsWith('Rp ', $row['Harga Beli']);
                $this->assertStringStartsWith('Rp ', $row['Harga Jual']);
                $this->assertEquals(10, $row['Stok']);

                return response()->make('pdf-content', 200);
            });

        $controller = new SparepartController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_sparepart_without_category()
    {
        $sparepart = $this->makeSparepart([
            'item_code'     => 'SP-PDF02',
            'name'          => 'Kampas Rem',
            'cost_off_sell' => 80000,
            'selling_price' => 120000,
            'quantity'      => 3,
        ]); // tanpa category → fallback '-'

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $q, $mapRow, $options) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('-', $row['Kategori']);

                return response()->make('pdf-content', 200);
            });

        $controller = new SparepartController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
