<?php
// database/seeders/HotelAmenitiesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Amenity;

class HotelAmenitiesTableSeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();
        $amenities = Amenity::all();

        foreach ($hotels as $hotel) {
            $hotel->amenities()->attach(
                $amenities->random(rand(3, 8))->pluck('id')->toArray()
            );
        }
    }
}
