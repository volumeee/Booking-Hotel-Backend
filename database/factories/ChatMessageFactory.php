<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class ChatMessageFactory extends Factory
{
    public function definition()
    {
        $user = User::where('role_id', Role::where('name', 'User')->first()->id)->inRandomOrder()->first();
        $admin = User::where('role_id', Role::where('name', 'Admin')->first()->id)->inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'admin_id' => $admin->id,
            'message' => $this->faker->sentence(),
            'is_from_user' => $this->faker->boolean(),
        ];
    }
}
