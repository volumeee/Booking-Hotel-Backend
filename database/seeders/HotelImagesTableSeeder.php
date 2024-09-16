<?php
// database/seeders/HotelImagesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelImage;
use App\Models\Hotel;

class HotelImagesTableSeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            HotelImage::factory()->count(rand(3, 8))->create([
                'hotel_id' => $hotel->id,
            ]);
        }
    }
}
