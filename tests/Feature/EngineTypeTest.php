<?php

namespace Tests\Feature;

use App\Http\Controllers\EngineTypeController;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\EngineType;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use Carbon\Carbon;

class EngineTypeTest extends TestCase
{
    private function makeEngineType(array $attrs): EngineType
    {
        $engine = new EngineType();
        $engine->engine_type_id = $attrs['engine_type_id'];
        $engine->name           = $attrs['name'];
        $engine->cylinders      = $attrs['cylinders'];
        $engine->oil_cap        = $attrs['oil_cap'];
        $engine->fuel_type      = $attrs['fuel_type'];
        $engine->engine_cap     = $attrs['engine_cap'];
        $engine->created_at     = $attrs['created_at'] ?? Carbon::parse('2025-01-15');
        return $engine;
    }

    // export excel

    public function test_export_excel_returns_streamed_response()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 1,
            'name'           => 'B48B20',
            'cylinders'      => 'Inline-4',
            'oil_cap'        => 5.5,
            'fuel_type'      => 'Bensin',
            'engine_cap'     => 1998,
            'created_at'     => Carbon::parse('2025-01-15'),
        ]);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($engine) {
                $this->assertStringStartsWith('data_mesin_', $fileName);
                $this->assertStringEndsWith('.xlsx', $fileName);

                $this->assertEquals(
                    ['Nama Mesin', 'Silinder', 'Kapasitas Oli (L)', 'Tipe BBM', 'Kapasitas Mesin (cc)', 'Tanggal Dibuat'],
                    $headers
                );

                // Validasi mapping row
                $row = $mapRow($engine);
                $this->assertEquals('B48B20',    $row[0]);
                $this->assertEquals('Inline-4',  $row[1]);
                $this->assertEquals(5.5,         $row[2]);
                $this->assertEquals('Bensin',    $row[3]);
                $this->assertEquals(1998,        $row[4]);
                $this->assertEquals('15-01-2025', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportExcel(new Request(), $exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_engine_with_diesel_fuel_type()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 2,
            'name'           => 'OM651',
            'cylinders'      => 'Inline-4',
            'oil_cap'        => 7.0,
            'fuel_type'      => 'Diesel',
            'engine_cap'     => 2143,
            'created_at'     => Carbon::parse('2025-03-10'),
        ]);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($engine) {
                $row = $mapRow($engine);
                $this->assertEquals('Diesel',    $row[3]);
                $this->assertEquals('10-03-2025', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportExcel(new Request(), $exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_engine_with_v8_cylinders()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 3,
            'name'           => 'S63B44',
            'cylinders'      => 'V8',
            'oil_cap'        => 9.0,
            'fuel_type'      => 'Bensin',
            'engine_cap'     => 4395,
            'created_at'     => Carbon::parse('2025-06-20'),
        ]);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($engine) {
                $row = $mapRow($engine);
                $this->assertEquals('S63B44', $row[0]);
                $this->assertEquals('V8',     $row[1]);
                $this->assertEquals(9.0,      $row[2]);
                $this->assertEquals(4395,     $row[4]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportExcel(new Request(), $exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    // export pdf

    public function test_export_pdf_returns_response()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 4,
            'name'           => 'N52B30',
            'cylinders'      => 'Inline-6',
            'oil_cap'        => 6.5,
            'fuel_type'      => 'Bensin',
            'engine_cap'     => 2996,
            'created_at'     => Carbon::parse('2025-02-28'),
        ]);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $q, $mapRow, $options) use ($engine) {
                $this->assertStringStartsWith('data_mesin_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);

                $this->assertEquals('Laporan Data Tipe Mesin', $options['title']);

                $row = $mapRow($engine);
                $this->assertEquals('N52B30',    $row['Nama']);
                $this->assertEquals('Inline-6',  $row['Silinder']);
                $this->assertEquals(6.5,         $row['Oli']);
                $this->assertEquals('Bensin',    $row['BBM']);
                $this->assertEquals(2996,        $row['Kapasitas']);
                $this->assertEquals('28-02-2025', $row['Tanggal']);

                return response()->make('pdf-content', 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportPdf(new Request(), $pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_engine_with_diesel_fuel_type()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 5,
            'name'           => 'OM642',
            'cylinders'      => 'V6',
            'oil_cap'        => 8.0,
            'fuel_type'      => 'Diesel',
            'engine_cap'     => 2987,
            'created_at'     => Carbon::parse('2025-04-01'),
        ]);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $q, $mapRow, $options) use ($engine) {
                $row = $mapRow($engine);
                $this->assertEquals('OM642',     $row['Nama']);
                $this->assertEquals('V6',        $row['Silinder']);
                $this->assertEquals('Diesel',    $row['BBM']);
                $this->assertEquals('01-04-2025', $row['Tanggal']);

                return response()->make('pdf-content', 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportPdf(new Request(), $pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_options_title_is_correct()
    {
        $engine = $this->makeEngineType([
            'engine_type_id' => 6,
            'name'           => 'B58B30',
            'cylinders'      => 'Inline-6',
            'oil_cap'        => 6.5,
            'fuel_type'      => 'Bensin',
            'engine_cap'     => 2998,
            'created_at'     => Carbon::parse('2025-07-07'),
        ]);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $q, $mapRow, $options) use ($engine) {
                // Fokus test: pastikan title di options selalu benar
                $this->assertArrayHasKey('title', $options);
                $this->assertEquals('Laporan Data Tipe Mesin', $options['title']);

                return response()->make('pdf-content', 200);
            });

        $controller = new EngineTypeController();
        $response   = $controller->exportPdf(new Request(), $pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}