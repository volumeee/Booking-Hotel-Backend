<?php
// database/seeders/PromotionsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionsTableSeeder extends Seeder
{
    public function run()
    {
        Promotion::factory()->count(30)->create();
    }
}
