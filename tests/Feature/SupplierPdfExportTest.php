<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use App\Http\Services\PdfExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class SupplierPdfExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_export_pdf_supplier_returns_success()
    {
        Supplier::create([
            'name' => 'Garasi BMW Supplier',
            'description' => 'Genuine Parts'
        ]);

        // Mocking PdfExportService
        $this->mock(PdfExportService::class, function (MockInterface $mock) {
            $mock->shouldReceive('export')
                ->once()
                ->andReturn(response()->make('fake-pdf-content', 200));
        });

        // Eksekusi - SESUAIKAN DENGAN web.php Anda
        $response = $this->get('/supplier/export/pdf');

        $response->assertStatus(200);
    }
}