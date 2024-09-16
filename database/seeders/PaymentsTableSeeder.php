<?php
// database/seeders/PaymentsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            Payment::factory()->create(['booking_id' => $booking->id]);
        }
    }
}
