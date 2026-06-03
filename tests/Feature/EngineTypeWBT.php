<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EngineType;
use App\Http\Controllers\EngineTypeController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

/**
 * White Box Testing — EngineTypeController::store()
 *
 * Function yang diuji:
 *   public function store(Request $request)
 *
 * 3 Path yang diuji:
 *   Path 1: N1→N2→N3→N4→N5→N6→N7→N8→N9→N10→N11
 *           Validasi lolos + DB sukses → HTTP 201
 *
 *   Path 2: N1→N2→N3→N4→N5→N6→N7→N8→END
 *           Validasi gagal → HTTP 422 (ValidationException)
 *
 *   Path 3: N1→N2→N3→N4→N5→N6→N7→N8→N9→N10→END
 *           Validasi lolos + DB error → HTTP 500 (QueryException)
 */
class EngineTypeStoreTest extends TestCase
{
    use RefreshDatabase;

    protected EngineTypeController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new EngineTypeController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // =========================================================
    // HELPER: Buat Request dengan data valid
    // =========================================================
    private function makeRequest(array $overrides = []): Request
    {
        $data = array_merge([
            'name'       => 'M54B30',
            'cylinders'  => 'Inline 6',
            'engine_cap' => 2979,
            'oil_cap'    => 6.5,
            'fuel_type'  => 'Bensin',
        ], $overrides);

        $request = Request::create('/api/engine-types', 'POST', $data);
        $request->headers->set('Accept', 'application/json');

        // Mock user dengan employees_id
        $user = Mockery::mock(\App\Models\User::class)->makePartial();
        $user->employees_id = 1;
        $request->setUserResolver(fn() => $user);

        return $request;
    }

    // =========================================================
    // TC-JM-WBT-01 | PATH 1
    // N1→N2→N3→N4→N5→N6→N7→N8→N9→N10→N11
    // Kondisi : Semua validasi lolos + DB sukses
    // Expected: HTTP 201, data tersimpan
    // =========================================================
    public function test_path1_store_berhasil_simpan_data_valid()
    {
        // Arrange
        $request = $this->makeRequest();

        // Act
        $response = $this->controller->store($request);
        $responseData = json_decode($response->getContent(), true);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Tipe Mesin ditambahkan', $responseData['message']);
        $this->assertNotNull($responseData['data']);
        $this->assertEquals('M54B30', $responseData['data']['name']);
        $this->assertEquals('Inline 6', $responseData['data']['cylinders']);
        $this->assertEquals(2979, $responseData['data']['engine_cap']);
        $this->assertEquals(6.5, $responseData['data']['oil_cap']);
        $this->assertEquals('Bensin', $responseData['data']['fuel_type']);

        // Pastikan benar-benar masuk ke DB
        $this->assertDatabaseHas('engine_types', [
            'name'      => 'M54B30',
            'fuel_type' => 'Bensin',
        ]);
    }

    // =========================================================
    // TC-JM-WBT-01b | PATH 1 (variasi Diesel)
    // N1→N2→N3→N4→N5→N6→N7→N8→N9→N10→N11
    // Kondisi : fuel_type = Diesel (masih valid, in:Bensin,Diesel)
    // Expected: HTTP 201, data tersimpan dengan fuel_type Diesel
    // =========================================================
    public function test_path1_store_berhasil_dengan_fuel_type_diesel()
    {
        // Arrange
        $request = $this->makeRequest([
            'name'       => 'N57D30',
            'cylinders'  => 'Inline 6',
            'engine_cap' => 2993,
            'oil_cap'    => 7.0,
            'fuel_type'  => 'Diesel',
        ]);

        // Act
        $response = $this->controller->store($request);
        $responseData = json_decode($response->getContent(), true);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Diesel', $responseData['data']['fuel_type']);

        $this->assertDatabaseHas('engine_types', [
            'name'      => 'N57D30',
            'fuel_type' => 'Diesel',
        ]);
    }

    // =========================================================
    // TC-JM-WBT-02 | PATH 2
    // N1→N2→N3→N4→N5→N6→N7→N8→END
    // Kondisi : fuel_type di luar enum Bensin/Diesel → validasi gagal
    // Expected: ValidationException (HTTP 422)
    // =========================================================
    public function test_path2_store_gagal_validasi_fuel_type_tidak_valid()
    {
        // Arrange
        $this->expectException(ValidationException::class);

        $request = $this->makeRequest([
            'fuel_type' => 'Solar', // Tidak ada di in:Bensin,Diesel
        ]);

        // Act — harus throw ValidationException
        $this->controller->store($request);
    }

    // =========================================================
    // TC-JM-WBT-02b | PATH 2 (variasi field kosong)
    // N1→N2→N3→N4→N5→N6→N7→N8→END
    // Kondisi : name kosong → required rule gagal
    // Expected: ValidationException (HTTP 422)
    // =========================================================
    public function test_path2_store_gagal_validasi_name_kosong()
    {
        // Arrange
        $this->expectException(ValidationException::class);

        $request = $this->makeRequest([
            'name' => '', // Melanggar rule required
        ]);

        // Act — harus throw ValidationException
        $this->controller->store($request);
    }

    // =========================================================
    // TC-JM-WBT-02c | PATH 2 (variasi numeric)
    // N1→N2→N3→N4→N5→N6→N7→N8→END
    // Kondisi : engine_cap berupa string → numeric rule gagal
    // Expected: ValidationException (HTTP 422)
    // =========================================================
    public function test_path2_store_gagal_validasi_engine_cap_bukan_angka()
    {
        // Arrange
        $this->expectException(ValidationException::class);

        $request = $this->makeRequest([
            'engine_cap' => 'dua ribu', // Melanggar rule numeric
        ]);

        // Act — harus throw ValidationException
        $this->controller->store($request);
    }

    // =========================================================
    // TC-JM-WBT-03 | PATH 3
    // N1→N2→N3→N4→N5→N6→N7→N8→N9→N10→END
    // Kondisi : Validasi lolos + DB error saat create()
    // Expected: QueryException (HTTP 500)
    // =========================================================
    public function test_path3_store_gagal_db_error_saat_create()
    {
        // Arrange
        $this->expectException(QueryException::class);

        $request = $this->makeRequest();

        // Mock EngineType::create() agar lempar QueryException
        $pdoException = new \PDOException('SQLSTATE[42S02]: Table not found');
        $pdoException->errorInfo = ['42S02', 1146, 'Table not found'];

        EngineType::saving(function () use ($pdoException) {
            throw new QueryException(
                'mysql',
                'INSERT INTO `engine_types`',
                [],
                $pdoException
            );
        });

        // Act — harus throw QueryException
        $this->controller->store($request);
    }
}