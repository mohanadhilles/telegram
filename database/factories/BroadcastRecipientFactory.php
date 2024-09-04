<?php

namespace Database\Factories;

use App\Models\BroadcastMessage;
use App\Models\BroadcastRecipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BroadcastRecipient>
 */
class BroadcastRecipientFactory extends Factory
{

    protected $model = BroadcastRecipient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'broadcast_message_id' => BroadcastMessage::factory(),
            'user_id' => User::factory(),
            'is_read' => false,
        ];
    }
}
