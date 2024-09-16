<?php
// app/Models/HotelImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'image_url',
        'is_main',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
