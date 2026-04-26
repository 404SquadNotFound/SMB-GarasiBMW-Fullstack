<?php

namespace Tests\Feature;

use App\Http\Controllers\ItemCategoryController;
use App\Http\Services\PdfExportService;
use App\Models\ItemCategory;
use App\Models\Employee;
use Tests\TestCase;
use Mockery;

class ItemCategoryPdfExportTest extends TestCase
{
    private function makeCreator(string $name): Employee
    {
        $creator = new Employee();
        $creator->name = $name;
        return $creator;
    }

    private function makeCategory(array $attrs, int $sparepartsCount = 0, $creator = null): ItemCategory
    {
        $category = new ItemCategory();
        $category->category_id      = $attrs['category_id'];
        $category->name             = $attrs['name'];
        $category->descriptions     = $attrs['descriptions'] ?? null;
        $category->created_by       = $attrs['created_by'] ?? null;
        $category->spareparts_count = $sparepartsCount;
        $category->setRelation('creator', $creator);
        return $category;
    }

    public function test_export_pdf_returns_response_with_correct_data(): void
    {
        $creator = $this->makeCreator('Admin GarasiBMW');
        $category = $this->makeCategory([
            'category_id'  => 1,
            'name'         => 'Pelumas',
            'descriptions' => 'Oli dan pelumas mesin',
            'created_by'   => 7,
        ], 8, $creator);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($category) {
                $this->assertStringStartsWith('laporan_kategori_barang_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);
                $this->assertEquals('Laporan Master Data Kategori Barang', $options['title']);

                $row = $mapRow($category);
                $this->assertEquals(1,                      $row['ID']);
                $this->assertEquals('Pelumas',              $row['Nama Kategori']);
                $this->assertEquals('Oli dan pelumas mesin', $row['Deskripsi']);
                $this->assertEquals(8,                      $row['Jumlah Suku Cadang']);
                $this->assertEquals('Admin GarasiBMW',      $row['Dibuat Oleh']);

                return response()->make('fake-pdf', 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_category_without_description_fallback(): void
    {
        $category = $this->makeCategory([
            'category_id'  => 2,
            'name'         => 'Transmisi',
            'descriptions' => null,
            'created_by'   => null,
        ], 0, null);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($category) {
                $row = $mapRow($category);

                $this->assertEquals('-', $row['Deskripsi']);   // null → '-'
                $this->assertEquals('-', $row['Dibuat Oleh']); // null → '-'
                $this->assertEquals(0,   $row['Jumlah Suku Cadang']);

                return response()->make('fake-pdf', 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_title_is_correct(): void
    {
        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) {
                $this->assertEquals('Laporan Master Data Kategori Barang', $options['title']);

                return response()->make('fake-pdf', 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_filename_contains_date(): void
    {
        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) {
                $expectedDate = date('Ymd');
                $this->assertStringContainsString($expectedDate, $fileName);
                $this->assertStringContainsString('kategori_barang', $fileName);

                return response()->make('fake-pdf', 200);
            });

        $controller = new ItemCategoryController();
        $controller->exportPdf($pdfService);
    }

    public function test_export_pdf_row_keys_are_in_bahasa_indonesia(): void
    {
        $creator = $this->makeCreator('Teknisi');
        $category = $this->makeCategory([
            'category_id'  => 3,
            'name'         => 'Suspensi',
            'descriptions' => 'Komponen suspensi kendaraan',
            'created_by'   => 2,
        ], 3, $creator);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($category) {
                $row = $mapRow($category);

                $this->assertArrayHasKey('ID',                   $row);
                $this->assertArrayHasKey('Nama Kategori',        $row);
                $this->assertArrayHasKey('Deskripsi',            $row);
                $this->assertArrayHasKey('Jumlah Suku Cadang',   $row);
                $this->assertArrayHasKey('Dibuat Oleh',          $row);

                return response()->make('fake-pdf', 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}