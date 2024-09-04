<?php

namespace Database\Factories;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BroadcastMessage>
 */
class BroadcastMessageFactory extends Factory
{

    protected $model = BroadcastMessage::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'content' => $this->faker->sentence,
            'media_type' => $this->faker->randomElement(['image', 'video', 'audio', 'document']),
            'media_url' => $this->faker->url,
        ];
    }
}
