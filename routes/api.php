<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([

    'middleware' => 'api',
//    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout')->middleware('jwt.auth');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user', 'App\Http\Controllers\AuthController@me')->middleware('jwt.auth');;

});

Route::get('airport', 'App\Http\Controllers\AirportController');
Route::get('flight', 'App\Http\Controllers\FlightController');
Route::post('booking', 'App\Http\Controllers\BookingController');
Route::get('booking/{code}', 'App\Http\Controllers\BookingController@show');
Route::get('booking/{code}/seat', 'App\Http\Controllers\BookingController@seat');
Route::patch('booking/{code}/seat', 'App\Http\Controllers\BookingController@update')->middleware('jwt.auth');
Route::get('user/booking', 'App\Http\Controllers\BookingController@user')->middleware('jwt.auth');


