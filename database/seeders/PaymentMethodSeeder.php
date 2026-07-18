<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethod = [
            ['payment_method' => 'Cash'],
            ['payment_method' => 'Check'],
            ['payment_method' => 'Bkash'],
            ['payment_method' => 'Nagot'],
            ['payment_method' => 'Weekly']
        ];
        PaymentMethod::insert($paymentMethod);
    }
}
