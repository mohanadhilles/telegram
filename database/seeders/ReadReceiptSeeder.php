<?php

namespace Database\Seeders;

use App\Models\ReadReceipt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReadReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReadReceipt::factory()->count(3000)->create();

    }
}
