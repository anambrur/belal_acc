<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Expense_category = [
            ['expense_category' => 'Home'],
            ['expense_category' => 'Bazar'],
            ['expense_category' => 'Employee Salary'],
            ['expense_category' => 'Personal'],
        ];
        ExpenseCategory::insert($Expense_category);
    }
}
