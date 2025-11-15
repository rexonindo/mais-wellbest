<?php

namespace App\Http\Controllers;

use App\Models\ToolingCavity;
use Illuminate\Http\Request;

class ToolingCavityController extends Controller
{
    public function index()
    {
        return ToolingCavity::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'itm_cd' => 'required|string|max:50',
            'tool_cd' => 'required|string|max:50',
            'proc_cd' => 'required|string|max:50',
            'cav' => 'required|integer|min:1',
        ]);

        $data['created_by'] = auth()->user()->name ?? 'system';
        return ToolingCavity::create($data);
    }

    public function update(Request $request, ToolingCavity $toolingCavity)
    {
        $data = $request->validate([
            'tool_cd' => 'string|max:50',
            'cav' => 'integer|min:1',
        ]);

        $data['updated_by'] = auth()->user()->name ?? 'system';
        $toolingCavity->update($data);

        return $toolingCavity;
    }

    public function destroy(ToolingCavity $toolingCavity)
    {
        $toolingCavity->delete();
        return response()->noContent();
    }
}
