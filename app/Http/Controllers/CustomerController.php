<?php

namespace App\Http\Controllers;
use App\Models\Customer;


use Illuminate\Http\Request;

class CustomerController extends Controller
{
    
public function getCustomer($id)
{
    $customer = Customer::findOrFail($id);
    return response()->json([
        'address' => $customer->address,
    ]);
}

}
