<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_from',
        'flight_back',
        'date_from',
        'date_back',
        'code'
    ];

    public $timestamps = false;

    public function occupiedSeats() {
        return $this->hasMany('App\Models\Passenger', 'booking_id');
    }

    public function flight_from_collect() {
        return $this->belongsTo(Flight::class, 'flight_from');
    }
    public function flight_back_collect() {
        return $this->belongsTo(Flight::class, 'flight_back');
    }
}
