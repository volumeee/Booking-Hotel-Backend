<?php
// database/seeders/RoomImagesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomImage;
use App\Models\RoomType;

class RoomImagesTableSeeder extends Seeder
{
    public function run()
    {
        $roomTypes = RoomType::all();

        foreach ($roomTypes as $roomType) {
            RoomImage::factory()->count(rand(2, 5))->create([
                'room_type_id' => $roomType->id,
            ]);
        }
    }
}
