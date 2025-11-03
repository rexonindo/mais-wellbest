<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display all items
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
    }

    // Show a single item
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    // Store a new item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'itm_cd' => 'required|string|max:50|unique:itm_tbl,itm_cd',
            'itm_nm' => 'required|string|max:100',
            'fg_flg' => 'nullable|boolean',
            'uom' => 'nullable|string|max:20',
            'std_rate' => 'nullable|numeric',
        ]);

        $item = Item::create($validated);
        return response()->json($item, 201);
    }

    // Update an item
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'itm_nm' => 'required|string|max:100',
            'fg_flg' => 'nullable|boolean',
            'uom' => 'nullable|string|max:20',
            'std_rate' => 'nullable|numeric',
        ]);

        $item->update($validated);
        return response()->json($item);
    }

    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
