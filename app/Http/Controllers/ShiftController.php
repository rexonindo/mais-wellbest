<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        return Shift::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_cd' => 'required|string|max:20|unique:shift_tbl',
            'shift_nm' => 'required|string|max:50',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        return Shift::create($validated);
    }

    public function show($id)
    {
        return Shift::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->update($request->all());
        return $shift;
    }

    public function destroy($id)
    {
        Shift::destroy($id);
        return response()->noContent();
    }
}
