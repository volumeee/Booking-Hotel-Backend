<?php
// database/seeders/BookingsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingsTableSeeder extends Seeder
{
    public function run()
    {
        Booking::factory()->count(100)->create();
    }
}
