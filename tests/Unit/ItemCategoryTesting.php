<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ItemCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-INV-01
     * Input kategori valid
     */
    public function test_create_category_success()
    {
        $category = ItemCategory::create([
            'name' => 'Aksesoris Eksterior',
            'descriptions' => 'Semua item body part'
        ]);

        $this->assertDatabaseHas('item_categories', [
            'name' => 'Aksesoris Eksterior'
        ]);
    }

    /**
     * TC-INV-01.2
     * Deskripsi kosong
     */
    public function test_create_category_without_description()
    {
        $category = ItemCategory::create([
            'name' => 'Mesin Turbo',
            'descriptions' => null
        ]);

        $this->assertDatabaseHas('item_categories', [
            'name' => 'Mesin Turbo'
        ]);
    }

    /**
     * TC-INV-01.3
     * Nama kosong → gagal validasi
     */
    public function test_category_name_required()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = new \Illuminate\Http\Request();
        $request->merge([
            'name' => null,
            'descriptions' => 'Tes'
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'descriptions' => 'nullable|string',
        ]);
    }

    /**
     * TC-INV-01.4
     * Nama duplikat → gagal validasi
     */
    public function test_duplicate_category_name()
    {
        ItemCategory::create([
            'name' => 'Aksesoris Eksterior'
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $request = new \Illuminate\Http\Request();
        $request->merge([
            'name' => 'Aksesoris Eksterior',
            'descriptions' => 'Coba duplikat'
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'descriptions' => 'nullable|string',
        ]);
    }

    /**
     * TC-INV-01.5
     * Search kategori
     */
    public function test_search_category()
    {
        ItemCategory::create([
            'name' => 'Filter Oli'
        ]);

        ItemCategory::create([
            'name' => 'Ban Mobil'
        ]);

        $result = ItemCategory::where(
            'name',
            'like',
            '%Filter%'
        )->get();

        $this->assertCount(1, $result);
    }
}