<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{

    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['emoji', 'sticker', 'gif'];
        $type = $this->faker->randomElement($types);

        return [
            'type' => $type,
            'url' => $type === 'emoji' ? null : $this->faker->imageUrl(),
            'code' => $type === 'emoji' ? $this->faker->word : null,
            'description' => $this->faker->sentence,
        ];
    }
}
