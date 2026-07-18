<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = [
            [
                'company_name' => 'Pakija'
            ],
            [
                'company_name' => 'Sohan'
            ],
            [
                'company_name' => 'Jhilik'
            ],
            [
                'company_name' => 'Standard'
            ],
            [
                'company_name' => 'Nila'
            ]
        ];
        Company::insert($company);
    }
}
