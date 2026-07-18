<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'admin', 'email' => 'admin@gmail.com', 'phone' => '01700000000', 'address' => 'Dhaka',  'status' => 'Approved', 'password' => Hash::make('12345678')]);
    }
}
