<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return Employee::all();
    }

    public function show($id)
    {
        return Employee::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'emp_id' => 'required|unique:empl_tbl,emp_id',
            'emp_nm' => 'required',
            'psition' => 'nullable|string|max:100',
            'dept_cd' => 'nullable|string|max:20',
            'shift_cd' => 'nullable|string|max:20',
            'stats' => 'in:Active,Inactive',
        ]);

        return Employee::create($validated);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'emp_id' => 'required|unique:empl_tbl,emp_id,' . $id,
            'emp_nm' => 'required',
            'psition' => 'nullable|string|max:100',
            'dept_cd' => 'nullable|string|max:20',
            'shift_cd' => 'nullable|string|max:20',
            'stats' => 'in:Active,Inactive',
        ]);

        $employee->update($validated);

        return $employee;
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->noContent();
    }
}
