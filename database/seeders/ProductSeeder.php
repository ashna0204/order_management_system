<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
    ['name' => 'Product A', 'price' => 100],
    ['name' => 'Product B', 'price' => 200],
    ['name' => 'Product C', 'price' => 300],
]);

    }
}
