<?php

namespace Tests\Feature;

use App\Http\Controllers\ItemCategoryController;
use App\Http\Services\ExportService;
use App\Models\ItemCategory;
use App\Models\Employee;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class ItemCategoryExcelExportTest extends TestCase
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
        $category->category_id  = $attrs['category_id'];
        $category->name         = $attrs['name'];
        $category->descriptions = $attrs['descriptions'] ?? null;
        $category->created_by   = $attrs['created_by'] ?? null;
        $category->spareparts_count = $sparepartsCount;
        $category->setRelation('creator', $creator);
        return $category;
    }

    public function test_export_excel_returns_streamed_response(): void
    {
        $creator = $this->makeCreator('Admin GarasiBMW');
        $category = $this->makeCategory([
            'category_id'  => 1,
            'name'         => 'Pelumas',
            'descriptions' => 'Kategori untuk oli dan pelumas mesin',
            'created_by'   => 10,
        ], 5, $creator);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($category) {
                $this->assertEquals(
                    ['ID', 'Nama Kategori', 'Deskripsi', 'Jumlah Suku Cadang', 'Dibuat Oleh'],
                    $headers
                );
                $this->assertEquals('data_kategori_barang_' . date('Ymd') . '.xlsx', $fileName);

                $row = $mapRow($category);
                $this->assertEquals(1,                                         $row[0]);
                $this->assertEquals('Pelumas',                                 $row[1]);
                $this->assertEquals('Kategori untuk oli dan pelumas mesin',    $row[2]);
                $this->assertEquals(5,                                         $row[3]);
                $this->assertEquals('Admin GarasiBMW',                         $row[4]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_category_without_description_uses_dash_fallback(): void
    {
        $category = $this->makeCategory([
            'category_id'  => 2,
            'name'         => 'Filter',
            'descriptions' => null,
            'created_by'   => null,
        ], 0, null);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($category) {
                $row = $mapRow($category);

                $this->assertEquals('-', $row[2]); // deskripsi null → '-'
                $this->assertEquals(0,   $row[3]); // spareparts_count = 0
                $this->assertEquals('-', $row[4]); // created_by null → '-'

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_category_with_many_spareparts(): void
    {
        $creator = $this->makeCreator('Teknisi');
        $category = $this->makeCategory([
            'category_id'  => 3,
            'name'         => 'Rem & Kopling',
            'descriptions' => 'Spare part sistem pengereman',
            'created_by'   => 5,
        ], 42, $creator);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($category) {
                $row = $mapRow($category);

                $this->assertEquals('Rem & Kopling',              $row[1]);
                $this->assertEquals('Spare part sistem pengereman', $row[2]);
                $this->assertEquals(42,                             $row[3]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_headers_are_correct(): void
    {
        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) {
                $this->assertCount(5, $headers);
                $this->assertContains('ID',                  $headers);
                $this->assertContains('Nama Kategori',       $headers);
                $this->assertContains('Deskripsi',           $headers);
                $this->assertContains('Jumlah Suku Cadang',  $headers);
                $this->assertContains('Dibuat Oleh',         $headers);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new ItemCategoryController();
        $response   = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}