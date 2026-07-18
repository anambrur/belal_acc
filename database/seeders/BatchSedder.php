<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchs = [
            ['batch_name' => 'Batch 1'],
            ['batch_name' => 'Batch 2'],
            ['batch_name' => 'Batch 3'],
        ];

        foreach ($batchs as $batch) {
            Batch::create($batch);
        }
    }
}
