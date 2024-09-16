<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Role;
use App\Models\User;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Promotion;
use App\Models\Notification;
use App\Models\HotelImage;
use App\Models\RoomImage;
use App\Models\ChatMessage;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            HotelSeeder::class,
            AmenitySeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            HotelAmenitySeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            PromotionSeeder::class,
            NotificationSeeder::class,
            HotelImageSeeder::class,
            RoomImageSeeder::class,
            ChatMessageSeeder::class,
            ReportSeeder::class,
        ]);
    }
}

class RoleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $roles = ['Admin', 'User', 'Manajer Hotel'];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role],
                ['description' => $faker->sentence()]
            );
        }
    }
}


class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 1 admin
        User::factory()->create([
            'role_id' => Role::where('name', 'Admin')->first()->id
        ]);

        // Create 2 managers
        User::factory()->count(2)->create([
            'role_id' => Role::where('name', 'Manajer Hotel')->first()->id
        ]);

        // Create 17 regular users (to make a total of 20 users)
        User::factory()->count(17)->create([
            'role_id' => Role::where('name', 'User')->first()->id
        ]);
    }
}

class HotelSeeder extends Seeder
{
    public function run()
    {
        Hotel::factory()->count(10)->create();
    }
}

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        Hotel::all()->each(function ($hotel) {
            RoomType::factory()->count(rand(1, 3))->create(['hotel_id' => $hotel->id]);
        });
    }
}

class RoomSeeder extends Seeder
{
    public function run()
    {
        RoomType::all()->each(function ($roomType) {
            $roomCount = rand(2, 5);
            Room::factory()->count($roomCount)->create([
                'room_type_id' => $roomType->id,
                'hotel_id' => $roomType->hotel_id
            ]);
        });
    }
}

class AmenitySeeder extends Seeder
{
    public function run()
    {
        Amenity::factory()->count(10)->create();
    }
}

class HotelAmenitySeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();
        $amenities = Amenity::all();

        foreach ($hotels as $hotel) {
            $hotel->amenities()->attach(
                $amenities->random(rand(1, $amenities->count()))->pluck('id')->toArray()
            );
        }
    }
}

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role_id', Role::where('name', 'User')->first()->id)->get();
        $rooms = Room::all();

        foreach ($users as $user) {
            $bookingsCount = rand(1, 2);
            for ($i = 0; $i < $bookingsCount; $i++) {
                $room = $rooms->random();
                Booking::factory()->create([
                    'user_id' => $user->id,
                    'hotel_id' => $room->hotel_id,
                    'room_id' => $room->id
                ]);
            }
        }
    }
}

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Booking::all()->each(function ($booking) {
            Payment::factory()->create(['booking_id' => $booking->id]);
        });
    }
}

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Booking::all()->each(function ($booking) {
            if (rand(0, 1)) { // 50% chance of creating a review for each booking
                Review::factory()->create([
                    'user_id' => $booking->user_id,
                    'hotel_id' => $booking->hotel_id
                ]);
            }
        });
    }
}

class PromotionSeeder extends Seeder
{
    public function run()
    {
        Promotion::factory()->count(10)->create();
    }
}

class NotificationSeeder extends Seeder
{
    public function run()
    {
        Notification::factory()->count(20)->create();
    }
}

class HotelImageSeeder extends Seeder
{
    public function run()
    {
        HotelImage::factory()->count(20)->create();
    }
}

class RoomImageSeeder extends Seeder
{
    public function run()
    {
        RoomImage::factory()->count(30)->create();
    }
}

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role_id', Role::where('name', 'User')->first()->id)->get();
        $admins = User::where('role_id', Role::where('name', 'Admin')->first()->id)->get();

        foreach ($users as $user) {
            $messagesCount = rand(1, 2);
            for ($i = 0; $i < $messagesCount; $i++) {
                ChatMessage::factory()->create([
                    'user_id' => $user->id,
                    'admin_id' => $admins->random()->id
                ]);
            }
        }
    }
}

class ReportSeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            $reportsCount = rand(1, 2);
            for ($i = 0; $i < $reportsCount; $i++) {
                Report::factory()->create([
                    'hotel_id' => $hotel->id
                ]);
            }
        }
    }
}
