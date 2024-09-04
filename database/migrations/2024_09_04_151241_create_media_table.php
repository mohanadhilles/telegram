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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['emoji', 'sticker', 'gif']); // Type of media
            $table->string('url')->nullable(); // URL to the media file
            $table->string('code')->nullable(); // Code for emojis
            $table->string('description')->nullable(); // Description or name
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
