<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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


class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Admin', 'User', 'Manajer Hotel']),
            'description' => $this->faker->sentence(),
        ];
    }
}

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role_id' => Role::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'loyalty_points' => $this->faker->numberBetween(0, 1000),
            'remember_token' => Str::random(10),
        ];
    }
}

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition()
    {
        $kota = $this->faker->city();
        return [
            'name' => "Hotel " . $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'city' => $kota,
            'country' => 'Indonesia',
            'zip_code' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'star_rating' => $this->faker->numberBetween(1, 5),
            'latitude' => $this->faker->latitude(-11, 6),
            'longitude' => $this->faker->longitude(95, 141),
        ];
    }
}

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'name' => $this->faker->randomElement(['Standar', 'Deluxe', 'Suite', 'Family', 'Presidential']),
            'description' => $this->faker->paragraph(),
            'capacity' => $this->faker->numberBetween(1, 6),
            'price_per_night' => $this->faker->numberBetween(500000, 5000000),
        ];
    }
}

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'room_type_id' => RoomType::factory(),
            'room_number' => $this->faker->unique()->numberBetween(100, 999),
            'status' => $this->faker->randomElement(['tersedia', 'dipesan', 'dibersihkan']),
        ];
    }
}

class AmenityFactory extends Factory
{
    protected $model = Amenity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'WiFi',
                'Kolam Renang',
                'Pusat Kebugaran',
                'Restoran',
                'Bar',
                'Parkir',
                'Layanan Kamar',
                'Spa',
                'AC',
                'TV'
            ]),
        ];
    }
}

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+2 months');
        $checkOut = $this->faker->dateTimeBetween($checkIn, $checkIn->format('Y-m-d H:i:s') . ' +2 weeks');

        return [
            'user_id' => User::factory(),
            'hotel_id' => Hotel::factory(),
            'room_id' => Room::factory(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'total_price' => $this->faker->numberBetween(1000000, 10000000),
            'status' => $this->faker->randomElement(['menunggu', 'dikonfirmasi', 'dibatalkan']),
        ];
    }
}

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'booking_id' => Booking::factory(),
            'amount' => $this->faker->numberBetween(1000000, 10000000),
            'payment_method' => $this->faker->randomElement(['kartu kredit', 'transfer bank', 'e-wallet']),
            'tripay_reference' => $this->faker->uuid(),
            'tripay_merchant_ref' => $this->faker->uuid(),
            'tripay_payment_method' => $this->faker->randomElement(['BRIVA', 'MANDIRIVA', 'BCAVA', 'PERMATAVA', 'ALFAMART', 'INDOMARET']),
            'tripay_pay_code' => $this->faker->numerify('############'),
            'tripay_amount_received' => $this->faker->numberBetween(1000000, 10000000),
            'tripay_fee_merchant' => $this->faker->numberBetween(1000, 10000),
            'tripay_fee_customer' => $this->faker->numberBetween(1000, 10000),
            'tripay_total_fee' => $this->faker->numberBetween(2000, 20000),
            'tripay_status' => $this->faker->randomElement(['UNPAID', 'PAID', 'EXPIRED', 'FAILED']),
            'tripay_expired_time' => $this->faker->dateTimeBetween('now', '+1 day'),
            'status' => $this->faker->randomElement(['menunggu', 'sukses', 'gagal']),
        ];
    }
}

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'hotel_id' => Hotel::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
        ];
    }
}

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'name' => 'Promo ' . $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'discount_percentage' => $this->faker->numberBetween(5, 50),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'is_read' => $this->faker->boolean(),
        ];
    }
}

class HotelImageFactory extends Factory
{
    protected $model = HotelImage::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'image_url' => $this->faker->imageUrl(640, 480, 'hotel'),
            'is_main' => $this->faker->boolean(),
        ];
    }
}

class RoomImageFactory extends Factory
{
    protected $model = RoomImage::class;

    public function definition()
    {
        return [
            'room_type_id' => RoomType::factory(),
            'image_url' => $this->faker->imageUrl(640, 480, 'room'),
            'is_main' => $this->faker->boolean(),
        ];
    }
}

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'admin_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'is_from_user' => $this->faker->boolean(),
        ];
    }
}

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'hotel_id' => Hotel::factory(),
            'report_type' => $this->faker->randomElement(['okupansi', 'pendapatan', 'kepuasan_pelanggan']),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'data' => json_encode([
                'total' => $this->faker->numberBetween(1000, 10000),
                'rata_rata' => $this->faker->randomFloat(2, 50, 100),
            ]),
        ];
    }
}
