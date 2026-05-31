<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CarType;
use App\Models\EngineType;
use App\Models\User;
use App\Http\Controllers\CarTypeController;
use App\Http\Services\CarTypeService;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class CarTypeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected CarTypeController $controller;
    protected $carTypeServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        if (!extension_loaded('pdo_sqlite')) {
            config(['database.default' => 'mysql']);
        }

        $this->carTypeServiceMock = Mockery::mock(CarTypeService::class);
        $this->controller = new CarTypeController($this->carTypeServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function createDummyEngine(string $name): EngineType
    {
        return EngineType::create([
            'name'       => $name,
            'cylinders'  => '6 Silinder',
            'oil_cap'    => 6.5,
            'fuel_type'  => 'Bensin',
            'engine_cap' => 2979,
        ]);
    }

    private function createDummyCar(array $override = []): CarType
    {
        return CarType::create(array_merge([
            'chassis_number' => 'E46',
            'name'           => 'BMW 325i',
            'series'         => '3 Series',
            'engine_code'    => 'M54B25',
            'engine_type_id' => null,
            'created_by'     => null,
        ], $override));
    }

    // ────────────────────────────────────────────────────────────────────────
    // INDEX FILTER TESTING (Whitebox / Basis Path - TC-IDX-17 s/d TC-IDX-22)
    // ────────────────────────────────────────────────────────────────────────

    /**
     * TC-IDX-17 (Path 1): Eksekusi program tanpa ada input parameter filter apapun ("Bypass all").
     * Payload: search = null, series = null, engine_type_id = null.
     * Kode berjalan melewati semua pengecekan IF bernilai False.
     */
    public function test_index_path1_bypass_all_tc_idx_17()
    {
        $uniqueName1 = 'BMW_M3_' . uniqid();
        $uniqueName2 = 'BMW_M5_' . uniqid();
        $this->createDummyCar(['name' => $uniqueName1]);
        $this->createDummyCar(['name' => $uniqueName2]);

        $request = new Request();
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertNotEmpty($items);

        $names = collect($items)->pluck('name')->toArray();
        $this->assertContains($uniqueName1, $names);
        $this->assertContains($uniqueName2, $names);
    }

    /**
     * TC-IDX-18 (Path 2): Eksekusi program hanya dengan input parameter search.
     * Payload: search = "BMW_UNIQUE", series = null, engine_type_id = null.
     * Kode masuk ke blok pencarian (Node 7-13) dan bypass IF lainnya.
     */
    public function test_index_path2_only_search_tc_idx_18()
    {
        $uniqueName = 'BMW_UNIQUE_' . uniqid();
        $this->createDummyCar(['name' => $uniqueName]);
        $this->createDummyCar(['name' => 'BMW_OTHER_' . uniqid()]);

        $request = new Request(['search' => $uniqueName]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals($uniqueName, $items[0]->name);
    }

    /**
     * TC-IDX-19 (Path 3): Eksekusi program hanya dengan input parameter series.
     * Payload: search = null, series = "3 Series", engine_type_id = null.
     * Kode bypass search, masuk ke blok series (Node 17-18).
     */
    public function test_index_path3_only_series_tc_idx_19()
    {
        $uniqueSeries = 'SERIES_' . uniqid();
        $this->createDummyCar(['series' => $uniqueSeries, 'name' => 'BMW Series Match']);
        $this->createDummyCar(['series' => 'OTHER_' . uniqid(), 'name' => 'BMW Series Other']);

        $request = new Request(['series' => $uniqueSeries]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals('BMW Series Match', $items[0]->name);
    }

    /**
     * TC-IDX-20 (Path 4): Eksekusi dengan engine_type_id terisi, namun ID mesin tidak ditemukan.
     * Payload: search = null, series = null, engine_type_id = 99999.
     * Program masuk blok mesin (Node 22-24). Karena mesin tidak ditemukan, program lompat blok if terdalam.
     */
    public function test_index_path4_engine_type_not_found_tc_idx_20()
    {
        $uniqueCode = 'ENGCODE_' . uniqid();
        $this->createDummyCar(['engine_code' => $uniqueCode, 'name' => 'BMW Engine Match']);

        $request = new Request(['engine_type_id' => 99999]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $names = collect($items)->pluck('name')->toArray();
        $this->assertContains('BMW Engine Match', $names);
    }

    /**
     * TC-IDX-21 (Path 5): Eksekusi dengan engine_type_id valid dan mesin ditemukan di DB.
     * Payload: search = null, series = null, engine_type_id = [valid].
     * Program mengeksekusi blok IF terdalam (Node 25-27) mengambil nama mesin untuk query.
     */
    public function test_index_path5_engine_type_found_tc_idx_21()
    {
        $uniqueEngineName = 'ENGNAME_' . uniqid();
        $engine = $this->createDummyEngine($uniqueEngineName);

        $this->createDummyCar(['engine_code' => $uniqueEngineName . ' Turbo', 'name' => 'BMW Engine Match']);
        $this->createDummyCar(['engine_code' => 'OTHER_CODE', 'name' => 'BMW Engine Other']);

        $request = new Request(['engine_type_id' => $engine->engine_type_id]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals('BMW Engine Match', $items[0]->name);
    }

    /**
     * TC-IDX-22 (Path 6 / Full Execution Path): Eksekusi program dengan seluruh parameter filter terisi secara valid.
     * Payload: search = "BMW", series = "3 Series", engine_type_id = [valid].
     * Kode masuk ke semua blok IF tanpa ter-bypass satupun.
     */
    public function test_index_path6_full_path_combination_tc_idx_22()
    {
        $uniqueEngineName = 'FULLENG_' . uniqid();
        $engine = $this->createDummyEngine($uniqueEngineName);

        $uniqueSeries = 'FULLSERIES_' . uniqid();

        $this->createDummyCar([
            'name'           => 'BMW M3 FULL MATCH',
            'chassis_number' => 'E46',
            'series'         => $uniqueSeries,
            'engine_code'    => $uniqueEngineName,
            'engine_type_id' => $engine->engine_type_id
        ]);

        $this->createDummyCar([
            'name'           => 'OTHER M5',
            'chassis_number' => 'E39',
            'series'         => $uniqueSeries,
            'engine_code'    => $uniqueEngineName,
            'engine_type_id' => $engine->engine_type_id
        ]);

        $request = new Request([
            'search'         => 'FULL MATCH',
            'series'         => $uniqueSeries,
            'engine_type_id' => $engine->engine_type_id
        ]);

        $response = $this->controller->index($request);
        $items = $response->items();

        $this->assertCount(1, $items);
        $this->assertEquals('BMW M3 FULL MATCH', $items[0]->name);
    }

    // ────────────────────────────────────────────────────────────────────────
    // CRUD METHOD TESTING
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Test Show: ID valid ditemukan
     */
    public function test_show_valid_id_returns_data()
    {
        $car = $this->createDummyCar(['name' => 'BMW E46 M3']);

        $response = $this->controller->show($car->car_type_id);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('BMW E46 M3', $result['data']['name']);
    }

    /**
     * Test Show: ID tidak valid menghasilkan 404
     */
    public function test_show_invalid_id_returns_404()
    {
        $response = $this->controller->show(99999);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Data mobil gak ketemu', $result['message']);
    }

    /**
     * Test Store: Menyimpan data baru dengan sukses
     */
    public function test_store_creates_car_type_successfully()
    {
        $engine1 = $this->createDummyEngine('S54');
        $engine2 = $this->createDummyEngine('M54');

        $request = new Request();
        $request->replace([
            'chassis_number' => 'E46',
            'name'           => 'BMW M3 CSL',
            'series'         => '3 Series',
            'engine_ids'     => [$engine1->engine_type_id, $engine2->engine_type_id],
        ]);

        // Mock User Login untuk Request
        $user = new User();
        $user->employees_id = 42;
        $request->setUserResolver(fn() => $user);

        $response = $this->controller->store($request);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('BMW M3 CSL', $result['data']['name']);

        // Cek nama-nama mesin yang digabung koma
        $this->assertEquals('S54, M54', $result['data']['engine_code']);

        // Cek engine_type_id di-set ke elemen pertama
        $this->assertEquals($engine1->engine_type_id, $result['data']['engine_type_id']);
        $this->assertEquals(42, $result['data']['created_by']);
    }

    /**
     * Test Update: Mengubah data yang sudah ada dengan sukses
     */
    public function test_update_updates_car_type_successfully()
    {
        $car = $this->createDummyCar([
            'chassis_number' => 'E39',
            'name'           => 'BMW 528i',
            'series'         => '5 Series',
        ]);

        $engine = $this->createDummyEngine('M52B28');

        $request = new Request();
        $request->replace([
            'chassis_number' => 'E39 LCI',
            'name'           => 'BMW 528i Executive',
            'series'         => '5 Series LCI',
            'engine_ids'     => [$engine->engine_type_id],
        ]);

        $user = new User();
        $user->employees_id = 99;
        $request->setUserResolver(fn() => $user);

        $response = $this->controller->update($request, $car->car_type_id);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Data terupdate', $result['message']);

        // Ambil data terbaru dari DB
        $updatedCar = CarType::find($car->car_type_id);
        $this->assertEquals('E39 LCI', $updatedCar->chassis_number);
        $this->assertEquals('BMW 528i Executive', $updatedCar->name);
        $this->assertEquals('5 Series LCI', $updatedCar->series);
        $this->assertEquals('M52B28', $updatedCar->engine_code);
        $this->assertEquals(99, $updatedCar->edited_by);
    }

    /**
     * Test Destroy: Menghapus data mobil dengan sukses
     */
    public function test_destroy_deletes_car_type()
    {
        $car = $this->createDummyCar();

        $response = $this->controller->destroy($car->car_type_id);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tipe Mobil dihapus', $result['message']);

        // Pastikan data benar-benar terhapus dari DB
        $this->assertNull(CarType::find($car->car_type_id));
    }

    /**
     * Test getUniqueSeries: Mengembalikan seri unik secara alfabetis
     */
    public function test_get_unique_series_returns_distinct_sorted_series()
    {
        $uniqueSeriesA = 'A_Series_' . uniqid();
        $uniqueSeriesB = 'B_Series_' . uniqid();

        $this->createDummyCar(['series' => $uniqueSeriesB]);
        $this->createDummyCar(['series' => $uniqueSeriesA]);
        $this->createDummyCar(['series' => $uniqueSeriesA]); // Duplikat

        $response = $this->controller->getUniqueSeries();
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);

        $data = $result['data'];
        $this->assertContains($uniqueSeriesA, $data);
        $this->assertContains($uniqueSeriesB, $data);

        // Seri harus unik dan berurutan secara ascending (A sebelum B)
        $indexA = array_search($uniqueSeriesA, $data);
        $indexB = array_search($uniqueSeriesB, $data);
        $this->assertTrue($indexA < $indexB, 'Seri A harus muncul sebelum Seri B (diurutkan ascending)');
    }
}
