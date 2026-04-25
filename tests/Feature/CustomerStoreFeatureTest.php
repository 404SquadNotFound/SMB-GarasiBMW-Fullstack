<?php

namespace Tests\Feature;

use App\Models\CarType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerStoreFeatureTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $carType;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(); 
        
        $this->carType = CarType::create([
            'car_type_id' => 1, 
            'name' => 'BMW E46 325i',
            'chassis_number' => 'WBAEV51000XXXXXXX', 
            'series' => '3 Series',                  
            'engine_code' => 'M54B25'               
        ]);
        
    }


    private function getPayload(string $licensePlate): array
    {
        return [
            'name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'address' => 'Jl. Braga No 1',
            'cars' => [
                [
                    'car_type_id' => $this->carType->car_type_id,
                    'license_plate' => $licensePlate, 
                    'km_reading' => 50000,
                    'year' => 2004,
                    'engine_name' => 'M54B25'
                ]
            ]
        ];
    }


    public function test_it_can_store_and_format_messy_license_plate()
    {
        $payload = $this->getPayload(' b-1020   jaw ');

        $response = $this->actingAs($this->user)->postJson('/api/customers', $payload);

        $response->assertStatus(201);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('vehicles', [
            'license_plate' => 'B 1020 JAW'
        ]);
        
        $this->assertDatabaseHas('customers', [
            'name' => 'Budi Santoso'
        ]);
    }

    public function test_it_fails_if_license_plate_prefix_is_invalid()
    {
        $payload = $this->getPayload('XX 1234 YY');

        $response = $this->actingAs($this->user)->postJson('/api/customers', $payload);

        $response->assertStatus(422);
        
        $response->assertJsonFragment([
            'message' => "Kode wilayah 'XX' pada plat 'XX 1234 YY' tidak dikenali di Indonesia. Silakan periksa kembali!"
        ]);

        $this->assertDatabaseCount('customers', 0);
        $this->assertDatabaseCount('vehicles', 0);
    }


    public function test_it_fails_if_middle_numbers_exceed_four_digits()
    {   
        $payload = $this->getPayload('B 12345 JAW');

        $response = $this->actingAs($this->user)->postJson('/api/customers', $payload);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => "Format nomor polisi 'B 12345 JAW' tidak valid! Gunakan format standar (Contoh: B 1020 JAW)."
        ]);

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_it_fails_if_suffix_letters_exceed_three_digits()
    {
        $payload = $this->getPayload('B 1020 JAWA');

        $response = $this->actingAs($this->user)->postJson('/api/customers', $payload);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => "Format nomor polisi 'B 1020 JAWA' tidak valid! Gunakan format standar (Contoh: B 1020 JAW)."
        ]);

        $this->assertDatabaseCount('customers', 0);
    }
}