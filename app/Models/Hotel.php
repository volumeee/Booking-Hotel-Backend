<?php
// app/Models/Hotel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'country',
        'zip_code',
        'phone',
        'email',
        'website',
        'star_rating',
        'latitude',
        'longitude',
    ];

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
