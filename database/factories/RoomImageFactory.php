<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;


class RoomImageFactory extends Factory
{
    public function definition()
    {
        return [
            'room_type_id' => RoomType::inRandomOrder()->first()->id,
            'image_url' => $this->faker->imageUrl(640, 480, 'room'),
            'is_main' => $this->faker->boolean(),
        ];
    }
}
