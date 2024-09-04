<?php

namespace Database\Seeders;

use App\Models\ForwardedMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForwardedMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ForwardedMessage::factory()->count(3000)->create();

    }
}
