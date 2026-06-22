<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('kode_customer', 'asc')->get();
        return view('admin.customer', compact('customers'));
    }
}
