<?php

namespace App\Http\Controllers;

use App\Models\ProductionLog;
use App\Models\WorkOrder;
use App\Models\Item;
use App\Models\Process;
use App\Models\Machine;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProductionLogController extends Controller
{
    public function index()
    {
        $logs = ProductionLog::with(['item', 'process', 'machine', 'employee'])
            ->orderBy('log_id', 'desc')
            ->get();

        $workOrders = WorkOrder::pluck('wo_no');
        $items = Item::pluck('itm_nm', 'itm_cd');
        $processes = Process::pluck('proc_nm', 'proc_cd');
        $machines = Machine::pluck('mchn_nm', 'mchn_cd');
        $employees = Employee::pluck('emp_nm', 'emp_id');

        return view('production.log', compact('logs', 'workOrders', 'items', 'processes', 'machines', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wo_no' => 'required|string',
            'itm_cd' => 'required|string',
            'proc_cd' => 'required|string',
            'mchn_cd' => 'nullable|string',
            'emp_id' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'in_qty' => 'numeric|min:0',
            'out_qty' => 'numeric|min:0',
            'ng_qty' => 'numeric|min:0',
            'rmks' => 'nullable|string',
        ]);

        ProductionLog::create($validated);

        return redirect()->route('prdlog.index')->with('success', 'Production log recorded successfully!');
    }
}
