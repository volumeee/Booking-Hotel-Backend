<?php
// database/seeders/AmenitiesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitiesTableSeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            'WiFi',
            'Swimming Pool',
            'Gym',
            'Restaurant',
            'Bar',
            'Parking',
            'Room Service',
            'Spa',
            'Air Conditioning',
            'TV'
        ];

        foreach ($amenities as $amenity) {
            Amenity::create(['name' => $amenity]);
        }
    }
}
