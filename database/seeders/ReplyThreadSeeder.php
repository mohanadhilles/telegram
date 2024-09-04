<?php

namespace Database\Seeders;

use App\Models\ReplyThread;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReplyThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReplyThread::factory()->count(3000)->create();

    }
}
