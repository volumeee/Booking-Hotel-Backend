<?php
// database/seeders/ChatMessagesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatMessage;

class ChatMessagesTableSeeder extends Seeder
{
    public function run()
    {
        ChatMessage::factory()->count(200)->create();
    }
}
