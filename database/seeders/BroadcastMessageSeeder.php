<?php

namespace Database\Seeders;

use App\Models\BroadcastMessage;
use Illuminate\Database\Seeder;

class BroadcastMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BroadcastMessage::factory()->count(10)->create();

    }
}
