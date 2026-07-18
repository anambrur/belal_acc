<?php

namespace Database\Seeders;

use App\Models\Road;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoadSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roads = [
            ['name' => 'Rangpur Sador'],
            ['name' => 'Razar Hat & Mahigonj'],
            ['name' => 'Pirgacha Road'],
            ['name' => 'Sothibari Road'],
            ['name' => 'Haragach Road'],
            ['name' => 'Joldhaka Road'],
            ['name' => 'Lalmonirhat Road'],
            ['name' => 'Badorganj Road'],
        ];

        foreach ($roads as $road) {
            Road::create(array_merge($road, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
