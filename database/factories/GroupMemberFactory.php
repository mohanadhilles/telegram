<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupMember>
 */
class GroupMemberFactory extends Factory
{
    protected $model = \App\Models\GroupMember::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Fetch existing group IDs and user IDs to avoid duplicates
        $groupIds = Group::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Ensure there are existing groups and users
        if (empty($groupIds) || empty($userIds)) {
            throw new \Exception('No groups or users available for seeding.');
        }

        // Generate a unique combination of group_id and user_id
        $groupId = $this->faker->randomElement($groupIds);
        $userId = $this->faker->randomElement($userIds);

        return [
            'group_id' => $groupId,
            'user_id' => $userId,
        ];
    }
}
