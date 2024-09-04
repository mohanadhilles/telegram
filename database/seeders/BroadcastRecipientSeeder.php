<?php

namespace Database\Seeders;

use App\Models\BroadcastRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BroadcastRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming that BroadcastMessage and Users are already seeded,
        // here we link them as recipients.
        $broadcastMessages = \App\Models\BroadcastMessage::all();
        $users = \App\Models\User::all();

        foreach ($broadcastMessages as $message) {
            $randomUsers = $users->random(5); // Select random 5 users per message
            foreach ($randomUsers as $user) {
                BroadcastRecipient::factory()->create([
                    'broadcast_message_id' => $message->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
