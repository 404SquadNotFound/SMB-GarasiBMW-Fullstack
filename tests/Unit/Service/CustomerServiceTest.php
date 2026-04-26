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
        // Pastikan spasi dan huruf kapital dirapikan
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
}