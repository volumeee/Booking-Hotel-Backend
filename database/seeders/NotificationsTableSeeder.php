<?php
// database/seeders/NotificationsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationsTableSeeder extends Seeder
{
    public function run()
    {
        Notification::factory()->count(100)->create();
    }
}
