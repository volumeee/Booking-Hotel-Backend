<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function create($bookingId)
    {
        try {
            $booking = Booking::with('hotel', 'room')->findOrFail($bookingId);

            if ($booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

            $paymentMethods = $this->getTripayPaymentMethods($booking->total_price);

            return $this->successResponse([
                'booking' => $booking,
                'payment_methods' => $paymentMethods
            ], 'Payment methods retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving payment methods: ' . $e->getMessage(), 500);
        }
    }

    public function process(Request $request, $bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            if ($booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

            $validator = Validator::make($request->all(), [
                'payment_method' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $paymentResult = $this->processTripayPayment($booking, $request->payment_method);

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'tripay_reference' => $paymentResult['reference'],
                'tripay_merchant_ref' => $paymentResult['merchant_ref'],
                'tripay_payment_method' => $paymentResult['payment_method'],
                'tripay_pay_code' => $paymentResult['pay_code'],
                'tripay_amount_received' => $paymentResult['amount_received'],
                'tripay_fee_merchant' => $paymentResult['fee_merchant'],
                'tripay_fee_customer' => $paymentResult['fee_customer'],
                'tripay_total_fee' => $paymentResult['total_fee'],
                'tripay_status' => $paymentResult['status'],
                'tripay_expired_time' => $paymentResult['expired_time'],
                'status' => 'pending',
            ]);

            return $this->successResponse($payment, 'Payment processed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error processing payment: ' . $e->getMessage(), 500);
        }
    }

    public function status($bookingId)
    {
        try {
            $booking = Booking::with('payment')->findOrFail($bookingId);

            if ($booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

            return $this->successResponse($booking, 'Payment status retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving payment status: ' . $e->getMessage(), 500);
        }
    }

    private function getTripayPaymentMethods($amount)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('tripay.api_key')
        ])->get(config('tripay.api_url') . '/merchant/payment-channel', [
            'amount' => $amount
        ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Error fetching payment methods from Tripay');
    }

    private function processTripayPayment($booking, $paymentMethod)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('tripay.api_key')
        ])->post(config('tripay.api_url') . '/transaction/create', [
            'method' => $paymentMethod,
            'merchant_ref' => 'BOOKING-' . $booking->id,
            'amount' => $booking->total_price,
            'customer_name' => $booking->user->name,
            'customer_email' => $booking->user->email,
            'order_items' => [
                [
                    'name' => $booking->room->roomType->name . ' at ' . $booking->hotel->name,
                    'price' => $booking->total_price,
                    'quantity' => 1
                ]
            ],
            'return_url' => route('landing.payment.status', $booking->id),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 hours
            'signature' => hash_hmac('sha256', config('tripay.merchant_code') . $booking->total_price . 'BOOKING-' . $booking->id, config('tripay.private_key'))
        ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Error processing payment with Tripay');
    }
}
