<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Http\Services\PdfExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Mockery;

class PdfExportServiceTest extends TestCase
{
    protected PdfExportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PdfExportService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function mockQuery(array $data): Builder
    {
        $collection = collect($data);

        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('get')->once()->andReturn($collection);

        return $query;
    }

    private function mockPdf(): void
    {
        $domPdf = Mockery::mock(DomPDF::class);
        $domPdf->shouldReceive('setPaper')->andReturnSelf();
        $domPdf->shouldReceive('setOptions')->andReturnSelf();
        $domPdf->shouldReceive('output')->andReturn('%PDF-fake-content');

        Pdf::shouldReceive('loadHTML')->andReturn($domPdf);
    }

    public function test_export_returns_streamed_response()
    {
        $this->mockPdf();

        $query = $this->mockQuery([
            (object) ['id' => 1, 'name' => 'Supplier A'],
        ]);

        $response = $this->service->export(
            'test.pdf',
            $query,
            fn($item) => ['ID' => $item->id, 'Nama' => $item->name],
        );

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_has_correct_filename_and_content_type()
    {
        $this->mockPdf();

        $query = $this->mockQuery([
            (object) ['id' => 1, 'name' => 'Supplier A'],
        ]);

        $response = $this->service->export(
            'data_supplier.pdf',
            $query,
            fn($item) => ['ID' => $item->id, 'Nama' => $item->name],
        );

        $this->assertStringContainsString(
            'data_supplier.pdf',
            $response->headers->get('Content-Disposition')
        );
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_export_uses_default_options()
    {
        $domPdf = Mockery::mock(DomPDF::class);
        $domPdf->shouldReceive('setPaper')->with('a4', 'portrait')->once()->andReturnSelf();
        $domPdf->shouldReceive('setOptions')->andReturnSelf();
        $domPdf->shouldReceive('output')->andReturn('%PDF-fake');

        Pdf::shouldReceive('loadHTML')->andReturn($domPdf);

        $query = $this->mockQuery([
            (object) ['id' => 1, 'name' => 'Supplier A'],
        ]);

        $response = $this->service->export(
            'test.pdf',
            $query,
            fn($item) => ['ID' => $item->id],
        );

        // Tambah assertion
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_uses_custom_paper_and_orientation()
    {
        $domPdf = Mockery::mock(DomPDF::class);
        $domPdf->shouldReceive('setPaper')->with('f4', 'landscape')->once()->andReturnSelf();
        $domPdf->shouldReceive('setOptions')->andReturnSelf();
        $domPdf->shouldReceive('output')->andReturn('%PDF-fake');

        Pdf::shouldReceive('loadHTML')->andReturn($domPdf);

        $query = $this->mockQuery([
            (object) ['id' => 1, 'name' => 'Supplier A'],
        ]);

        $response = $this->service->export(
            'test.pdf',
            $query,
            fn($item) => ['ID' => $item->id],
            ['paper' => 'f4', 'orientation' => 'landscape']
        );

        // Tambah assertion
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_with_empty_data_still_returns_response()
    {
        $this->mockPdf();

        $query = $this->mockQuery([]); // kosong

        $response = $this->service->export(
            'kosong.pdf',
            $query,
            fn($item) => ['ID' => $item->id],
        );

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_map_row_is_called_for_each_item()
    {
        $this->mockPdf();

        $data = [
            (object) ['id' => 1, 'name' => 'Supplier A'],
            (object) ['id' => 2, 'name' => 'Supplier B'],
            (object) ['id' => 3, 'name' => 'Supplier C'],
        ];

        $query = $this->mockQuery($data);
        $counter = 0;

        $this->service->export(
            'test.pdf',
            $query,
            function ($item) use (&$counter) {
                $counter++;
                return ['ID' => $item->id, 'Nama' => $item->name];
            },
        );

        $this->assertEquals(3, $counter);
    }

}