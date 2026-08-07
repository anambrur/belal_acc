<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Category::factory(5)->create();
        // \App\Models\Unit::factory(5)->create();
        // \App\Models\Supplier::factory(5)->create();
        // \App\Models\Product::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);




        $this->call(BatchSedder::class);
        $this->call(CategorySedder::class);
        $this->call(UnitSedder::class);
        $this->call(CompanySeeder::class);
        $this->call(ProductSedder::class);
        $this->call(LocationSeeder::class);
        $this->call(RoadSedder::class);
        $this->call(CustomerSeeder::class);
        $this->call(SupplierSedder::class);
        $this->call(PurchaseSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(ExpenseCategorySeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AccountsSeeder::class);
        $this->call(StoreSeeder::class);
    }
}
