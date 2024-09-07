<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Client as MongoClient;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $client = new MongoClient(env('MONGODB_DB_HOST'));
        $database = $client->selectDatabase(env('MONGODB_DB_DATABASE'));
        $chat = $database->selectCollection('chats');
        $receivers = $database->selectCollection('receivers');

        $chat->createIndex(['sender_id' => 1]);
        $chat->createIndex(['receiver_id' => 1]);
        $chat->createIndex(['group_id' => 1]);
        $chat->createIndex(['reply_to_message_id' => 1]);
        $chat->createIndex(['media_id' => 1]);
        $chat->createIndex(['created_at' => 1]);

        $receivers->createIndex(['name' => 1]);
        $receivers->createIndex(['photo' => 1]);
        $receivers->createIndex(['phone' => 1]);
        $receivers->createIndex(['email' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Connect to MongoDB
        $client = new MongoClient(env('MONGODB_DB_HOST'));
        $database = $client->selectDatabase(env('MONGODB_DB_DATABASE'));
        $chats = $database->selectCollection('chats');
        $receivers = $database->selectCollection('receivers');

        // Drop indexes
        $chats->dropIndex(['sender_id' => 1]);
        $chats->dropIndex(['receiver_id' => 1]);
        $chats->dropIndex(['group_id' => 1]);
        $chats->dropIndex(['reply_to_message_id' => 1]);
        $chats->dropIndex(['media_id' => 1]);
        $chats->dropIndex(['created_at' => 1]);


        $receivers->dropIndex(['name' => 1]);
        $receivers->dropIndex(['photo' => 1]);
        $receivers->dropIndex(['phone' => 1]);
        $receivers->dropIndex(['email' => 1]);
    }
};
