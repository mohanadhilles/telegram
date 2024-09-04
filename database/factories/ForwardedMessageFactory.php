<?php

namespace Database\Factories;

use App\Models\ForwardedMessage;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForwardedMessage>
 */
class ForwardedMessageFactory extends Factory
{

    protected $model = ForwardedMessage::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_message_id' => Message::factory(),
            'forwarded_to_user_id' => \App\Models\User::factory(),

        ];
    }
}
