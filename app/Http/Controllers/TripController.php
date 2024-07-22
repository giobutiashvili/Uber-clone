<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TripAccapted;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        return $request->user()->trips()->create($request->only([
            'origin',
            'destination' ,
            'destination_name',
        ]));
    }
    public function show(Request $request, Trip $trip)
    {
        if ($trip->user-id == $request->user()->id){
            return $trip;
        }

        if ($trip->driver && $request->user()->driver){
            if ($trip->driver-id == $request->user()->driver->id){
                return $trip;
            }
        }
        return response()->json(['message'=> 'მარშუტი არ მოიძებნა'], 401);
    }

    public function accept(Request $request, Trip $trip)
    {

        // მძღოლის თანხმობა მარშუტზე
        $request->validate([
            'driver_location' => 'required'
        ]);

        $trip->update([
            'driver_id'=> $request->user()->id,
            'driver_location'=>$request->driver_location,
        ]);

        $trip->load('driver.user'); 

        TripAccapted::dispatch($trip, $request->user());

        return $trip;

    }
    public function start(Request $request, Trip $trip)
    {
        // მარშუტის დაწყება    
        $trip->update([
            'is_started' => true
        ]);

        $trip->load('driver.user'); 

        TripStarted::dispatch($trip, $request->user());

        return $trip;
    }
    public function end(Request $request, Trip $trip)
    {
        // მარშუტის დამთავრება
        $trip->update([
            'complete' => true
        ]);
        
        $trip->load('driver.user'); 

        TripEnded::dispatch($trip, $request->user());

        return $trip;
    }
    public function location(Request $request, Trip $trip)
    {
        // განახლება მძღოლის მიმდინარე ლოკაციის
        $request->validate([
            'driver_location' => 'required'
        ]);
        $trip->update([
            'driver_location' => $request->driver_location
        ]);

        $trip->load('driver.user'); 

        TripLocationUpdate::dispatch($trip, $request->user());

        return $trip;
    }

}
