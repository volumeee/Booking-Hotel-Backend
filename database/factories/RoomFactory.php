<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition()
    {
        $roomType = RoomType::inRandomOrder()->first();
        return [
            'hotel_id' => $roomType->hotel_id,
            'room_type_id' => $roomType->id,
            'room_number' => $this->faker->unique()->numberBetween(100, 9999),
            'status' => $this->faker->randomElement(['tersedia', 'dipesan', 'dibersihkan']),
        ];
    }
}
