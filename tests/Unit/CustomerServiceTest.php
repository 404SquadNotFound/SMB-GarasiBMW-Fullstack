<?php


namespace Tests\Unit;

use App\Http\Services\CustomerService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class CustomerServiceTest extends TestCase
{
    protected $excelMock;
    protected $pdfMock;
    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->excelMock = Mockery::mock(ExportService::class);
        $this->pdfMock = Mockery::mock(PdfExportService::class);

        $this->customerService = new CustomerService($this->excelMock, $this->pdfMock);
    }

    private function makeCustomer(array $attrs, array $vehicles = [], $creator = null): Customer
    {
        $customer = new Customer();
        $customer->customer_id = $attrs['customer_id'];
        $customer->name = $attrs['name'];
        $customer->phone_number = $attrs['phone_number'];
        $customer->address = $attrs['address'];

        if ($creator) {
            $customer->setRelation('creator', $creator);
        }
        $customer->setRelation('vehicles', collect($vehicles));

        return $customer;
    }

    private function makeVehicle(string $plate, string $model): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->license_plate = $plate;
        $vehicle->model = $model;
        return $vehicle;
    }

    private function makeEmployee(string $name): Employee
    {
        $employee = new Employee();
        $employee->name = $name;
        return $employee;
    }


    public function test_format_and_validate_cleans_messy_plate_successfully()
    {
        $cars = [
            ['license_plate' => ' b-1020   jaw ', 'car_type_id' => 1]
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertTrue($result['success']);
        $this->assertEquals('B 1020 JAW', $result['data'][0]['license_plate']);
    }

    public function test_format_and_validate_fails_if_prefix_is_invalid()
    {
        $cars = [
            ['license_plate' => 'XX 1234 YY']
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString("Kode wilayah 'XX'", $result['message']);
    }

    public function test_format_and_validate_fails_if_regex_format_is_wrong()
    {
        $cars = [
            ['license_plate' => 'B 12345 JAW']
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString("tidak valid", $result['message']);
    }

    public function test_download_excel_maps_data_correctly()
    {
        $customer = $this->makeCustomer(
            ['customer_id' => 1, 'name' => 'Edsel', 'phone_number' => '0812', 'address' => 'Bandung'],
            [$this->makeVehicle('D 1234 XYZ', 'BMW E46')],
            $this->makeEmployee('Admin GarasiBMW')
        );

        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($customer) {
                // Cek nama file
                $this->assertStringStartsWith('data_pelanggan_', $fileName);

                // Cek hasil mapping array-nya
                $row = $mapRow($customer);
                $this->assertEquals(1, $row[0]);
                $this->assertEquals('Edsel', $row[1]);
                $this->assertEquals('D 1234 XYZ (BMW E46)', $row[4]);
                $this->assertEquals('Admin GarasiBMW', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->customerService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_download_pdf_maps_data_correctly_and_handles_empty_relations()
    {
        $customer = $this->makeCustomer([
            'customer_id' => 2,
            'name' => 'John Doe',
            'phone_number' => '0899',
            'address' => 'Jakarta'
        ]);

        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($customer) {
                $this->assertStringStartsWith('laporan_pelanggan_', $fileName);

                $row = $mapRow($customer);
                $this->assertEquals(2, $row['ID']);
                $this->assertEquals('-', $row['Kendaraan']);
                $this->assertEquals('-', $row['Pendaftar']);

                return response()->make('fake-pdf-content', 200);
            });

        $response = $this->customerService->downloadPdf();
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_format_and_validate_succeeds_with_multiple_valid_cars()
    {
        $cars = [
            ['license_plate' => 'B 1234 XYZ', 'car_type_id' => 1],
            ['license_plate' => 'd-5678-ab', 'car_type_id' => 2],
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertTrue($result['success']);
        $this->assertCount(2, $result['data']);
        $this->assertEquals('B 1234 XYZ', $result['data'][0]['license_plate']);
        $this->assertEquals('D 5678 AB', $result['data'][1]['license_plate']);
    }

    public function test_format_and_validate_succeeds_for_plate_without_suffix()
    {
        $cars = [
            ['license_plate' => 'B 9999', 'car_type_id' => 1]
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertTrue($result['success']);
        $this->assertEquals('B 9999', $result['data'][0]['license_plate']);
    }

    public function test_format_and_validate_succeeds_with_two_char_prefix()
    {
        $cars = [
            ['license_plate' => 'AA 1234 AB', 'car_type_id' => 1]
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertTrue($result['success']);
        $this->assertEquals('AA 1234 AB', $result['data'][0]['license_plate']);
    }

    public function test_format_and_validate_fails_on_second_car_and_returns_error()
    {
        $cars = [
            ['license_plate' => 'B 1234 XY', 'car_type_id' => 1],
            ['license_plate' => 'ZZ 9999 ABC', 'car_type_id' => 2],
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString("Kode wilayah 'ZZ'", $result['message']);
    }

    public function test_format_and_validate_fails_if_plate_has_no_numbers()
    {
        $cars = [
            ['license_plate' => 'ABCDE']
        ];

        $result = $this->customerService->formatAndValidate($cars);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('tidak valid', $result['message']);
    }

    public function test_download_excel_handles_no_vehicles_and_no_creator()
    {
        // Customer tanpa kendaraan dan tanpa creator
        $customer = $this->makeCustomer([
            'customer_id' => 99,
            'name' => 'No Data',
            'phone_number' => '0800',
            'address' => 'Unknown',
        ]);

        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($customer) {
                $row = $mapRow($customer);

                // Vehicles kosong → fallback string
                $this->assertEquals('Belum ada kendaraan', $row[4]);
                // Creator null → '-'
                $this->assertEquals('-', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->customerService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_download_excel_joins_multiple_vehicles_with_comma()
    {
        $customer = $this->makeCustomer(
            ['customer_id' => 3, 'name' => 'Multi', 'phone_number' => '0811', 'address' => 'Surabaya'],
            [
                $this->makeVehicle('B 1111 AA', 'Toyota Supra'),
                $this->makeVehicle('B 2222 BB', 'Honda NSX'),
            ]
        );

        $this->excelMock->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $query, $mapRow) use ($customer) {
                $row = $mapRow($customer);

                $this->assertEquals(
                    'B 1111 AA (Toyota Supra), B 2222 BB (Honda NSX)',
                    $row[4]
                );

                return new StreamedResponse(function () {}, 200);
            });

        $response = $this->customerService->downloadExcel();
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_download_pdf_maps_data_correctly_with_vehicles_and_creator()
    {
        $customer = $this->makeCustomer(
            ['customer_id' => 5, 'name' => 'Budi', 'phone_number' => '0855', 'address' => 'Bekasi'],
            [$this->makeVehicle('F 4321 XY', 'Mazda RX-7')],
            $this->makeEmployee('Staff Bengkel')
        );

        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($customer) {
                $row = $mapRow($customer);

                $this->assertEquals(5, $row['ID']);
                $this->assertEquals('Budi', $row['Nama']);
                $this->assertEquals('F 4321 XY', $row['Kendaraan']);
                $this->assertEquals('Staff Bengkel', $row['Pendaftar']);
                $this->assertEquals('Laporan Data Pelanggan GarasiBMW', $options['title']);

                return response()->make('pdf', 200);
            });

        $response = $this->customerService->downloadPdf();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_download_pdf_joins_multiple_vehicles_with_comma()
    {
        $customer = $this->makeCustomer(
            ['customer_id' => 6, 'name' => 'Citra', 'phone_number' => '0877', 'address' => 'Depok'],
            [
                $this->makeVehicle('D 1000 ZZ', 'BMW M3'),
                $this->makeVehicle('D 2000 YY', 'BMW M5'),
            ]
        );

        $this->pdfMock->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($customer) {
                $row = $mapRow($customer);

                $this->assertEquals('D 1000 ZZ, D 2000 YY', $row['Kendaraan']);

                return response()->make('pdf', 200);
            });

        $response = $this->customerService->downloadPdf();
        $this->assertEquals(200, $response->getStatusCode());
    }
}