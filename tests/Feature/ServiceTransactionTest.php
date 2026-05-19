<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Sparepart;
use App\Models\ServiceTransaction;

class ServiceTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $employee = Employee::factory()->create();
        
        $this->user = User::factory()->create([
            'employees_id' => $employee->employees_id,
        ]);
    }

    public function test_can_create_service_transaction_with_existing_customer_and_vehicle()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->customer_id]);
        $sparepart = Sparepart::factory()->create(['selling_price' => 100000, 'quantity' => 10]);

        $response = $this->actingAs($this->user)->postJson('/api/transactions', [
            'customer_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicles_id,
            'km_masuk' => '10000',
            'items' => [
                [
                    'sparepart_id' => $sparepart->sparepart_id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('service_transactions', [
            'vehicle_id' => $vehicle->vehicles_id,
            'odometer' => 10000,
        ]);

        $transaction = ServiceTransaction::where('vehicle_id', $vehicle->vehicles_id)->first();
        
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->transaction_id,
            'spare_part_id' => $sparepart->sparepart_id,
            'qty' => 2,
            'price' => 100000,
            'subtotal' => 200000,
        ]);
        
        // Assert stock is decremented
        $this->assertEquals(8, $sparepart->fresh()->quantity);
    }

    public function test_can_update_service_transaction()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->customer_id]);
        
        $transaction = ServiceTransaction::create([
            'vehicle_id' => $vehicle->vehicles_id,
            'invoice_number' => 'INV-TEST-001',
            'branch' => 'PELAJAR_PEJUANG',
            'odometer' => 5000,
            'status_service' => 'pengecekan',
            'status_payment' => 'unpaid',
            'created_by' => $this->user->employees_id,
        ]);

        $oldSparepart = Sparepart::factory()->create(['selling_price' => 50000, 'quantity' => 5]);
        $transaction->items()->create([
            'spare_part_id' => $oldSparepart->sparepart_id,
            'item_name' => 'Old Part',
            'item_type' => 'Parts',
            'qty' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);

        $newSparepart = Sparepart::factory()->create(['selling_price' => 60000, 'quantity' => 10]);

        $response = $this->actingAs($this->user)->putJson("/api/transactions/{$transaction->transaction_id}", [
            'customer_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicles_id,
            'km_masuk' => '6000',
            'items' => [
                [
                    'sparepart_id' => $newSparepart->sparepart_id,
                    'quantity' => 3,
                ]
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('service_transactions', [
            'transaction_id' => $transaction->transaction_id,
            'odometer' => 6000,
        ]);
        
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->transaction_id,
            'spare_part_id' => $newSparepart->sparepart_id,
            'qty' => 3,
            'price' => 60000,
        ]);
        
        $this->assertDatabaseMissing('transaction_items', [
            'transaction_id' => $transaction->transaction_id,
            'spare_part_id' => $oldSparepart->sparepart_id,
        ]);

        // Old stock restored
        $this->assertEquals(7, $oldSparepart->fresh()->quantity);
        
        // New stock decremented
        $this->assertEquals(7, $newSparepart->fresh()->quantity);
    }
    
    public function test_can_delete_service_transaction_and_restore_stock()
    {
        $vehicle = Vehicle::factory()->create();
        
        $transaction = ServiceTransaction::create([
            'vehicle_id' => $vehicle->vehicles_id,
            'invoice_number' => 'INV-TEST-002',
            'branch' => 'PELAJAR_PEJUANG',
            'odometer' => 5000,
            'status_service' => 'pengecekan',
            'status_payment' => 'unpaid',
            'created_by' => $this->user->employees_id,
        ]);

        $sparepart = Sparepart::factory()->create(['selling_price' => 50000, 'quantity' => 5]);
        $transaction->items()->create([
            'spare_part_id' => $sparepart->sparepart_id,
            'item_name' => 'Test Part',
            'item_type' => 'Parts',
            'qty' => 2,
            'price' => 50000,
            'subtotal' => 100000,
        ]);
        
        $response = $this->actingAs($this->user)->deleteJson("/api/transactions/{$transaction->transaction_id}");
        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('service_transactions', [
            'transaction_id' => $transaction->transaction_id,
        ]);
        
        $this->assertEquals(7, $sparepart->fresh()->quantity);
    }
}
