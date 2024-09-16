<?php

namespace Database\Factories;

use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelAmenityFactory extends Factory
{
    protected $model = HotelAmenity::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'amenity_id' => Amenity::factory(),
        ];
    }
}
