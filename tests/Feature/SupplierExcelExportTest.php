<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use App\Http\services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class SupplierExcelExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_export_excel_supplier_returns_success()
    {
        // 1. Setup data dummy
        Supplier::create([
            'name' => 'Garasi BMW Supplier',
            'description' => 'Genuine Parts'
        ]);

        // 2. Mocking ExportService
        $this->mock(ExportService::class, function (MockInterface $mock) {
            $mock->shouldReceive('exportToExcel')
                ->once()
                ->andReturn(response()->make('fake-excel-content', 200));
        });

        // 3. Eksekusi - SESUAIKAN DENGAN web.php Anda
        $response = $this->get('/supplier/export');

        // 4. Assertions
        $response->assertStatus(200);
    }
}