<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController; 
use App\Http\Services\ExportService;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Employee;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Mockery;

class CustomerExportTest extends TestCase
{
    private function makeVehicle(string $plate, string $model): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->license_plate = $plate;
        $vehicle->model = $model;
        return $vehicle;
    }

    private function makeCreator(string $name): Employee
    {
        $creator = new Employee();
        $creator->name = $name;
        return $creator;
    }

    private function makeCustomer(array $attrs, $vehicles, $creator): Customer
    {
        $customer = new Customer();
        $customer->customer_id = $attrs['customer_id'];
        $customer->name = $attrs['name'];
        $customer->phone_number = $attrs['phone_number'];
        $customer->address = $attrs['address'];
        $customer->setRelation('vehicles', collect($vehicles));
        $customer->setRelation('creator', $creator);
        return $customer;
    }

    public function test_export_excel_returns_streamed_response()
    {
        $vehicle = $this->makeVehicle('D 1234 BMW', 'E46');
        $creator = $this->makeCreator('Admin GarasiBMW');
        $customer = $this->makeCustomer([
            'customer_id' => 1,
            'name' => 'Edsel Septa',
            'phone_number' => '08123456789',
            'address' => 'Bandung',
        ], [$vehicle], $creator);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($customer) {
                $this->assertEquals(
                    ['ID', 'Nama', 'Nomor Telepon', 'Alamat', 'Daftar Kendaraan', 'Didaftarkan Oleh'],
                    $headers
                );
                $this->assertEquals('data_pelanggan_' . date('Ymd') . '.xlsx', $fileName);

                $row = $mapRow($customer);
                $this->assertEquals(1, $row[0]);
                $this->assertEquals('Edsel Septa', $row[1]);
                $this->assertEquals('08123456789', $row[2]);
                $this->assertEquals('Bandung', $row[3]);
                $this->assertEquals('D 1234 BMW (E46)', $row[4]);
                $this->assertEquals('Admin GarasiBMW', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new CustomerController();
        $response = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_customer_without_vehicle()
    {
        $creator = $this->makeCreator('Admin');
        $customer = $this->makeCustomer([
            'customer_id' => 2,
            'name' => 'No Vehicle',
            'phone_number' => '0811111111',
            'address' => 'Jakarta',
        ], [], $creator); // empty vehicles

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($customer) {
                $row = $mapRow($customer);
                $this->assertEquals('Belum ada kendaraan', $row[4]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new CustomerController();
        $response = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_export_excel_customer_without_creator()
    {
        $customer = $this->makeCustomer([
            'customer_id' => 3,
            'name' => 'No Creator',
            'phone_number' => '0822222222',
            'address' => 'Surabaya',
        ], [], null); // null creator

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($customer) {
                $row = $mapRow($customer);
                $this->assertEquals('-', $row[5]);

                return new StreamedResponse(function () {}, 200);
            });

        $controller = new CustomerController();
        $response = $controller->exportExcel($exportService);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}