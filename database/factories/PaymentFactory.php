<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $booking = Booking::inRandomOrder()->first();
        return [
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
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
