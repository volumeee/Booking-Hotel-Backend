<?php
// database/seeders/ReportsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Hotel;

class ReportsTableSeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();
        $reportTypes = ['occupancy', 'revenue', 'customer_satisfaction'];

        foreach ($hotels as $hotel) {
            foreach ($reportTypes as $type) {
                Report::factory()->create([
                    'hotel_id' => $hotel->id,
                    'report_type' => $type,
                ]);
            }
        }
    }
}
