<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{
    public function definition()
    {
        return [
            'hotel_id' => Hotel::inRandomOrder()->first()->id,
            'name' => $this->faker->randomElement(['Standar', 'Deluxe', 'Suite', 'Family', 'Presidential']),
            'description' => $this->faker->paragraph(),
            'capacity' => $this->faker->numberBetween(1, 6),
            'price_per_night' => $this->faker->numberBetween(500000, 5000000),
        ];
    }
}
