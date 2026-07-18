<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit = [
            [
                'unit' => 'Pices'
            ],
            [
                'unit' => 'Gojs'
            ],
            [
                'unit' => 'Dojon'
            ]
        ];
        Unit::insert($unit);
    }
}
