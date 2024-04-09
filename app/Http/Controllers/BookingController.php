<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __invoke() {
        $validator = validator()->make(request()->all(), [
            'flight_from' => 'required|array',
            'flight_from.id' => 'required|exists:flights,id',
            'flight_from.date' =>'required|date|date_format:Y-m-d',
            'flight_back' => 'array',
            'flight_back.id' => [Rule::requiredIf(!empty(request('flight_back'))), 'exists:flights,id'],
            'flight_back.date' => [Rule::requiredIf(!empty(request('flight_back'))), 'date', 'date_format:Y-m-d'],
            'passengers' => 'required|array',
            'passengers.*.first_name' => 'required|string',
            'passengers.*.last_name' => 'required|string',
            'passengers.*.birth_date' =>'required|date|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|digits:10'
        ]);

        if($validator->fails() && (
                $validator->errors()->has('flight_from') ||
                $validator->errors()->has('flight_from.id') ||
                $validator->errors()->has('flight_from.date') ||
                $validator->errors()->has('flight_back') ||
                $validator->errors()->has('flight_back.id') ||
                $validator->errors()->has('flight_back.date')
            )
        ){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->getMessageBag()
                ]
            ], 422);
        }

        $passengers = count(request('passengers'));

        $errors = $validator->getMessageBag();
        if(
            Flight::find(request('flight_from.id'))->freePlaces(request('flight_from.date')) < $passengers ||
            (
                !empty(request('flight_back')) && Flight::find(request('flight_back.id'))->freePlaces(request('flight_back.date')) < $passengers
            )
        ){
            $errors->add('places', 'Не хватает свободных мест');
        }
        if(!$errors->isEmpty()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $errors
                ]
            ], 422);
        }
//        return response(Flight::find(request('flight_from.id'))->firstAvailableSeat(request('flight_from.date')));
        $key = $this->generateUniqueKey();
        $booking = Booking::create([
            'flight_from' => request('flight_from.id'),
            'flight_back' => request('flight_back.id'),
            'date_from' => request('flight_from.date'),
            'date_back' => request('flight_back.date'),
            'code' => $key
        ]);

        foreach (request('passengers') as $item) {
            /*$place_from = Flight::find(request('flight_from.id'))->firstAvailableSeat(request('flight_from.date'));
            $place_back = Flight::find(request('flight_back.id'))->firstAvailableSeat(request('flight_back.date'));*/
            Passenger::create([
                'booking_id' => $booking->id,
                'first_name' => $item['first_name'],
                'last_name' => $item['last_name'],
                'birth_date' => $item['birth_date'],
                'document_number' => $item['document_number'],
            ]);
        }


        return response()->json([
            'data' => [
                'code' => $key
            ]
        ]);
    }

    protected function generateUniqueKey($length = 5) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $key = '';
        while(true){
            // Генерируем случайный ключ, выбирая пять символов из $characters
            for ($i = 0; $i < $length; $i++) {
                $key .= $characters[rand(0, strlen($characters) - 1)];
            }
            if (empty(Booking::where('code', $key)->first())){
                return $key;
            }
            $key='';
        }
    }

    public function show($code){
        $booking = Booking::where('code', $code)->first();
        if(empty($booking)){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Not found',
                ]
            ], 404);
        }

        $allCost = $booking->flight_from_collect->cost;

        $flights = [
            [
                'flight_id' => $booking->flight_from_collect->id,
                'flight_code' => $booking->flight_from_collect->flight_code,
                'from' => [
                    'city' => $booking->flight_from_collect->airport_from->city,
                    'airport' => $booking->flight_from_collect->airport_from->name,
                    'iata' => $booking->flight_from_collect->airport_from->iata,
                    'date' => $booking->date_from,
                    'time' => $booking->flight_from_collect->time_from
                ],
                'to' => [
                    'city' => $booking->flight_from_collect->airport_to->city,
                    'airport' => $booking->flight_from_collect->airport_to->name,
                    'iata' => $booking->flight_from_collect->airport_to->iata,
                    'date' => $booking->date_from,
                    'time' => $booking->flight_from_collect->time_to
                ],
                'cost' => $booking->flight_from_collect->cost,
                'availability' => $booking->flight_from_collect->freePlaces($booking->date_from)
            ]
        ];
        if(!empty($booking->flight_back)){
            $allCost += $booking->flight_back_collect->cost;

            $flights[] = [
                'flight_id' => $booking->flight_back_collect->id,
                'flight_code' => $booking->flight_back_collect->flight_code,
                'from' => [
                    'city' => $booking->flight_back_collect->airport_from->city,
                    'airport' => $booking->flight_back_collect->airport_from->name,
                    'iata' => $booking->flight_back_collect->airport_from->iata,
                    'date' => $booking->date_back,
                    'time' => $booking->flight_back_collect->time_from
                ],
                'to' => [
                    'city' => $booking->flight_back_collect->airport_to->city,
                    'airport' => $booking->flight_back_collect->airport_to->name,
                    'iata' => $booking->flight_back_collect->airport_to->iata,
                    'date' => $booking->date_from,
                    'time' => $booking->flight_back_collect->time_to
                ],
                'cost' => $booking->flight_back_collect->cost,
                'availability' => $booking->flight_back_collect->freePlaces($booking->date_from)
            ];
        }
        $passengers = [];
        foreach($booking->occupiedSeats as $item){
            $passengers[] = [
                'id' => $item->id,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'birth_date' => $item->birth_date,
                'document_number' => $item->document_number,
                'place_from' => $item->place_from,
                'place_back' => $item->place_back
            ];
        }
        $allCost *= count($passengers);

        return response()->json([
            'data' => [
                'code' => $booking->code,
                'cost' => $allCost,
                'flights' => $flights,
                'passengers' => $passengers
            ]
        ]);
    }

    public function seat($code) {
        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Not found',
                ]
            ], 404);
        }

        $occupied_from = $booking->flight_from_collect->firstAvailableSeat($booking->date_from);

        $occupied_back = [];
        if(!empty($booking->flight_back)){
            $occupied_back = $booking->flight_back_collect->firstAvailableSeat($booking->date_back);
        }

        return response()->json([
            'data' => [
                'occupied_from' => $occupied_from,
                'occupied_back' => $occupied_back
            ]
        ]);
    }

    public function update($code) {
        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Not found',
                ]
            ], 404);
        }
        $validator = validator()->make(request()->all(), [
            'passenger' => 'required|exists:passengers,id',
            'seat' => 'required|exists:airplane_seats,name',
            'type' => 'required|in:from,back'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->getMessageBag()
                ]
            ], 422);
        }

        $function = 'flight_' . request('type') . '_collect';
        $dateFn = 'date_' . request('type');
        if(in_array(request('seat'), array_column($booking->$function->firstAvailableSeat($booking->$dateFn), 'place'))){
            return response()->json([
                "error" => [
                    "code" => 422,
                    "message" => "Seat is occupied"
                ]
            ], 422);
        }
        $doc = '0123456789';

        if($booking->occupiedSeats()->where('document_number', auth()->user()->document_number)->get()->isEmpty()){
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Passenger does not apply to booking'
                ]
            ], 403);
        }
        $function = 'place_' . request('type');

        $passenger = Passenger::find(request('passenger'));
        $passenger->$function = request('seat');
        $passenger->save();
        return response()->json([
            'data' => [
                'id' => $passenger->id,
                'firs_name' => $passenger->first_name,
                'last_name' => $passenger->last_name,
                'birth_date' => $passenger->birth_date,
                'document_number' => $passenger->document_number,
                'place_from' => $passenger->place_from,
                'place_back' => $passenger->place_back
            ]
        ], 200);
    }

    public function user(){
        $doc = '0123456789';
        $bookings_id = Passenger::where('document_number', $doc)->get()->pluck('booking_id');
        $items = [];
        foreach ($bookings_id as $item){
            $booking = Booking::find($item);
            $allCost = $booking->flight_from_collect->cost;

            $flights = [
                [
                    'flight_id' => $booking->flight_from_collect->id,
                    'flight_code' => $booking->flight_from_collect->flight_code,
                    'from' => [
                        'city' => $booking->flight_from_collect->airport_from->city,
                        'airport' => $booking->flight_from_collect->airport_from->name,
                        'iata' => $booking->flight_from_collect->airport_from->iata,
                        'date' => $booking->date_from,
                        'time' => $booking->flight_from_collect->time_from
                    ],
                    'to' => [
                        'city' => $booking->flight_from_collect->airport_to->city,
                        'airport' => $booking->flight_from_collect->airport_to->name,
                        'iata' => $booking->flight_from_collect->airport_to->iata,
                        'date' => $booking->date_from,
                        'time' => $booking->flight_from_collect->time_to
                    ],
                    'cost' => $booking->flight_from_collect->cost,
                    'availability' => $booking->flight_from_collect->freePlaces($booking->date_from)
                ]
            ];
            if(!empty($booking->flight_back)){
                $allCost += $booking->flight_back_collect->cost;

                $flights[] = [
                    'flight_id' => $booking->flight_back_collect->id,
                    'flight_code' => $booking->flight_back_collect->flight_code,
                    'from' => [
                        'city' => $booking->flight_back_collect->airport_from->city,
                        'airport' => $booking->flight_back_collect->airport_from->name,
                        'iata' => $booking->flight_back_collect->airport_from->iata,
                        'date' => $booking->date_back,
                        'time' => $booking->flight_back_collect->time_from
                    ],
                    'to' => [
                        'city' => $booking->flight_back_collect->airport_to->city,
                        'airport' => $booking->flight_back_collect->airport_to->name,
                        'iata' => $booking->flight_back_collect->airport_to->iata,
                        'date' => $booking->date_from,
                        'time' => $booking->flight_back_collect->time_to
                    ],
                    'cost' => $booking->flight_back_collect->cost,
                    'availability' => $booking->flight_back_collect->freePlaces($booking->date_from)
                ];
            }
            $passengers = [];
            foreach($booking->occupiedSeats as $item){
                $passengers[] = [
                    'id' => $item->id,
                    'first_name' => $item->first_name,
                    'last_name' => $item->last_name,
                    'birth_date' => $item->birth_date,
                    'document_number' => $item->document_number,
                    'place_from' => $item->place_from,
                    'place_back' => $item->place_back
                ];
            }
            $allCost *= count($passengers);

            $items[] = [
                'code' => $booking->code,
                'cost' => $allCost,
                'flights' => $flights,
                'passengers' => $passengers
            ];
        }
        return response()->json([
            'data' => [
                'items' => $items
            ]
        ]);
    }
}
