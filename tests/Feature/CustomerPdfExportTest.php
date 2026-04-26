<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController;
use App\Http\Services\PdfExportService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use Tests\TestCase;
use Mockery;

class CustomerPdfExportTest extends TestCase
{
    private function makeCustomer(array $attrs, $creator = null, array $vehicles = []): Customer
    {
        $customer = new Customer();
        $customer->customer_id  = $attrs['customer_id'];
        $customer->name         = $attrs['name'];
        $customer->phone_number = $attrs['phone_number'];
        $customer->address      = $attrs['address'];

        if ($creator) {
            $customer->setRelation('creator', $creator);
        }

        $customer->setRelation('vehicles', collect($vehicles));

        return $customer;
    }

    private function makeEmployee(string $name): Employee
    {
        $employee = new Employee();
        $employee->name = $name;
        return $employee;
    }

    private function makeVehicle(string $licensePlate): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->license_plate = $licensePlate;
        return $vehicle;
    }

    public function test_export_pdf_returns_response_with_full_relations()
    {
        $admin = $this->makeEmployee('Reza Admin');
        $mobil1 = $this->makeVehicle('B 1020 JAW');
        $mobil2 = $this->makeVehicle('D 5555 XYZ');

        $customer = $this->makeCustomer([
            'customer_id'  => 101,
            'name'         => 'Edsel Septa',
            'phone_number' => '08123456789',
            'address'      => 'Bandung',
        ], $admin, [$mobil1, $mobil2]);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($customer) {
                $this->assertStringStartsWith('laporan_pelanggan_', $fileName);
                $this->assertStringEndsWith('.pdf', $fileName);
                $this->assertEquals('Laporan Data Pelanggan GarasiBMW', $options['title']);

                $row = $mapRow($customer);
                $this->assertEquals(101, $row['ID']);
                $this->assertEquals('Edsel Septa', $row['Nama']);
                $this->assertEquals('08123456789', $row['telepon']);
                $this->assertEquals('Bandung', $row['Alamat']);
                
                $this->assertEquals('B 1020 JAW, D 5555 XYZ', $row['Kendaraan']); 
                
                $this->assertEquals('Reza Admin', $row['Pendaftar']); 

                return response()->make('fake-pdf-content', 200);
            });

        // 3. Eksekusi Controller
        $controller = new CustomerController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_export_pdf_customer_without_relations_returns_fallback()
    {
        $customer = $this->makeCustomer([
            'customer_id'  => 102,
            'name'         => 'John Doe',
            'phone_number' => '089999999',
            'address'      => 'Jakarta',
        ]); 

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($customer) {
                $row = $mapRow($customer);
                
                $this->assertEquals('-', $row['Kendaraan']);
                $this->assertEquals('-', $row['Pendaftar']);

                return response()->make('fake-pdf-content', 200);
            });

        $controller = new CustomerController();
        $response   = $controller->exportPdf($pdfService);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}