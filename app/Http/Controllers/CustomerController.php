<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cust_cd' => 'required|string|max:20|unique:cust_tbl',
            'cust_nm' => 'required|string|max:100',
            'address' => 'nullable|string',
            'telp' => 'nullable|string|max:50',
        ]);

        $data['created_by'] = auth()->user()->name ?? 'system';
        return Customer::create($data);
    }

    public function show(Customer $customer)
    {
        return $customer;
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'cust_cd' => 'required|string|max:20|unique:cust_tbl,cust_cd,' . $customer->id,
            'cust_nm' => 'required|string|max:100',
            'address' => 'nullable|string',
            'telp' => 'nullable|string|max:50',
        ]);

        $data['updated_by'] = auth()->user()->name ?? 'system';
        $customer->update($data);

        return $customer;
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->noContent();
    }
}
