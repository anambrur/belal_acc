<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = [
            [
                'category' => 'Sari'
            ],
            [
                'category' => 'Lungi'
            ],
            [
                'category' => 'Shart'
            ],
            [
                'category' => 'Pant'
            ],
            [
                'category' => '3PC'
            ]
        ];
        Category::insert($category);
    }
}
