<?php

namespace App\Http\Controllers;

use App\Models\TransactionItem;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class TransactionItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:service_transactions,transaction_id',
            'item_type' => 'required|in:Service,Parts',
            'item_name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'spare_part_id' => 'nullable|exists:spareparts,sparepart_id',
        ]);

        $validated['subtotal'] = $validated['qty'] * $validated['price'];

        if ($validated['item_type'] == 'Parts' && $validated['spare_part_id']) {
            $sparepart = Sparepart::findOrFail($validated['spare_part_id']);
            if ($sparepart->quantity < $validated['qty']) {
                return response()->json(['status' => 'error', 'message' => 'Stok tidak mencukupi'], 400);
            }
            $sparepart->decrement('quantity', $validated['qty']);
        }

        $item = TransactionItem::create($validated);
        return response()->json(['status' => 'success', 'message' => 'Item ditambahkan', 'data' => $item], 201);
    }

    public function destroy($id)
    {
        $item = TransactionItem::findOrFail($id);

        if ($item->item_type == 'Parts' && $item->spare_part_id) {
            Sparepart::where('sparepart_id', $item->spare_part_id)->increment('quantity', $item->qty);
        }

        $item->delete();
        return response()->json(['status' => 'success', 'message' => 'Item dihapus dan stok dikembalikan'], 200);
    }
}
