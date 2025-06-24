<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(10);
        return view('layouts.customer.index', ['customers' => $customers]);
    }

    public function create()
    {
        return view('layouts.customer.create');
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateFields($request);
        Customer::where('id', $id)->update($validated);
        return redirect()->route('customer.index')->with('success', 'Customer Edited!');
    }

    public function show(Customer $customer)
    {
        $customer->with('site'); //to load in any relations
        //show/edit uses the same form for create
        return view('layouts.customer.create', ["customer" => $customer]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFields($request);
        Customer::create($validated);
        return redirect()->route('customer.index')->with('success', 'Customer Created!');
    }

    private function validateFields(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_number' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'address_3' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'telephone_1' => 'required|string|max:255',
            'telephone_2' => 'nullable|string|max:255',
        ]);
        return $validated;
    }
}
