<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemCategory::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);

        return response()->json($categories, 200);
    }

    public function show($id)
    {
        $category = ItemCategory::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kategori berhasil ditarik',
            'data' => $category
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'descriptions' => 'nullable|string',
        ]);

        $validated['employee_id'] = $request->user()->employees_id ?? 1;
        $validated['created_by'] = $request->user()->employees_id ?? 1;

        $category = ItemCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori baru berhasil ditambahkan!',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = ItemCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $id . ',category_id',
            'descriptions' => 'nullable|string',
        ]);

        $validated['edited_by'] = $request->user()->employees_id ?? 1;

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate!',
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = ItemCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus!',
        ], 200);
    }
}
