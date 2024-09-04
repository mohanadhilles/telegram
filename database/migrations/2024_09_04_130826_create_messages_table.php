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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->text('content')->nullable();
            $table->string('media_type')->nullable(); // e.g., 'image', 'video', 'audio', 'document'
            $table->text('media_url')->nullable(); // URL or path to the media file
            $table->boolean('is_read')->default(false);
            $table->boolean('is_forwarded')->default(false);
            $table->foreignId('reply_to_message_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
