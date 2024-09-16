<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmenityFactory extends Factory
{
    protected $model = Amenity::class;

    protected $amenities = [
        'WiFi',
        'Kolam Renang',
        'Pusat Kebugaran',
        'Restoran',
        'Bar',
        'Parkir',
        'Layanan Kamar',
        'Spa',
        'AC',
        'TV'
    ];

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement($this->amenities),
        ];
    }
}
