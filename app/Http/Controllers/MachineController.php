<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        return response()->json(Machine::with('department')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mchn_cd' => 'required|string|max:50|unique:mchn_tbl,mchn_cd',
            'mchn_nm' => 'nullable|string|max:100',
            'dept_cd' => 'nullable|string|max:20',
            'uom' => 'nullable|string|max:20',
            'dsc' => 'nullable|string|max:50',
            'stats' => 'in:Running,Idle,Maintenance,Breakdown',
        ]);

        $machine = Machine::create($validated);

        return response()->json($machine, 201);
    }

    public function show($id)
    {
        return response()->json(Machine::with('department')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);

        $validated = $request->validate([
            'mchn_cd' => 'required|string|max:50|unique:mchn_tbl,mchn_cd,' . $id,
            'mchn_nm' => 'nullable|string|max:100',
            'dept_cd' => 'nullable|string|max:20',
            'uom' => 'nullable|string|max:20',
            'dsc' => 'nullable|string|max:50',
            'stats' => 'in:Running,Idle,Maintenance,Breakdown',
        ]);

        $machine->update($validated);

        return response()->json($machine);
    }

    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
        $machine->delete();

        return response()->json(null, 204);
    }
}