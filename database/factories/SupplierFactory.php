<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone'=> fake()->phoneNumber(),
            'address'=>fake()->citySuffix(),
            'photo'=>fake()->imageUrl(80,80,'supplier'),
            'total_purchase_due'=>fake()->randomDigit(),
            'total_purchase_return'=> fake()->randomDigit()
        ];
    }
}
