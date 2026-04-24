<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return response()->json($suppliers, 200);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['message' => 'Supplier gak ada brok'], 404);
        return response()->json(['status' => 'success', 'data' => $supplier], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Supplier ditambahkan', 'data' => $supplier], 201);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Supplier diupdate', 'data' => $supplier], 200);
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Supplier dihapus'], 200);
    }
}
