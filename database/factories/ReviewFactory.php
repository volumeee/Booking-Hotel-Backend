<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;


class ReviewFactory extends Factory
{
    public function definition()
    {
        $booking = Booking::inRandomOrder()->first();
        return [
            'user_id' => $booking->user_id,
            'hotel_id' => $booking->hotel_id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
        ];
    }
}
