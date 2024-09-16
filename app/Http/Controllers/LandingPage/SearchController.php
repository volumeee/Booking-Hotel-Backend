<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'location' => 'nullable|string',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'guests' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $guests = $request->filled('guests') ? $request->guests : null;

            $query = RoomType::with(['hotel', 'images', 'rooms'])
                ->whereHas('hotel', function ($q) use ($request) {
                    $q->where('city', 'like', '%' . $request->location . '%')
                        ->orWhere('country', 'like', '%' . $request->location . '%');
                })
                ->whereHas('rooms', function ($q) use ($checkIn, $checkOut) {
                    $q->whereDoesntHave('bookings', function ($bookingQuery) use ($checkIn, $checkOut) {
                        $bookingQuery->where(function ($subQuery) use ($checkIn, $checkOut) {
                            $subQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                ->orWhere(function ($innerQuery) use ($checkIn, $checkOut) {
                                    $innerQuery->where('check_in_date', '<=', $checkIn)
                                        ->where('check_out_date', '>=', $checkOut);
                                });
                        });
                    });
                });

            if ($guests) {
                $query->where('capacity', '>=', $guests);
            }

            $roomTypes = $query->get();

            if ($roomTypes->isEmpty()) {
                if ($request->filled('location')) {
                    return $this->errorResponse("No hotels found in the specified location.", 404);
                }
                if ($guests >= 10) {
                    return $this->errorResponse("No rooms available with capacity 10 or more.", 404);
                }
                return $this->errorResponse("No rooms available for the specified dates.", 404);
            }

            $formattedRoomTypes = $roomTypes->map(function ($roomType) {
                return [
                    'id' => $roomType->id,
                    'hotel_id' => $roomType->hotel_id,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'capacity' => $roomType->capacity,
                    'price_per_night' => $roomType->price_per_night,
                    'created_at' => $roomType->created_at,
                    'updated_at' => $roomType->updated_at,
                    'hotel' => $roomType->hotel,
                    'images' => $roomType->images,
                    'rooms' => $roomType->rooms->map(function ($room) {
                        return [
                            'id' => $room->id,
                            'hotel_id' => $room->hotel_id,
                            'room_type_id' => $room->room_type_id,
                            'room_number' => $room->room_number,
                            'status' => $room->status,
                            'created_at' => $room->created_at,
                            'updated_at' => $room->updated_at,
                        ];
                    }),
                ];
            });

            $paginatedRoomTypes = new LengthAwarePaginator(
                $formattedRoomTypes->forPage($request->page ?: 1, 10),
                $formattedRoomTypes->count(),
                10,
                $request->page ?: 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return $this->successResponse($paginatedRoomTypes, 'Room types retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error searching room types: ' . $e->getMessage(), 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $validator = Validator::make($request->query(), [
                'price_min' => 'nullable|numeric',
                'price_max' => 'nullable|numeric',
                'star_rating' => 'nullable|integer|min:1|max:5',
                'amenities' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $query = RoomType::with(['hotel', 'images', 'rooms', 'hotel.amenities']);

            // Handle price range
            if ($request->query('price_min') && $request->query('price_max')) {
                $query->whereBetween('price_per_night', [$request->query('price_min'), $request->query('price_max')]);
            }

            // Handle star rating
            if ($request->query('star_rating')) {
                $query->whereHas('hotel', function ($q) use ($request) {
                    $q->where('star_rating', '>=', $request->query('star_rating'));
                });
            }

            // Handle amenities
            $requestedAmenities = [];
            if ($request->query('amenities')) {
                $requestedAmenities = array_map('trim', explode(',', $request->query('amenities')));
                $query->whereHas('hotel.amenities', function ($q) use ($requestedAmenities) {
                    $q->whereIn('amenities.id', $requestedAmenities);
                });
            }

            $roomTypes = $query->paginate(10);

            $formattedRoomTypes = collect($roomTypes->items())->map(function ($roomType) use ($requestedAmenities) {
                $allAmenities = $roomType->hotel->amenities->map(function ($amenity) use ($roomType) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'created_at' => $amenity->created_at,
                        'updated_at' => $amenity->updated_at,
                        'pivot' => [
                            'hotel_id' => $roomType->hotel_id,
                            'amenity_id' => $amenity->id
                        ]
                    ];
                });

                // Separate filtered and non-filtered amenities
                $filteredAmenities = $allAmenities->filter(function ($amenity) use ($requestedAmenities) {
                    return in_array($amenity['id'], $requestedAmenities);
                })->sortBy(function ($amenity) use ($requestedAmenities) {
                    return array_search($amenity['id'], $requestedAmenities);
                })->values();

                $nonFilteredAmenities = $allAmenities->reject(function ($amenity) use ($requestedAmenities) {
                    return in_array($amenity['id'], $requestedAmenities);
                })->values();

                // Combine filtered and non-filtered amenities
                $sortedAmenities = $filteredAmenities->concat($nonFilteredAmenities);

                $hotel = $roomType->hotel->toArray();
                unset($hotel['amenities']); // Hide amenities from hotel array

                return [
                    'id' => $roomType->id,
                    'hotel_id' => $roomType->hotel_id,
                    'name' => $roomType->name,
                    'description' => $roomType->description,
                    'capacity' => $roomType->capacity,
                    'price_per_night' => $roomType->price_per_night,
                    'created_at' => $roomType->created_at,
                    'updated_at' => $roomType->updated_at,
                    'hotel' => $hotel,
                    'images' => $roomType->images,
                    'rooms' => $roomType->rooms,
                    'amenities' => $sortedAmenities,
                ];
            });

            $paginatedResult = new LengthAwarePaginator(
                $formattedRoomTypes,
                $roomTypes->total(),
                $roomTypes->perPage(),
                $roomTypes->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return $this->successResponse($paginatedResult, 'Filtered room types retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error filtering room types: ' . $e->getMessage(), 500);
        }
    }
}
