<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;


class HotelImageFactory extends Factory
{
    public function definition()
    {
        return [
            'hotel_id' => Hotel::inRandomOrder()->first()->id,
            'image_url' => $this->faker->imageUrl(640, 480, 'hotel'),
            'is_main' => $this->faker->boolean(),
        ];
    }
}
