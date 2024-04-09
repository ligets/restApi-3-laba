<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    public function freePlaces($date){
        $size = AirplaneSeat::count();
        return $size - Booking::where('flight_from', $this->id)
                                ->orWhere('flight_back', $this->id)
                                ->where('date_from', $date)
                                ->orWhere('date_back', $date)
                                ->count();
    }

    public function firstAvailableSeat($date)
    {
//        return $this->bookingsFrom()->where('date_from', $date)->get();
        // Получаем список всех занятых мест на рейсе
        $bookedSeats = $this->bookingsFrom()->where('date_from', $date)->get()->flatMap(function ($booking) {
            return $booking->occupiedSeats->filter(function ($seat){
                return $seat->place_from !== null;
            })->map(function ($seat){
                return [
                    'id' => $seat->id,
                    'place' => $seat->place_from
                ];
            })->toArray();
        })->merge($this->bookingsBack()->where('date_back', $date)->get()->flatMap(function ($booking) {
            return $booking->occupiedSeats->filter(function ($seat){
                return $seat->place_back !== null;
            })->map(function ($seat){
                return [
                    'id' => $seat->id,
                    'place' => $seat->place_back
                ];
            })->toArray();
        }))->toArray();
        return $bookedSeats;
    }

    // Определите отношение между рейсом и бронированием
    public function bookingsFrom()
    {
        return $this->hasMany(Booking::class, 'flight_from');
    }

    // Определение отношения с бронированиями, для рейсов из flight_back
    public function bookingsBack()
    {
        return $this->hasMany(Booking::class, 'flight_back', 'id');
    }

    public function airport_from(){
        return $this->belongsTo(Airport::class, 'from_id');
    }

    public function airport_to(){
        return $this->belongsTo(Airport::class, 'to_id');
    }
}
