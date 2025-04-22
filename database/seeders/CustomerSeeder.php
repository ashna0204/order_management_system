<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'address' => '123 Main Street, New York',
            ],
            [
                'name' => 'Jane Smith',
                'address' => '456 Oak Avenue, Los Angeles',
            ],
            [
                'name' => 'Alice Johnson',
                'address' => '789 Pine Road, Chicago',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
