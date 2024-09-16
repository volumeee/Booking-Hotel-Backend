<?php
// database/seeders/RoomsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        Room::factory()->count(200)->create();
    }
}
