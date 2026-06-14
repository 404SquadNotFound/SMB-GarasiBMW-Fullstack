<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeStoreTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = Employee::create([
            'name' => 'Admin Tester',
            'join_date' => '2025-01-01',
            'birth_date' => '2000-01-01',
            'address' => 'Bandung',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'base_salary' => 8000000,
            'created_by' => 1
        ]);

        Sanctum::actingAs($this->user);
    }
    public function test_tambah_pegawai_input_valid()
    {
        $response = $this->postJson('/api/employees', [
            'name' => 'Budi Santoso',
            'join_date' => '2025-01-01',
            'birth_date' => '2000-01-01',
            'address' => 'Bandung',
            'email' => 'budi@test.com',
            'password' => '123456',
            'role' => 'finance',
            'base_salary' => 5000000
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'status' => 'success'
        ]);
    }
    public function test_tambah_pegawai_inputan_invalid()
    {
        $response = $this->postJson('/api/employees', [
            'name' => '',
            'join_date' => '2025-01-01',
            'birth_date' => '2000-01-01',
            'address' => 'Bandung',
            'email' => 'kosong@test.com',
            'password' => '123456',
            'role' => 'finance',
            'base_salary' => 5000000
        ]);

        $response->assertStatus(422);
    }
}