<?php

namespace Tests\Unit;

use App\Http\Controllers\SupplierController;
use App\Http\Services\SupplierService;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $supplierServiceMock;
    protected SupplierController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplierServiceMock = Mockery::mock(SupplierService::class);
        $this->controller          = new SupplierController($this->supplierServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // Helper: buat Supplier langsung ke DB (mengikuti pola makeSupplier di ServiceTest
    private function createSupplier(array $attrs): Supplier
    {
        return Supplier::create([
            'name'        => $attrs['name'],
            'description' => $attrs['description'] ?? null,
        ]);
    }

    // TC-INV-08.5 — Pencarian Valid (Data Ada)
    // Kata Kunci: 'PT. Sparepart Indo'
    // Expected : Sistem berhasil mengambil data dan menampilkannya sesuai parameter

    public function test_tc_inv_08_5_pencarian_keyword_exact_response_200()
    {
        // Arrange
        $this->createSupplier(['name' => 'PT. Sparepart Indo',  'description' => 'Supplier sparepart resmi']);
        $this->createSupplier(['name' => 'CV. Maju Bersama',    'description' => 'Supplier lainnya']);

        $request = Request::create('/suppliers', 'GET', [
            'search' => 'PT. Sparepart Indo',
        ]);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('total', $content);
    }

    public function test_tc_inv_08_5_keyword_valid_data_tidak_kosong()
    {
        // Arrange
        $this->createSupplier(['name' => 'PT. Sparepart Indo', 'description' => null]);

        $request = Request::create('/suppliers', 'GET', [
            'search' => 'PT. Sparepart Indo',
        ]);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — data ditemukan, tidak kosong
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($content['data'], 'Data seharusnya tidak kosong untuk keyword yang valid.');
    }

    public function test_tc_inv_08_5_pencarian_keyword_partial_mengembalikan_data_relevan()
    {
        // Arrange
        $this->createSupplier(['name' => 'PT. Sparepart Indo',      'description' => null]);
        $this->createSupplier(['name' => 'PT. Sparepart Nusantara', 'description' => null]);
        $this->createSupplier(['name' => 'CV. Lain-lain',           'description' => null]);

        $request = Request::create('/suppliers', 'GET', ['search' => 'Sparepart']);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — hanya 2 yang cocok
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $content['data'], 'Harus mengembalikan 2 supplier yang mengandung "Sparepart".');
    }

    public function test_tc_inv_08_5_pencarian_cocok_di_description_mengembalikan_data()
    {
        // Arrange — keyword cocok di description, bukan name
        $this->createSupplier([
            'name'        => 'Toko ABC',
            'description' => 'PT. Sparepart Indo distributor resmi',
        ]);

        $request = Request::create('/suppliers', 'GET', ['search' => 'PT. Sparepart Indo']);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($content['data'], 'Data harus ditemukan via kolom description.');
    }

    public function test_tc_inv_08_5_response_memiliki_struktur_paginasi_lengkap()
    {
        // Arrange
        $this->createSupplier(['name' => 'PT. Sparepart Indo', 'description' => null]);

        $request = Request::create('/suppliers', 'GET', [
            'search' => 'PT. Sparepart Indo',
            'limit'  => 10,
        ]);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — struktur paginasi Laravel lengkap
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('current_page', $content);
        $this->assertArrayHasKey('per_page', $content);
        $this->assertArrayHasKey('total', $content);
        $this->assertArrayHasKey('last_page', $content);
        $this->assertEquals(10, $content['per_page']);
    }

    // TC-INV-08.6 — Tanpa Parameter Search / Keyword Kosong (Empty State)
    // Kata Kunci: ' ' (spasi)
    // Expected : Sistem menampilkan "Data tidak ditemukan" atau tabel kosong
    
    public function test_tc_inv_08_6_tanpa_parameter_search_mengembalikan_semua_data()
    {
        // Arrange
        $this->createSupplier(['name' => 'Supplier A', 'description' => null]);
        $this->createSupplier(['name' => 'Supplier B', 'description' => null]);
        $this->createSupplier(['name' => 'Supplier C', 'description' => null]);

        $request = Request::create('/suppliers', 'GET', []);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — semua data tampil tanpa filter
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $content['data']);
    }

    public function test_tc_inv_08_6_keyword_spasi_response_200_dan_struktur_valid()
    {
        // Arrange — keyword berupa spasi ' ' sesuai test case
        $request = Request::create('/suppliers', 'GET', ['search' => ' ']);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('total', $content);
    }

    public function test_tc_inv_08_6_database_kosong_mengembalikan_empty_state()
    {
        // Arrange — tidak ada data di DB sama sekali
        $request = Request::create('/suppliers', 'GET', []);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — empty state
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($content['data'], 'Harus kosong jika tidak ada data di DB.');
        $this->assertEquals(0, $content['total']);
    }

    public function test_tc_inv_08_6_keyword_tidak_cocok_mengembalikan_empty_state()
    {
        // Arrange — ada data tapi keyword tidak cocok
        $this->createSupplier(['name' => 'CV. Maju Jaya', 'description' => null]);

        $request = Request::create('/suppliers', 'GET', ['search' => 'XYZ999NOTEXIST']);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — empty state
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($content['data'], 'Keyword tidak cocok harus menghasilkan data kosong.');
        $this->assertEquals(0, $content['total']);
    }

    public function test_tc_inv_08_6_default_limit_adalah_10_jika_tidak_diberikan()
    {
        // Arrange — buat 15 data, pastikan default limit 10 berlaku
        for ($i = 1; $i <= 15; $i++) {
            $this->createSupplier(['name' => "Supplier $i", 'description' => null]);
        }

        $request = Request::create('/suppliers', 'GET', []);

        // Act
        $response = $this->controller->index($request);
        $content  = json_decode($response->getContent(), true);

        // Assert — default per_page = 10
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(10, $content['per_page']);
        $this->assertCount(10, $content['data']);
    }
}