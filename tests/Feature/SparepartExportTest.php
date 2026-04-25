<?php

namespace Tests\Feature; // ← disesuaikan dengan lokasi folder tests/Feature/

use App\Http\Controllers\SparepartController;
use App\Http\Services\ExportService;
use App\Http\Services\PdfExportService;
use App\Models\CarType;
use App\Models\ItemCategory;
use App\Models\Sparepart;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Mockery;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class SparepartExportTest extends TestCase
{
    use RefreshDatabase;

    private SparepartController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new SparepartController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    private function seedCategory(string $name = 'Oli'): ItemCategory
    {
        $cat = new ItemCategory();
        $cat->name = $name;
        $cat->save();
        return $cat;
    }

    private function seedSupplier(string $name = 'Supplier A'): Supplier
    {
        $sup = new Supplier();
        $sup->name = $name;
        $sup->save();
        return $sup;
    }

    private function seedCarType(string $name = 'BMW 3 Series'): CarType
    {
        $ct = new CarType();
        $ct->name = $name;
        $ct->save();
        return $ct;
    }

    private function seedSparepart(array $overrides = []): Sparepart
    {
        $cat = $this->seedCategory();
        $sup = $this->seedSupplier();

        return Sparepart::create(array_merge([
            'item_category_id' => $cat->category_id,
            'supplier_id'      => $sup->supplier_id,
            'car_type_id'      => null,
            'item_code'        => 'SP-' . uniqid(),
            'name'             => 'Oli Mesin',
            'category'         => 'Pelumas',
            'cost_off_sell'    => 50000,
            'selling_price'    => 75000,
            'quantity'         => 10,
            'date'             => now()->toDateString(),
            'created_by'       => 1,
        ], $overrides));
    }

    // ─────────────────────────────────────────────────────────────
    // index()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function index_returns_paginated_spareparts()
    {
        $this->seedSparepart();
        $this->seedSparepart(['name' => 'Filter Udara', 'item_code' => 'SP-FA01', 'category' => 'Filter']);

        $request  = Request::create('/api/spareparts', 'GET', ['limit' => 10]);
        $response = $this->controller->index($request);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertCount(2, $response->items());
    }

    /** @test */
    public function index_filters_by_search_name()
    {
        $this->seedSparepart(['name' => 'Filter Udara', 'item_code' => 'SP-FA01', 'category' => 'Filter']);
        $this->seedSparepart(['name' => 'Oli Mesin',   'item_code' => 'SP-OL01', 'category' => 'Pelumas']);

        $request  = Request::create('/api/spareparts', 'GET', ['search' => 'Filter']);
        $response = $this->controller->index($request);

        $this->assertCount(1, $response->items());
        $this->assertEquals('Filter Udara', $response->items()[0]->name);
    }

    /** @test */
    public function index_filters_by_category()
    {
        $this->seedSparepart(['name' => 'Filter Udara', 'item_code' => 'SP-FA01', 'category' => 'Filter']);
        $this->seedSparepart(['name' => 'Oli Mesin',   'item_code' => 'SP-OL01', 'category' => 'Pelumas']);

        $request  = Request::create('/api/spareparts', 'GET', ['category' => 'Filter']);
        $response = $this->controller->index($request);

        $this->assertCount(1, $response->items());
        $this->assertEquals('Filter', $response->items()[0]->category);
    }

    /** @test */
    public function index_filters_by_supplier_id()
    {
        $sup1 = $this->seedSupplier('Supplier X');
        $sup2 = $this->seedSupplier('Supplier Y');
        $cat  = $this->seedCategory();

        Sparepart::create(['item_category_id' => $cat->category_id, 'supplier_id' => $sup1->supplier_id, 'car_type_id' => null, 'item_code' => 'SP-X01', 'name' => 'Busi NGK',   'category' => 'Pengapian', 'cost_off_sell' => 15000, 'selling_price' => 25000, 'quantity' => 20, 'date' => now()->toDateString(), 'created_by' => 1]);
        Sparepart::create(['item_category_id' => $cat->category_id, 'supplier_id' => $sup2->supplier_id, 'car_type_id' => null, 'item_code' => 'SP-Y01', 'name' => 'Kampas Rem', 'category' => 'Rem',       'cost_off_sell' => 80000, 'selling_price' => 120000, 'quantity' => 5, 'date' => now()->toDateString(), 'created_by' => 1]);

        $request  = Request::create('/api/spareparts', 'GET', ['supplier_id' => $sup1->supplier_id]);
        $response = $this->controller->index($request);

        $this->assertCount(1, $response->items());
        $this->assertEquals('Busi NGK', $response->items()[0]->name);
    }

    /** @test */
    public function index_returns_empty_when_no_match()
    {
        $this->seedSparepart(['name' => 'Oli Mesin', 'item_code' => 'SP-OL01']);

        $request  = Request::create('/api/spareparts', 'GET', ['search' => 'zzz_tidak_ada']);
        $response = $this->controller->index($request);

        $this->assertCount(0, $response->items());
    }

    // ─────────────────────────────────────────────────────────────
    // show()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function show_returns_sparepart_when_found()
    {
        $sparepart = $this->seedSparepart();

        $response = $this->controller->show($sparepart->sparepart_id);
        $data     = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data['status']);
        $this->assertEquals($sparepart->sparepart_id, $data['data']['sparepart_id']);
    }

    /** @test */
    public function show_returns_404_when_not_found()
    {
        $response = $this->controller->show(99999);
        $data     = $response->getData(true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('error', $data['status']);
        $this->assertEquals('Data suku cadang tidak ditemukan', $data['message']);
    }

    // ─────────────────────────────────────────────────────────────
    // store()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function store_creates_sparepart_and_returns_201()
    {
        $cat = $this->seedCategory();
        $sup = $this->seedSupplier();

        $request = Request::create('/api/spareparts', 'POST', [
            'item_category_id' => $cat->category_id,
            'supplier_id'      => $sup->supplier_id,
            'item_code'        => 'SP-NEW01',
            'name'             => 'Kampas Kopling',
            'category'         => 'Transmisi',
            'cost_off_sell'    => 200000,
            'selling_price'    => 300000,
            'quantity'         => 15,
            'date'             => now()->toDateString(),
        ]);

        $response = $this->controller->store($request);
        $data     = $response->getData(true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Kampas Kopling', $data['data']['name']);
        $this->assertDatabaseHas('spareparts', ['item_code' => 'SP-NEW01']);
    }

    /** @test */
    public function store_fails_validation_when_required_fields_missing()
    {
        $this->expectException(ValidationException::class);

        $request = Request::create('/api/spareparts', 'POST', []);
        $this->controller->store($request);
    }

    /** @test */
    public function store_fails_when_item_code_duplicate()
    {
        $this->expectException(ValidationException::class);

        $sparepart = $this->seedSparepart(['item_code' => 'SP-DUP01']);

        $cat = ItemCategory::find($sparepart->item_category_id);
        $sup = Supplier::find($sparepart->supplier_id);

        $request = Request::create('/api/spareparts', 'POST', [
            'item_category_id' => $cat->category_id,
            'supplier_id'      => $sup->supplier_id,
            'item_code'        => 'SP-DUP01', // duplikat
            'name'             => 'Sparepart Lain',
            'category'         => 'Umum',
            'cost_off_sell'    => 10000,
            'selling_price'    => 15000,
            'quantity'         => 5,
            'date'             => now()->toDateString(),
        ]);

        $this->controller->store($request);
    }

    /** @test */
    public function store_fails_when_quantity_is_negative()
    {
        $this->expectException(ValidationException::class);

        $cat = $this->seedCategory();
        $sup = $this->seedSupplier();

        $request = Request::create('/api/spareparts', 'POST', [
            'item_category_id' => $cat->category_id,
            'supplier_id'      => $sup->supplier_id,
            'item_code'        => 'SP-NEG01',
            'name'             => 'Barang Minus',
            'category'         => 'Umum',
            'cost_off_sell'    => 10000,
            'selling_price'    => 15000,
            'quantity'         => -5, // tidak valid
            'date'             => now()->toDateString(),
        ]);

        $this->controller->store($request);
    }

    // ─────────────────────────────────────────────────────────────
    // update()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function update_modifies_sparepart_and_returns_200()
    {
        $sparepart = $this->seedSparepart();
        $cat       = ItemCategory::find($sparepart->item_category_id);

        $request = Request::create("/api/spareparts/{$sparepart->sparepart_id}", 'PUT', [
            'item_category_id' => $cat->category_id,
            'supplier_id'      => $sparepart->supplier_id,
            'name'             => 'Oli Mesin Updated',
            'cost_off_sell'    => 60000,
            'selling_price'    => 90000,
            'quantity'         => 20,
        ]);

        $response = $this->controller->update($request, $sparepart->sparepart_id);
        $data     = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Oli Mesin Updated', $data['data']['name']);
        $this->assertDatabaseHas('spareparts', [
            'sparepart_id'  => $sparepart->sparepart_id,
            'name'          => 'Oli Mesin Updated',
            'selling_price' => 90000,
        ]);
    }

    /** @test */
    public function update_fails_validation_when_quantity_is_negative()
    {
        $this->expectException(ValidationException::class);

        $sparepart = $this->seedSparepart();
        $cat       = ItemCategory::find($sparepart->item_category_id);

        $request = Request::create("/api/spareparts/{$sparepart->sparepart_id}", 'PUT', [
            'item_category_id' => $cat->category_id,
            'name'             => 'Oli Mesin',
            'cost_off_sell'    => 50000,
            'selling_price'    => 75000,
            'quantity'         => -1,
        ]);

        $this->controller->update($request, $sparepart->sparepart_id);
    }

    // ─────────────────────────────────────────────────────────────
    // destroy()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function destroy_deletes_sparepart_and_returns_200()
    {
        $sparepart = $this->seedSparepart();
        $id        = $sparepart->sparepart_id;

        $response = $this->controller->destroy($id);
        $data     = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data['status']);
        $this->assertDatabaseMissing('spareparts', ['sparepart_id' => $id]);
    }

    /** @test */
    public function destroy_throws_404_when_sparepart_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->controller->destroy(99999);
    }

    // ─────────────────────────────────────────────────────────────
    // lowStock()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function low_stock_returns_spareparts_with_quantity_5_or_less()
    {
        $this->seedSparepart(['item_code' => 'SP-LOW1', 'quantity' => 2]);
        $this->seedSparepart(['item_code' => 'SP-LOW2', 'quantity' => 5]);
        $this->seedSparepart(['item_code' => 'SP-OK1',  'quantity' => 50]);

        $response = $this->controller->lowStock();
        $data     = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('success', $data['status']);
        $this->assertCount(2, $data['data']);
        $this->assertEquals(2, $data['data'][0]['quantity']); // ascending
    }

    /** @test */
    public function low_stock_returns_maximum_3_items()
    {
        foreach (range(1, 4) as $i) {
            $this->seedSparepart(['item_code' => "SP-L{$i}", 'quantity' => $i]);
        }

        $response = $this->controller->lowStock();
        $data     = $response->getData(true);

        $this->assertCount(3, $data['data']); // limit 3
    }

    /** @test */
    public function low_stock_returns_empty_when_all_stock_sufficient()
    {
        $this->seedSparepart(['item_code' => 'SP-OK1', 'quantity' => 100]);

        $response = $this->controller->lowStock();
        $data     = $response->getData(true);

        $this->assertEquals('success', $data['status']);
        $this->assertCount(0, $data['data']);
    }

    // ─────────────────────────────────────────────────────────────
    // getFilterOptions()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function get_filter_options_returns_categories_and_suppliers()
    {
        $this->seedSparepart(['item_code' => 'SP-A', 'category' => 'Pelumas']);
        $this->seedSparepart(['item_code' => 'SP-B', 'category' => 'Filter']);

        $request  = Request::create('/api/sparepart-options', 'GET');
        $response = $this->controller->getFilterOptions($request);
        $data     = $response->getData(true);

        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('categories', $data['data']);
        $this->assertArrayHasKey('suppliers', $data['data']);
        $this->assertContains('Filter', $data['data']['categories']);
        $this->assertContains('Pelumas', $data['data']['categories']);
    }

    /** @test */
    public function get_filter_options_filters_categories_by_supplier()
    {
        $cat  = $this->seedCategory();
        $sup1 = $this->seedSupplier('Sup Alpha');
        $sup2 = $this->seedSupplier('Sup Beta');

        Sparepart::create(['item_category_id' => $cat->category_id, 'supplier_id' => $sup1->supplier_id, 'car_type_id' => null, 'item_code' => 'SP-C1', 'name' => 'Busi', 'category' => 'Pengapian', 'cost_off_sell' => 15000, 'selling_price' => 25000, 'quantity' => 10, 'date' => now()->toDateString(), 'created_by' => 1]);
        Sparepart::create(['item_category_id' => $cat->category_id, 'supplier_id' => $sup2->supplier_id, 'car_type_id' => null, 'item_code' => 'SP-C2', 'name' => 'Oli',  'category' => 'Pelumas',   'cost_off_sell' => 50000, 'selling_price' => 75000, 'quantity' => 5,  'date' => now()->toDateString(), 'created_by' => 1]);

        $request  = Request::create('/api/sparepart-options', 'GET', ['supplier_id' => $sup1->supplier_id]);
        $response = $this->controller->getFilterOptions($request);
        $data     = $response->getData(true);

        $this->assertContains('Pengapian', $data['data']['categories']);
        $this->assertNotContains('Pelumas', $data['data']['categories']);
    }

    // ─────────────────────────────────────────────────────────────
    // exportExcel()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function export_excel_calls_export_service_with_correct_headers()
    {
        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->withArgs(function ($fileName, $headers) {
                return $fileName === 'Data_Suku_Cadang.xlsx'
                    && $headers === ['Kode Barang', 'Nama Suku Cadang', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok'];
            })
            ->andReturn(new StreamedResponse(fn() => null, 200));

        $response = $this->controller->exportExcel($exportService);
        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    /** @test */
    public function export_excel_maprow_formats_sparepart_with_object_category()
    {
        $category       = new ItemCategory();
        $category->name = 'Pelumas';

        $sparepart                = new Sparepart();
        $sparepart->item_code     = 'SP-001';
        $sparepart->name          = 'Oli Mesin';
        $sparepart->cost_off_sell = 50000;
        $sparepart->selling_price = 75000;
        $sparepart->quantity      = 10;
        $sparepart->setRelation('category', $category);

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('SP-001',    $row[0]);
                $this->assertEquals('Oli Mesin', $row[1]);
                $this->assertEquals('Pelumas',   $row[2]);
                $this->assertEquals(50000,        $row[3]);
                $this->assertEquals(75000,        $row[4]);
                $this->assertEquals(10,           $row[5]);
                return new StreamedResponse(fn() => null, 200);
            });

        $this->controller->exportExcel($exportService);
    }

    /** @test */
    public function export_excel_maprow_uses_fallback_when_no_category()
    {
        $sparepart                = new Sparepart();
        $sparepart->item_code     = 'SP-002';
        $sparepart->name          = 'Busi';
        $sparepart->cost_off_sell = 20000;
        $sparepart->selling_price = 30000;
        $sparepart->quantity      = 5;

        $exportService = Mockery::mock(ExportService::class);
        $exportService->shouldReceive('exportToExcel')
            ->once()
            ->andReturnUsing(function ($fileName, $headers, $q, $mapRow) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('-', $row[2]); // fallback tanpa category
                return new StreamedResponse(fn() => null, 200);
            });

        $this->controller->exportExcel($exportService);
    }

    // ─────────────────────────────────────────────────────────────
    // exportPdf()
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function export_pdf_calls_pdf_export_service_with_correct_options()
    {
        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->withArgs(function ($fileName, $query, $mapRow, $options) {
                return str_starts_with($fileName, 'Laporan_Suku_Cadang_')
                    && str_ends_with($fileName, '.pdf')
                    && $options['title'] === 'Laporan Data Suku Cadang'
                    && $options['paper'] === 'a4'
                    && $options['orientation'] === 'portrait';
            })
            ->andReturn(response()->make('pdf-content', 200));

        $response = $this->controller->exportPdf($pdfService);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function export_pdf_maprow_formats_price_as_rupiah()
    {
        $category       = new ItemCategory();
        $category->name = 'Pelumas';

        $sparepart                = new Sparepart();
        $sparepart->item_code     = 'SP-PDF01';
        $sparepart->name          = 'Oli Mesin';
        $sparepart->cost_off_sell = 50000;
        $sparepart->selling_price = 75000;
        $sparepart->quantity      = 10;
        $sparepart->setRelation('category', $category);

        $pdfService = Mockery::mock(PdfExportService::class);
        $pdfService->shouldReceive('export')
            ->once()
            ->andReturnUsing(function ($fileName, $query, $mapRow, $options) use ($sparepart) {
                $row = $mapRow($sparepart);
                $this->assertEquals('SP-PDF01', $row['Kode Barang']);
                $this->assertEquals('Oli Mesin', $row['Nama Suku Cadang']);
                $this->assertEquals('Pelumas', $row['Kategori']);
                $this->assertStringStartsWith('Rp ', $row['Harga Beli']);
                $this->assertStringStartsWith('Rp ', $row['Harga Jual']);
                $this->assertEquals(10, $row['Stok']);
                return response()->make('ok', 200);
            });

        $this->controller->exportPdf($pdfService);
    }
}
