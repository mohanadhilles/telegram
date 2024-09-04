<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\ReadReceipt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReadReceipt>
 */
class ReadReceiptFactory extends Factory
{

    protected $model = ReadReceipt::class;

    public function definition()
    {
        return [
            'message_id' => Message::factory(),
            'user_id' => User::factory(),
        ];
    }

}
