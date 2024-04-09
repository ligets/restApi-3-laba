<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function __invoke(Request $request){
        $query = request('query');
        $airports = Airport::where('city', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orWhere('iata', strtoupper($query))
            ->get();

        $items = [];
        foreach ($airports as $airport){
            $items[] = [
                'name' => $airport->name,
                'iata' => $airport->iata
            ];
        }
        return response()->json([
            'data' => [
                'items' => $items
            ]
        ]);
    }
}
