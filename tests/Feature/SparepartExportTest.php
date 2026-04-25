<?php

namespace Tests\Feature;

use App\Http\Controllers\SparepartController;
use App\Http\Services\ExportService;
use App\Models\Sparepart;
use App\Models\ItemCategory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class SparepartExportTest extends TestCase
{
    private function makeSparepart(array $attrs, $category = null): Sparepart
    {
        $sparepart = new Sparepart();
        foreach ($attrs as $key => $value) {
            $sparepart->$key = $value;
        }
        if ($category) {
            $sparepart->setRelation('category', $category);
        }
        return $sparepart;
    }

    public function test_export_excel_returns_streamed_response()
    {
        $category = new ItemCategory();
        $category->name = 'Oli';

        $sparepart = $this->makeSparepart([
            'item_code' => 'SP-001',
            'name' => 'Oli Mesin',
            'cost_off_sell' => 50000,
            'selling_price' => 75000,
            'quantity' => 10,
        ], $category);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $this->assertEquals(
                    ['Kode Barang', 'Nama Suku Cadang', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok'],
                    $headers
                );
                $this->assertEquals('Data_Suku_Cadang.xlsx', $fileName);

                $row = $mapRow($sparepart);
                $this->assertEquals('SP-001', $row[0]);
                $this->assertEquals('Oli Mesin', $row[1]);
                $this->assertEquals('Oli', $row[2]);
                $this->assertEquals(50000, $row[3]);
                $this->assertEquals(75000, $row[4]);
                $this->assertEquals(10, $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new SparepartController();
        $response = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_sparepart_without_category()
    {
        $sparepart = $this->makeSparepart([
            'item_code' => 'SP-002',
            'name' => 'Busi',
            'cost_off_sell' => 20000,
            'selling_price' => 30000,
            'quantity' => 5,
        ]); // tanpa category

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('-', $row[2]); // Default fallback untuk kategori kosong

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new SparepartController();
        $response = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
