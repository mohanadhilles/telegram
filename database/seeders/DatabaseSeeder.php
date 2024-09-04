<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            GroupChatSeeder::class,
            MessageSeeder::class,
            ReadReceiptSeeder::class,
            GroupMemberSeeder::class,
            ForwardedMessageSeeder::class,
            ReplyThreadSeeder::class,
            MediaSeeder::class,
            BroadcastMessageSeeder::class,
            BroadcastRecipientSeeder::class,
        ]);
    }
}
