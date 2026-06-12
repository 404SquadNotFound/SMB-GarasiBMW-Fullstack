<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CarType;
use App\Models\EngineType;
use App\Models\User;
use App\Http\Controllers\CarTypeController;
use App\Http\Services\CarTypeService;
use Illuminate\Http\Request;
use Mockery;

class CarTypeControllerTest extends TestCase
{
    protected CarTypeController $controller;
    protected $carTypeServiceMock;
    protected $connectionMock;
    protected $originalResolver;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mock Database Connection untuk menghindari pemanggilan PDO/Database asli
        $this->connectionMock = Mockery::mock(\Illuminate\Database\Connection::class);
        $this->connectionMock->shouldReceive('getName')->andReturn('mysql');
        $this->connectionMock->shouldReceive('getTablePrefix')->andReturn('');

        $grammar = new \Illuminate\Database\Query\Grammars\MySqlGrammar($this->connectionMock);
        $processor = new \Illuminate\Database\Query\Processors\MySqlProcessor();
        $this->connectionMock->shouldReceive('getQueryGrammar')->andReturn($grammar);
        $this->connectionMock->shouldReceive('getPostProcessor')->andReturn($processor);

        // Tambahkan support untuk ->query() agar Eloquent Builder bisa di-instantiate
        $this->connectionMock->shouldReceive('query')->andReturnUsing(function() use ($grammar, $processor) {
            return new \Illuminate\Database\Query\Builder(
                $this->connectionMock,
                $grammar,
                $processor
            );
        });

        // Mock database transactions
        $this->connectionMock->shouldReceive('beginTransaction')->andReturn(null);
        $this->connectionMock->shouldReceive('commit')->andReturn(null);
        $this->connectionMock->shouldReceive('rollBack')->andReturn(null);

        // Simpan resolver asli Eloquent
        $this->originalResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();

        // Set custom resolver ke model Eloquent
        $resolver = new \Illuminate\Database\ConnectionResolver([
            'mysql' => $this->connectionMock
        ]);
        $resolver->setDefaultConnection('mysql');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

        // Mock PresenceVerifier untuk menghindari PDO check pada rule unique & exists di validator
        $presenceVerifierMock = Mockery::mock(\Illuminate\Validation\PresenceVerifierInterface::class);
        $presenceVerifierMock->shouldReceive('getCount')->andReturnUsing(function($collection, $column, $value, $excludeId, $idColumn, $extra) {
            // exist rule checks expect count > 0
            if ($collection === 'engine_types') {
                return 1;
            }
            // unique rule checks expect count == 0
            return 0;
        });
        $presenceVerifierMock->shouldReceive('setConnection')->andReturn(null);

        $this->app->extend('validator', function ($validatorFactory) use ($presenceVerifierMock) {
            $validatorFactory->setPresenceVerifier($presenceVerifierMock);
            return $validatorFactory;
        });

        // 2. Mock service karena controller butuh di-inject di constructor
        $this->carTypeServiceMock = Mockery::mock(CarTypeService::class);
        $this->controller = new CarTypeController($this->carTypeServiceMock);
    }

    protected function tearDown(): void
    {
        // Kembalikan resolver asli Eloquent
        if ($this->originalResolver) {
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($this->originalResolver);
        }

        Mockery::close();
        parent::tearDown();
    }

    // ────────────────────────────────────────────────────────────────────────
    // INDEX FILTER TESTING (Whitebox / Basis Path - TC-IDX-17 s/d TC-IDX-22)
    // ────────────────────────────────────────────────────────────────────────

    /**
     * TC-IDX-17 (Path 1): Eksekusi program tanpa ada input parameter filter apapun ("Bypass all").
     */
    public function test_index_path1_bypass_all_tc_idx_17()
    {
        // Mock query count dan select
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 2]];
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW M3',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => 1,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ],
                (object)[
                    'car_type_id' => 2,
                    'chassis_number' => 'E39',
                    'name' => 'BMW M5',
                    'series' => '5 Series',
                    'engine_code' => 'S62',
                    'engine_type_id' => 2,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request();
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(2, $items);
        $this->assertEquals('BMW M3', $items[0]->name);
        $this->assertEquals('BMW M5', $items[1]->name);
    }

    /**
     * TC-IDX-18 (Path 2): Eksekusi program hanya dengan input parameter search.
     */
    public function test_index_path2_only_search_tc_idx_18()
    {
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 1]];
            }
            // Pastikan binding search dikirim ke query jika ini adalah query car_types
            if (str_contains($query, 'car_types') && str_contains($query, 'like')) {
                $this->assertNotEmpty($bindings);
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW UNIQUE',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => 1,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request(['search' => 'BMW UNIQUE']);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals('BMW UNIQUE', $items[0]->name);
    }

    /**
     * TC-IDX-19 (Path 3): Eksekusi program hanya dengan input parameter series.
     */
    public function test_index_path3_only_series_tc_idx_19()
    {
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 1]];
            }
            // Cek filter series di binding jika ini adalah query car_types dan bukan count
            if (str_contains($query, 'car_types') && !str_contains($query, 'count(')) {
                $this->assertContains('3 Series', $bindings);
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW Series Match',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => 1,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request(['series' => '3 Series']);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals('BMW Series Match', $items[0]->name);
    }

    /**
     * TC-IDX-20 (Path 4): Eksekusi dengan engine_type_id terisi, namun ID mesin tidak ditemukan.
     */
    public function test_index_path4_engine_type_not_found_tc_idx_20()
    {
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            // Ketika mencari EngineType: "select * from engine_types where engine_type_id = ?"
            if (str_contains($query, 'engine_types')) {
                return []; // Kembalikan kosong (tidak ditemukan)
            }
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 1]];
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW Engine Match',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => null,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request(['engine_type_id' => 99999]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertNotEmpty($items);
    }

    /**
     * TC-IDX-21 (Path 5): Eksekusi dengan engine_type_id valid dan mesin ditemukan di DB.
     */
    public function test_index_path5_engine_type_found_tc_idx_21()
    {
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            // Ketika mencari EngineType
            if (str_contains($query, 'engine_types')) {
                return [
                    (object)[
                        'engine_type_id' => 1,
                        'name' => 'S54',
                    ]
                ];
            }
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 1]];
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW Engine Match',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => 1,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request(['engine_type_id' => 1]);
        $response = $this->controller->index($request);

        $items = $response->items();
        $this->assertCount(1, $items);
        $this->assertEquals('BMW Engine Match', $items[0]->name);
    }

    /**
     * TC-IDX-22 (Path 6): Eksekusi program dengan seluruh parameter filter terisi secara valid.
     */
    public function test_index_path6_full_path_combination_tc_idx_22()
    {
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            // Ketika mencari EngineType
            if (str_contains($query, 'engine_types')) {
                return [
                    (object)[
                        'engine_type_id' => 1,
                        'name' => 'S54',
                    ]
                ];
            }
            if (str_contains($query, 'count(')) {
                return [(object)['aggregate' => 1]];
            }
            return [
                (object)[
                    'car_type_id' => 1,
                    'chassis_number' => 'E46',
                    'name' => 'BMW M3 FULL MATCH',
                    'series' => '3 Series',
                    'engine_code' => 'S54',
                    'engine_type_id' => 1,
                    'created_by' => 1,
                    'created_at' => '2026-05-31 00:00:00',
                ]
            ];
        });

        $request = new Request([
            'search' => 'FULL MATCH',
            'series' => '3 Series',
            'engine_type_id' => 1
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
        $this->connectionMock->shouldReceive('select')->andReturn([
            (object)[
                'car_type_id' => 1,
                'chassis_number' => 'E46',
                'name' => 'BMW E46 M3',
                'series' => '3 Series',
                'engine_code' => 'S54',
                'engine_type_id' => 1,
                'created_by' => 1,
                'created_at' => '2026-05-31 00:00:00',
            ]
        ]);

        $response = $this->controller->show(1);
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
        $this->connectionMock->shouldReceive('select')->andReturn([]);

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
        // 1. Mock pemanggilan EngineType::whereIn
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            if (str_contains($query, 'engine_types')) {
                return [
                    (object)['name' => 'S54'],
                    (object)['name' => 'M54']
                ];
            }
            return [];
        });

        // 2. Mock Insert Query
        $this->connectionMock->shouldReceive('insert')->once()->andReturn(true);
        $this->connectionMock->shouldReceive('getLastInsertId')->andReturn(10);

        $request = new Request();
        $request->replace([
            'chassis_number' => 'E46',
            'name'           => 'BMW M3 CSL',
            'series'         => '3 Series',
            'engine_ids'     => [1, 2],
        ]);

        $user = new User();
        $user->employees_id = 42;
        $request->setUserResolver(fn() => $user);

        $response = $this->controller->store($request);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('BMW M3 CSL', $result['data']['name']);
        $this->assertEquals('S54, M54', $result['data']['engine_code']);
        $this->assertEquals(1, $result['data']['engine_type_id']);
        $this->assertEquals(42, $result['data']['created_by']);
    }

    /**
     * Test Update: Mengubah data yang sudah ada dengan sukses
     */
    public function test_update_updates_car_type_successfully()
    {
        // 1. Mock select untuk findOrFail (menemukan model yang mau di-update)
        $this->connectionMock->shouldReceive('select')->andReturnUsing(function($query, $bindings) {
            if (str_contains($query, 'car_types')) {
                return [
                    (object)[
                        'car_type_id' => 5,
                        'chassis_number' => 'E39',
                        'name' => 'BMW 528i',
                        'series' => '5 Series',
                        'engine_code' => 'M52',
                        'engine_type_id' => 1,
                        'created_by' => 1,
                    ]
                ];
            }
            if (str_contains($query, 'engine_types')) {
                return [
                    (object)['name' => 'M52B28']
                ];
            }
            return [];
        });

        // 2. Mock Update Query
        $this->connectionMock->shouldReceive('update')->once()->andReturn(1);

        $request = new Request();
        $request->replace([
            'chassis_number' => 'E39 LCI',
            'name'           => 'BMW 528i Executive',
            'series'         => '5 Series LCI',
            'engine_ids'     => [1],
        ]);

        $user = new User();
        $user->employees_id = 99;
        $request->setUserResolver(fn() => $user);

        $response = $this->controller->update($request, 5);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Data terupdate', $result['message']);
    }

    /**
     * Test Destroy: Menghapus data mobil dengan sukses
     */
    public function test_destroy_deletes_car_type()
    {
        // 1. Mock select untuk findOrFail (menemukan model yang mau di-delete)
        $this->connectionMock->shouldReceive('select')->andReturn([
            (object)[
                'car_type_id' => 5,
                'chassis_number' => 'E39',
                'name' => 'BMW 528i',
                'series' => '5 Series',
                'engine_code' => 'M52',
                'engine_type_id' => 1,
            ]
        ]);

        // 2. Mock Delete Query
        $this->connectionMock->shouldReceive('delete')->once()->andReturn(1);

        $response = $this->controller->destroy(5);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tipe Mobil dihapus', $result['message']);
    }

    /**
     * Test getUniqueSeries: Mengembalikan seri unik secara alfabetis
     */
    public function test_get_unique_series_returns_distinct_sorted_series()
    {
        $this->connectionMock->shouldReceive('select')->andReturn([
            (object)['series' => '3 Series'],
            (object)['series' => '5 Series'],
            (object)['series' => 'Z Series'],
        ]);

        $response = $this->controller->getUniqueSeries();
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $result['status']);

        $expected = ['3 Series', '5 Series', 'Z Series'];
        $this->assertEquals($expected, $result['data']);
    }
}
