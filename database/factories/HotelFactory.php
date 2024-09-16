<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition()
    {
        $kota = $this->faker->city();
        return [
            'name' => "Hotel " . $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'city' => $kota,
            'country' => 'Indonesia',
            'zip_code' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'star_rating' => $this->faker->numberBetween(1, 5),
            'latitude' => $this->faker->latitude(-11, 6),
            'longitude' => $this->faker->longitude(95, 141),
        ];
    }
}
