<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Channel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'channel_id' => function() {
                return Channel::factory()->create()->id;
            },
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
        ];
    }
}
