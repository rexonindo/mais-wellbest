<?php

namespace App\Http\Controllers;

use App\Models\ProductRoute;
use App\Models\Process;
use Illuminate\Http\Request;

class ProductRouteController extends Controller
{
    public function index()
    {
        $routes = ProductRoute::with('process')->get();
        return view('productroute.index', compact('routes'));
    }

    public function create()
    {
        $processes = Process::pluck('proc_nm', 'proc_cd');
        return view('productroute.create', compact('processes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'itm_type' => 'required|string|max:50',
            'seq_no' => 'required|integer',
            'proc_cd' => 'required|string|max:50|exists:proc_tbl,proc_cd',
        ]);

        ProductRoute::create($validated);

        return redirect()->route('productroute.index')->with('success', 'Product Route created successfully.');
    }

    public function edit($itm_type, $seq_no, $proc_cd)
    {
        $route = ProductRoute::where(compact('itm_type', 'seq_no', 'proc_cd'))->firstOrFail();
        $processes = Process::pluck('proc_nm', 'proc_cd');
        return view('productroute.edit', compact('route', 'processes'));
    }

    public function update(Request $request, $itm_type, $seq_no, $proc_cd)
    {
        $validated = $request->validate([
            'proc_cd' => 'required|string|max:50|exists:proc_tbl,proc_cd',
        ]);

        $route = ProductRoute::where(compact('itm_type', 'seq_no', 'proc_cd'))->firstOrFail();
        $route->update($validated);

        return redirect()->route('productroute.index')->with('success', 'Product Route updated successfully.');
    }

    public function destroy($itm_type, $seq_no, $proc_cd)
    {
        ProductRoute::where(compact('itm_type', 'seq_no', 'proc_cd'))->delete();
        return redirect()->route('productroute.index')->with('success', 'Product Route deleted successfully.');
    }
}
