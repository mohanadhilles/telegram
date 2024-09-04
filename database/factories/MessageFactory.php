<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'group_id' => Group::factory(),
            'content' => $this->faker->text,
            'media_type' => $this->faker->randomElement(['image', 'video', 'audio', 'document']),
            'media_url' => $this->faker->url,
            'is_read' => $this->faker->boolean,
            'is_forwarded' => $this->faker->boolean,
        ];
    }
}
