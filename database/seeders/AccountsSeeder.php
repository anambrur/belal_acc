<?php

namespace Database\Seeders;

use App\Models\Accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'bank_name' => 'Cash',
                'branch_name' => 'Cash',
                'account_number' => 'Shop',
                'date' => date('Y-m-d'),
                'amount' => 0,
                'description' => 'Cash',
                'status' => 1
            ],
            [
                'bank_name' => 'Bkash',
                'branch_name' => 'Bkash',
                'account_number' => '01714613653',
                'date' => date('Y-m-d'),
                'amount' => 0,
                'description' => 'Bkash',
                'status' => 1
            ],
            
        ];
        Accounts::insert($accounts);
    }
}
