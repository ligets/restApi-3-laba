<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpParser\Node\ArrayItem;

class FlightController extends Controller
{
    public function __invoke() {
        $validator = validator()->make(request()->all(), [
            'from' => [
                'required',
                Rule::exists('airports', 'iata')
            ],
            'to' =>[
                'required',
                Rule::exists('airports', 'iata')
            ],
            'date1' => 'required|date|date_format:Y-m-d',
            'date2' => 'date|date_format:Y-m-d',
            'passengers' => 'required|integer|between:1,8'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->getMessageBag()
                ]
            ]);
        }

        $from = Airport::where('iata', request('from'))->first();
        $to = Airport::where('iata', request('to'))->first();
        $passengers = request('passengers');

        $flightsTo = Flight::where('from_id', $from->id)
                            ->where('to_id', $to->id)
                            ->get()
                            ->filter(function ($flight) use ($passengers){
                                return $flight->freePlaces(request('date1')) >= $passengers;
                            });

        $flightsBack = [];
        if(!empty(request('date2'))){
            $flightsBack = Flight::where('from_id', $to->id)
                                ->where('to_id', $from->id)
                                ->get()
                                ->filter(function ($flight) use ($passengers){
                                    return $flight->freePlaces(request('date2')) >= $passengers;
                                });
        }

        $result_to = [];
        $result_back = [];

        foreach($flightsTo as $flight){
            $result_to[] = [
                'flight_id' => $flight->id,
                'flight_code' => $flight->flight_code,
                'from' => [
                    'city' => $flight->airport_from->city,
                    'airport' => $flight->airport_from->name,
                    'iata' => $flight->airport_from->iata,
                    'date' => request('date1'),
                    'time' => $flight->time_from
                ],
                'to' => [
                    'city' => $flight->airport_to->city,
                    'airport' => $flight->airport_to->name,
                    'iata' => $flight->airport_to->iata,
                    'date' => request('date1'),
                    'time' => $flight->time_to
                ],
                'cost' => $flight->cost,
                'availability' => $flight->freePlaces(request('date1'))
            ];
        }

        foreach($flightsBack as $flight){
            $result_back[] = [
                'flight_id' => $flight->id,
                'flight_code' => $flight->flight_code,
                'from' => [
                    'city' => $flight->airport_from->city,
                    'airport' => $flight->airport_from->name,
                    'iata' => $flight->airport_from->iata,
                    'date' => request('date2'),
                    'time' => $flight->time_from
                ],
                'to' => [
                    'city' => $flight->airport_to->city,
                    'airport' => $flight->airport_to->name,
                    'iata' => $flight->airport_to->iata,
                    'date' => request('date2'),
                    'time' => $flight->time_to
                ],
                'cost' => $flight->cost,
                'availability' => $flight->freePlaces(request('date2'))
            ];
        }

        return response()->json([
            'data' => [
                'flights_to' => $result_to,
                'flights_back' => $result_back
            ]
        ]);
    }
}
