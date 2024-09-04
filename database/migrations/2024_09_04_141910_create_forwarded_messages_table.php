<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forwarded_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('forwarded_to_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forwarded_messages');
    }
};
