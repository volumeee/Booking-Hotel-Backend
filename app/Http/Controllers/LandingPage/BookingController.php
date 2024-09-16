<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rooms' => 'required|array',
                'rooms.*.room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $bookingDetails = [];
            $totalPrice = 0;

            foreach ($request->rooms as $roomData) {
                $room = Room::with(['hotel', 'roomType'])->findOrFail($roomData['room_id']);
                $nights = date_diff(date_create($request->check_in), date_create($request->check_out))->days;
                $roomPrice = $room->roomType->price_per_night * $nights;
                $totalPrice += $roomPrice;

                $bookingDetails[] = [
                    'room' => $room,
                    'price' => number_format($roomPrice, 2),
                ];
            }

            $bookingSummary = [
                'rooms' => $bookingDetails,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'nights' => $nights,
                'total_price' => number_format($totalPrice, 2),
            ];

            return $this->successResponse($bookingSummary, 'Booking details retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving booking details: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rooms' => 'required|array',
                'rooms.*.room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $totalPrice = 0;
            $bookings = [];

            DB::transaction(function () use ($request, &$totalPrice, &$bookings) {
                foreach ($request->rooms as $roomData) {
                    $room = Room::with(['hotel', 'roomType'])->findOrFail($roomData['room_id']);
                    $nights = date_diff(date_create($request->check_in), date_create($request->check_out))->days;
                    $roomPrice = $room->roomType->price_per_night * $nights;
                    $totalPrice += $roomPrice;

                    $booking = Booking::create([
                        'user_id' => Auth::id(),
                        'hotel_id' => $room->hotel_id,
                        'room_id' => $room->id,
                        'check_in_date' => $request->check_in,
                        'check_out_date' => $request->check_out,
                        'total_price' => $roomPrice,
                        'status' => 'menunggu',
                    ]);

                    $bookings[] = $booking;
                }
            });

            return $this->successResponse(['bookings' => $bookings, 'total_price' => $totalPrice], 'Bookings created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error creating bookings: ' . $e->getMessage(), 500);
        }
    }
}
