<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    public function show($id)
    {
        try {
            $hotel = Hotel::with(['images', 'reviews.user', 'amenities', 'roomTypes.rooms', 'promotions'])
                ->findOrFail($id);

            return $this->successResponse($hotel, 'Hotel details retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving hotel details: ' . $e->getMessage(), 500);
        }
    }

    public function showRooms($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $hotel = Hotel::with([
                'roomTypes.rooms' => function ($query) use ($request) {
                    $query->where('status', 'tersedia')
                        ->whereDoesntHave('bookings', function ($q) use ($request) {
                            $q->where(function ($q) use ($request) {
                                $q->whereBetween('check_in_date', [$request->check_in, $request->check_out])
                                    ->orWhereBetween('check_out_date', [$request->check_in, $request->check_out]);
                            });
                        });
                }
            ])->findOrFail($id);

            foreach ($hotel->roomTypes as $roomType) {
                $roomType->available_rooms_count = $roomType->rooms->count();
                $roomType->price_per_night = number_format($roomType->price_per_night, 2);
            }

            return $this->successResponse($hotel, 'Available rooms retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving available rooms: ' . $e->getMessage(), 500);
        }
    }
}
