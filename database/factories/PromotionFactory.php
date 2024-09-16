<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;


class PromotionFactory extends Factory
{
    public function definition()
    {
        return [
            'hotel_id' => Hotel::inRandomOrder()->first()->id,
            'name' => 'Promo ' . $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'discount_percentage' => $this->faker->numberBetween(5, 50),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}
