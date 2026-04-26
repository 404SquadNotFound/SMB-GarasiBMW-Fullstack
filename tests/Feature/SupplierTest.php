<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Supplier;
use App\Http\Services\SupplierService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_suppliers()
    {
        Supplier::factory()->count(3)->create();

        $response = $this->getJson('/api/suppliers');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data',
                    'links',
                    'meta'
                 ]);
    }

    /** @test */
    public function it_can_search_suppliers()
    {
        Supplier::create([
            'name' => 'ABC Supplier',
            'description' => 'Test desc'
        ]);

        Supplier::create([
            'name' => 'XYZ',
            'description' => 'Another desc'
        ]);

        $response = $this->getJson('/api/suppliers?search=ABC');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_supplier_detail()
    {
        $supplier = Supplier::create([
            'name' => 'Test Supplier',
            'description' => 'Desc'
        ]);

        $response = $this->getJson("/api/suppliers/{$supplier->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'status' => 'success'
                 ]);
    }

    /** @test */
    public function it_returns_404_if_supplier_not_found()
    {
        $response = $this->getJson('/api/suppliers/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_store_supplier()
    {
        $data = [
            'name' => 'New Supplier',
            'description' => 'Desc'
        ];

        $response = $this->postJson('/api/suppliers', $data);

        $response->assertStatus(201)
                 ->assertJson([
                    'status' => 'success'
                 ]);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'New Supplier'
        ]);
    }

    /** @test */
    public function it_fails_store_validation()
    {
        $response = $this->postJson('/api/suppliers', []);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_update_supplier()
    {
        $supplier = Supplier::create([
            'name' => 'Old Name',
            'description' => 'Old Desc'
        ]);

        $response = $this->putJson("/api/suppliers/{$supplier->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated Desc'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'status' => 'success'
                 ]);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Updated Name'
        ]);
    }

    /** @test */
    public function it_fails_update_validation()
    {
        $supplier = Supplier::create([
            'name' => 'Test',
            'description' => 'Desc'
        ]);

        $response = $this->putJson("/api/suppliers/{$supplier->id}", [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_delete_supplier()
    {
        $supplier = Supplier::create([
            'name' => 'Delete Me',
            'description' => 'Desc'
        ]);

        $response = $this->deleteJson("/api/suppliers/{$supplier->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('suppliers', [
            'id' => $supplier->id
        ]);
    }

    /** @test */
    public function it_can_export_excel()
    {
        $mock = Mockery::mock(SupplierService::class);
        $mock->shouldReceive('downloadExcel')
              ->once()
              ->andReturn(response()->json(['success' => true]));

        $this->app->instance(SupplierService::class, $mock);

        $response = $this->get('/api/suppliers/export-excel');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_export_pdf()
    {
        $mock = Mockery::mock(SupplierService::class);
        $mock->shouldReceive('downloadPdf')
              ->once()
              ->andReturn(response()->json(['success' => true]));

        $this->app->instance(SupplierService::class, $mock);

        $response = $this->get('/api/suppliers/export-pdf');

        $response->assertStatus(200);
    }
}