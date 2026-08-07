<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's single store profile.
     */
    public function run(): void
    {
        if (Store::query()->exists()) {
            return;
        }

        Store::create([
            'store_name' => 'Moon Trading',
            'owner_name' => 'Belal Hosain',
            'phone' => '01723074844',
            'email' => null,
            'address' => 'Central Road, Payrachottor',
            'city' => 'Rangpur',
            'logo' => null,
            'invoice_footer' => 'Formula: Due = Sales − Returns − Payments. Positive balance means customer owes.',
        ]);
    }
}
