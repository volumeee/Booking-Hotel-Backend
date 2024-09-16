<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'popularHotels' => $this->getPopularHotels(),
                'availableRooms' => $this->getAvailableRooms(),
                'recentReviews' => $this->getRecentReviews(),
            ];

            return $this->successResponse($data, 'Landing page data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving landing page data: ' . $e->getMessage(), 500);
        }
    }

    public function clearCache()
    {
        try {
            Cache::forget('popular_hotels');
            Cache::forget('all_rooms_sorted');
            Cache::forget('recent_reviews');
            Cache::forget('featured_destinations');

            return $this->successResponse(null, 'Cache cleared successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error clearing cache: ' . $e->getMessage(), 500);
        }
    }

    private function getPopularHotels()
    {
        return Cache::remember('popular_hotels', 3600, function () {
            return Hotel::with(['images', 'reviews', 'amenities'])
                ->orderByDesc('star_rating')
                ->take(6)
                ->get();
        });
    }


    private function getAvailableRooms()
    {
        return Cache::remember('all_rooms_sorted', 1800, function () {
            $rooms = RoomType::with([
                'hotel',
                'images',
                'rooms' => function ($query) {
                    $query->orderByRaw("CASE 
                                WHEN status = 'tersedia' THEN 0 
                                WHEN status = 'dibersihkan' THEN 1 
                                ELSE 2 
                                END")
                        ->orderBy('status', 'asc');
                }
            ])
                ->get();

            return $rooms;
        });
    }



    private function getRecentReviews()
    {
        return Cache::remember('recent_reviews', 1800, function () {
            return Review::with(['user', 'hotel'])
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
        });
    }
}
