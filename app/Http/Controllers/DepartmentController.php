<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_cd' => 'required|string|max:20|unique:dept_tbl,dept_cd',
            'dept_nm' => 'required|string|max:100',
            'descrp' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
                         ->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'dept_cd' => 'required|string|max:20|unique:dept_tbl,dept_cd,' . $department->id,
            'dept_nm' => 'required|string|max:100',
            'descrp' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
                         ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')
                         ->with('success', 'Department deleted successfully.');
    }
}