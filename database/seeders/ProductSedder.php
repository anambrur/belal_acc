<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSedder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $product = [
      [
        'product_name' => 'Demo Product', 'bn_name' => 'ডেমো প্রোডাক্ট', 'category_id' => 1, 'unit_id' => 1, 'batch_id' => 1, 'price' => 999, 'company_id' => 1
      ],
      [
        'product_name' => 'Test Product', 'bn_name' => 'টেস্ট প্রোডাক্ট', 'category_id' => 2, 'unit_id' => 2, 'batch_id' => 2, 'price' => 650, 'company_id' => 2
      ],
      [
        'product_name' => 'Check Product', 'bn_name' => 'চেক প্রোডাক্ট', 'category_id' => 3, 'unit_id' => 3, 'batch_id' => 3, 'price' => 820, 'company_id' => 3
      ]
    ];
    Product::insert($product);
  }
}
