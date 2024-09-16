<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition()
    {
        return [
            'hotel_id' => Hotel::inRandomOrder()->first()->id,
            'report_type' => $this->faker->randomElement(['okupansi', 'pendapatan', 'kepuasan_pelanggan']),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'data' => json_encode([
                'total' => $this->faker->numberBetween(1000, 10000),
                'rata_rata' => $this->faker->randomFloat(2, 50, 100),
            ]),
        ];
    }
}
