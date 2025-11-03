<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Item;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workorders = WorkOrder::with('item')->get();
        return view('workorders.index', compact('workorders'));
    }

    public function create()
    {
        $items = Item::all();
        return view('workorders.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wo_no' => 'required|string|max:50',
            'itm_cd' => 'required|string|max:50|exists:itm_tbl,itm_cd',
            'po_no' => 'required|string|max:50',
            'req_dt' => 'nullable|date',
            'plan_qty' => 'nullable|numeric',
            'start_dt' => 'nullable|date',
            'end_dt' => 'nullable|date',
            'stats' => 'nullable|in:Planned,In Progress,Completed,Cancelled',
        ]);

        WorkOrder::create($validated);

        return redirect()->route('workorders.index')->with('success', 'Work Order created successfully.');
    }

    public function edit($wo_no, $itm_cd)
    {
        $workorder = WorkOrder::where('wo_no', $wo_no)->where('itm_cd', $itm_cd)->firstOrFail();
        $items = Item::all();
        return view('workorders.edit', compact('workorder', 'items'));
    }

    public function update(Request $request, $wo_no, $itm_cd)
    {
        $workorder = WorkOrder::where('wo_no', $wo_no)->where('itm_cd', $itm_cd)->firstOrFail();

        $validated = $request->validate([
            'plan_qty' => 'nullable|numeric',
            'req_dt' => 'nullable|date',
            'start_dt' => 'nullable|date',
            'end_dt' => 'nullable|date',
            'stats' => 'nullable|in:Planned,In Progress,Completed,Cancelled',
        ]);

        $workorder->update($validated);

        return redirect()->route('workorders.index')->with('success', 'Work Order updated successfully.');
    }

    public function destroy($wo_no, $itm_cd)
    {
        $workorder = WorkOrder::where('wo_no', $wo_no)->where('itm_cd', $itm_cd)->firstOrFail();
        $workorder->delete();

        return redirect()->route('workorders.index')->with('success', 'Work Order deleted successfully.');
    }
}
