<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class NotificationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence(),
            'is_read' => $this->faker->boolean(),
        ];
    }
}
