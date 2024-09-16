<?php
// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'tripay_reference',
        'tripay_merchant_ref',
        'tripay_payment_method',
        'tripay_pay_code',
        'tripay_amount_received',
        'tripay_fee_merchant',
        'tripay_fee_customer',
        'tripay_total_fee',
        'tripay_status',
        'tripay_expired_time',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
