<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\ReplyThread;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReplyThread>
 */
class ReplyThreadFactory extends Factory
{

    protected $model = ReplyThread::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message_id' => Message::factory(),
            'reply_message_id' => Message::factory(),

        ];
    }
}
