<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class BookingFactory extends Factory
{
    public function definition()
    {
        $room = Room::inRandomOrder()->first();
        $checkIn = $this->faker->dateTimeBetween('now', '+2 months');
        $checkOut = $this->faker->dateTimeBetween($checkIn, $checkIn->format('Y-m-d H:i:s') . ' +2 weeks');

        return [
            'user_id' => User::where('role_id', Role::where('name', 'User')->first()->id)->inRandomOrder()->first()->id,
            'hotel_id' => $room->hotel_id,
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_price' => $this->faker->numberBetween(1000000, 10000000),
            'status' => $this->faker->randomElement(['menunggu', 'dikonfirmasi', 'dibatalkan']),
        ];
    }
}
