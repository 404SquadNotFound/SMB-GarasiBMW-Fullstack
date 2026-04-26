<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CarType;
use App\Models\EngineType;
use App\Http\Services\CarTypeService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CarTypeExportTest extends TestCase
{
    use RefreshDatabase;

    protected $excelServiceMock;
    protected $pdfServiceMock;
    protected $carTypeService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock kedua service
        $this->excelServiceMock = Mockery::mock(ExportService::class);
        $this->pdfServiceMock = Mockery::mock(PdfExportService::class);

        $this->carTypeService = new CarTypeService(
            $this->excelServiceMock,
            $this->pdfServiceMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function createDummyCarType(): CarType
    {
        $engine = EngineType::create([
            'name' => 'B48',
            'cylinders' => '4',
            'oil_cap' => 4.50,
            'fuel_type' => 'Bensin',
            'engine_cap' => 1998,
        ]);

        return CarType::create([
            'chassis_number' => 'F30',
            'name' => 'BMW 320i',
            'series' => '3 Series',
            'engine_code' => 'B48',
            'engine_type_id' => $engine->engine_type_id,
            'created_by' => null,
        ]);
    }


    public function it_calls_excel_service_with_correct_headers()
    {
        $this->createDummyCarType();

        $expectedHeaders = [
            'No. Chasis',
            'Nama',
            'Seri',
            'Kode Mesin',
            'Tipe Mesin',
            'Dibuat Oleh',
        ];

        $this->excelServiceMock
            ->shouldReceive('exportToExcel')
            ->once()
            ->withArgs(function ($fileName, $headers, $query, $mapRow) use ($expectedHeaders) {
                // Cek nama file formatnya benar
                $this->assertStringStartsWith('data_tipe_mobil_', $fileName);
                $this->assertStringEndsWith('.xlsx', $fileName);

                // Cek headers sesuai
                $this->assertEquals($expectedHeaders, $headers);

                return true;
            })
            ->andReturn(response()->download(base_path('composer.json'), 'test.xlsx'));

        $this->carTypeService->downloadExcel();
    }

    public function test_calls_excel_service_with_correct_headers()
    {
        $carType = $this->createDummyCarType();

        $this->excelServiceMock
            ->shouldReceive('exportToExcel')
            ->once()
            ->withArgs(function ($fileName, $headers, $query, $mapRow) use ($carType) {
                $row = $mapRow($carType);

                $this->assertEquals($carType->chassis_number, $row[0]);
                $this->assertEquals($carType->name, $row[1]);
                $this->assertEquals($carType->series, $row[2]);
                $this->assertEquals($carType->engine_code, $row[3]);
                // engineType ada
                $this->assertEquals('B48', $row[4]);
                // creator null → '-'
                $this->assertEquals('-', $row[5]);

                return true;
            })
            ->andReturn(response()->download(base_path('composer.json'), 'test.xlsx'));

        $this->carTypeService->downloadExcel();
    }

    public function test_maps_excel_row_correctly()
    {
        $carType = CarType::create([
            'chassis_number' => 'G20',
            'name' => 'BMW 330i',
            'series' => '3 Series',
            'engine_code' => '',
            'engine_type_id' => null,
            'created_by' => null,
        ]);

        $this->excelServiceMock
            ->shouldReceive('exportToExcel')
            ->once()
            ->withArgs(function ($fileName, $headers, $query, $mapRow) use ($carType) {
                $row = $mapRow($carType);

                // Relasi null → harus '-'
                $this->assertEquals('-', $row[4]); // engineType
                $this->assertEquals('-', $row[5]); // creator
    
                return true;
            })
            ->andReturn(response()->download(base_path('composer.json'), 'test.xlsx'));

        $this->carTypeService->downloadExcel();
    }

    public function test_calls_pdf_service_with_correct_options()
    {
        $this->createDummyCarType();

        $this->pdfServiceMock
            ->shouldReceive('export')
            ->once()
            ->withArgs(function ($fileName, $query, $mapRow, $options) {
                // Cek nama file
                $this->assertStringStartsWith('data_tipe_mobil_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);

                // Cek options
                $this->assertEquals('Laporan Data Tipe Mobil GarasiBMW', $options['title']);
                $this->assertEquals('a4', $options['paper']);
                $this->assertEquals('landscape', $options['orientation']);

                return true;
            })
            ->andReturn(response()->streamDownload(fn() => print ('pdf'), 'test.pdf'));

        $this->carTypeService->downloadPdf();
    }

    public function test_maps_pdf_row_correctly()
    {
        $carType = $this->createDummyCarType();

        $this->pdfServiceMock
            ->shouldReceive('export')
            ->once()
            ->withArgs(function ($fileName, $query, $mapRow, $options) use ($carType) {
                $row = $mapRow($carType);

                // Cek key dan value sesuai
                $this->assertArrayHasKey('No. Chasis', $row);
                $this->assertArrayHasKey('Nama', $row);
                $this->assertArrayHasKey('Seri', $row);
                $this->assertArrayHasKey('Kode Mesin', $row);
                $this->assertArrayHasKey('Tipe Mesin', $row);
                $this->assertArrayHasKey('Dibuat Oleh', $row);

                $this->assertEquals($carType->chassis_number, $row['No. Chasis']);
                $this->assertEquals($carType->name, $row['Nama']);
                $this->assertEquals($carType->series, $row['Seri']);
                $this->assertEquals($carType->engine_code, $row['Kode Mesin']);
                $this->assertEquals('B48', $row['Tipe Mesin']);
                $this->assertEquals('-', $row['Dibuat Oleh']);

                return true;
            })
            ->andReturn(response()->streamDownload(fn() => print ('pdf'), 'test.pdf'));

        $this->carTypeService->downloadPdf();
    }

    public function test_maps_pdf_row_with_null_relations()
    {
        $carType = CarType::create([
            'chassis_number' => 'G20',
            'name' => 'BMW 330i',
            'series' => '3 Series',
            'engine_code' => '',
            'engine_type_id' => null,
            'created_by' => null,
        ]);

        $this->pdfServiceMock
            ->shouldReceive('export')
            ->once()
            ->withArgs(function ($fileName, $query, $mapRow, $options) use ($carType) {
                $row = $mapRow($carType);

                $this->assertEquals('-', $row['Tipe Mesin']);
                $this->assertEquals('-', $row['Dibuat Oleh']);

                return true;
            })
            ->andReturn(response()->streamDownload(fn() => print ('pdf'), 'test.pdf'));

        $this->carTypeService->downloadPdf();
    }
}