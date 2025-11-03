<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionLog;
use Illuminate\Http\Request;

class ProductionLogController extends Controller
{
    public function index()
    {
        return response()->json(ProductionLog::all());
    }

    public function show($id)
    {
        $log = ProductionLog::find($id);
        if (!$log) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json($log);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wo_no' => 'required|string',
            'itm_cd' => 'required|string',
            'proc_cd' => 'required|string',
            'in_qty' => 'nullable|numeric',
            'out_qty' => 'nullable|numeric',
            'ng_qty' => 'nullable|numeric',
            'mchn_cd' => 'nullable|string',
            'emp_id' => 'nullable|string',
        ]);

        $log = ProductionLog::create($validated);
        return response()->json($log, 201);
    }

    public function update(Request $request, $id)
    {
        $log = ProductionLog::find($id);
        if (!$log) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $log->update($request->all());
        return response()->json($log);
    }

    public function destroy($id)
    {
        $log = ProductionLog::find($id);
        if (!$log) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $log->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
