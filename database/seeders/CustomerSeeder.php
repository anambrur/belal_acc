<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = [
            [
                'customer_name' => 'Customer 1', 'phone' => '01723074844', 'road_id' => 1, 'location_id' => 1
            ],
            [
                'customer_name' => 'Customer 2', 'phone' => '01723074844', 'road_id' => 2, 'location_id' => 2
            ],
            [
                'customer_name' => 'Customer 3', 'phone' => '01723074844', 'road_id' => 3, 'location_id' => 3
            ]

        ];
        Customer::insert($customer);
    }
}
