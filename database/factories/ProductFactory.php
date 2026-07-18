<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'barcode'=>fake()->ean8(),
            'product_name'=>fake()->word(),
            'category_id'=>fake()->randomDigitNot(6,7,8,9,0),
            'unit_id'=>fake()->randomDigitNot(6,7,8,9,0),
            'price'=>fake()->randomDigit(),
            'supplier_id'=>fake()->randomDigitNot(6,7,8,9,0),
            'photo'=>fake()->imageUrl(80,80,'supplier'),
        ];
    }
}
