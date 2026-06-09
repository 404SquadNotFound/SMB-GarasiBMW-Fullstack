<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CarType;
use App\Models\EngineType;
use App\Models\Employee;
use App\Http\Services\CarTypeService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Mockery;

class CarTypeExportTest extends TestCase
{
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

    /**
     * Helper untuk menginstansiasi EngineType secara in-memory (tanpa menyentuh DB)
     */
    private function makeEngineType(string $name): EngineType
    {
        $engine = new EngineType();
        $engine->engine_type_id = 1;
        $engine->name = $name;
        $engine->cylinders = '4';
        $engine->oil_cap = 4.50;
        $engine->fuel_type = 'Bensin';
        $engine->engine_cap = 1998;
        return $engine;
    }

    /**
     * Helper untuk menginstansiasi Employee secara in-memory (tanpa menyentuh DB)
     */
    private function makeEmployee(string $name): Employee
    {
        $employee = new Employee();
        $employee->employees_id = 99;
        $employee->name = $name;
        return $employee;
    }

    /**
     * Helper untuk menginstansiasi CarType secara in-memory (tanpa menyentuh DB)
     * persis seperti makeCustomer() pada CustomerServiceTest.php
     */
    private function makeCarType(array $attrs, $engineType = null, $creator = null): CarType
    {
        $carType = new CarType();
        $carType->car_type_id = $attrs['car_type_id'] ?? 1;
        $carType->chassis_number = $attrs['chassis_number'];
        $carType->name = $attrs['name'];
        $carType->series = $attrs['series'];
        $carType->engine_code = $attrs['engine_code'];
        $carType->engine_type_id = $attrs['engine_type_id'] ?? null;

        if ($engineType) {
            $carType->setRelation('engineType', $engineType);
        }
        if ($creator) {
            $carType->setRelation('creator', $creator);
        }

        return $carType;
    }

    public function test_calls_excel_service_with_correct_headers()
    {
        $engine = $this->makeEngineType('B48');
        $carType = $this->makeCarType([
            'chassis_number' => 'F30',
            'name' => 'BMW 320i',
            'series' => '3 Series',
            'engine_code' => 'B48',
        ], $engine);

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
            ->withArgs(function ($fileName, $headers, $query, $mapRow) use ($expectedHeaders, $carType) {
                // Cek nama file formatnya benar
                $this->assertStringStartsWith('data_tipe_mobil_', $fileName);
                $this->assertStringEndsWith('.xlsx', $fileName);

                // Cek headers sesuai
                $this->assertEquals($expectedHeaders, $headers);

                // Cek mapping row
                $row = $mapRow($carType);
                $this->assertEquals($carType->chassis_number, $row[0]);
                $this->assertEquals($carType->name, $row[1]);
                $this->assertEquals($carType->series, $row[2]);
                $this->assertEquals($carType->engine_code, $row[3]);
                $this->assertEquals('B48', $row[4]);
                $this->assertEquals('-', $row[5]);

                return true;
            })
            ->andReturn(response()->download(base_path('composer.json'), 'test.xlsx'));

        $this->carTypeService->downloadExcel();
    }

    public function test_maps_excel_row_correctly()
    {
        $engine = $this->makeEngineType('B48');
        $creator = $this->makeEmployee('Admin GarasiBMW');
        $carType = $this->makeCarType([
            'chassis_number' => 'F30',
            'name' => 'BMW 320i',
            'series' => '3 Series',
            'engine_code' => 'B48',
        ], $engine, $creator);

        $this->excelServiceMock
            ->shouldReceive('exportToExcel')
            ->once()
            ->withArgs(function ($fileName, $headers, $query, $mapRow) use ($carType) {
                $row = $mapRow($carType);

                $this->assertEquals($carType->chassis_number, $row[0]);
                $this->assertEquals($carType->name, $row[1]);
                $this->assertEquals($carType->series, $row[2]);
                $this->assertEquals($carType->engine_code, $row[3]);
                $this->assertEquals('B48', $row[4]);
                $this->assertEquals('Admin GarasiBMW', $row[5]);

                return true;
            })
            ->andReturn(response()->download(base_path('composer.json'), 'test.xlsx'));

        $this->carTypeService->downloadExcel();
    }

    public function test_maps_excel_row_with_null_relations()
    {
        $carType = $this->makeCarType([
            'chassis_number' => 'G20',
            'name' => 'BMW 330i',
            'series' => '3 Series',
            'engine_code' => '',
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
        $engine = $this->makeEngineType('B48');
        $carType = $this->makeCarType([
            'chassis_number' => 'F30',
            'name' => 'BMW 320i',
            'series' => '3 Series',
            'engine_code' => 'B48',
        ], $engine);

        $this->pdfServiceMock
            ->shouldReceive('export')
            ->once()
            ->withArgs(function ($fileName, $query, $mapRow, $options) use ($carType) {
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
        $engine = $this->makeEngineType('B48');
        $creator = $this->makeEmployee('Admin GarasiBMW');
        $carType = $this->makeCarType([
            'chassis_number' => 'F30',
            'name' => 'BMW 320i',
            'series' => '3 Series',
            'engine_code' => 'B48',
        ], $engine, $creator);

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
                $this->assertEquals('Admin GarasiBMW', $row['Dibuat Oleh']);

                return true;
            })
            ->andReturn(response()->streamDownload(fn() => print ('pdf'), 'test.pdf'));

        $this->carTypeService->downloadPdf();
    }

    public function test_maps_pdf_row_with_null_relations()
    {
        $carType = $this->makeCarType([
            'chassis_number' => 'G20',
            'name' => 'BMW 330i',
            'series' => '3 Series',
            'engine_code' => '',
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