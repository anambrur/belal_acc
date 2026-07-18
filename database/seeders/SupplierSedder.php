<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = [
            [
                'supplier_name' => 'Abdul Rehman', 'phone' => '01723074844', 'road_id' => 1,'location_id'=>1
            ],
            [
                'supplier_name' => 'Kalam Hossen', 'phone' => '01714613653', 'road_id' => 2,'location_id'=>2
            ],
            [
                'supplier_name' => 'Basar Ali', 'phone' => '01714613653', 'road_id' => 3,'location_id'=>3
            ],
            [
                'supplier_name' => 'Nazim Hossen', 'phone' => '01714613653', 'road_id' => 1,'location_id'=>1
            ]
        ];
        Supplier::insert($supplier);
    }
}
