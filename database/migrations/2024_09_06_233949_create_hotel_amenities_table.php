<?php

// database/migrations/xxxx_xx_xx_create_hotel_amenities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelAmenitiesTable extends Migration
{
    public function up()
    {
        Schema::create('hotel_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels');
            $table->foreignId('amenity_id')->constrained('amenities');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_amenities');
    }
}
