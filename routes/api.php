<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logincontroller;
use App\Http\Controllers\DriverController;

Route::post('/login', [LoginController::class, 'submit']);
Route::post('/login/verify', [LoginController::class, 'verify']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    // მძღოლის ინფორმაცია
    Route::get('/driver',[DriverController::class, 'show']);
    // განახლება მძღოლის ინფორმაციის
    Route::post('/driver',[DriverController::class, 'update']);

    //შევქმენით მარრშუტი
    Route::post('/trip', [Tripcontroller::class, 'store']);
    //მივიღეთ მარშუტი
    Route::prefix('trip/{trip}')->controller(TripController::class)->group(function () {
        Route::post('/accept', 'accept');
        Route::post('/start', 'start');
        Route::post('/end', 'end');
        Route::post('/location', 'location');
    });


    Route::get('/user', function(Request $request){
        return $request->user();
    });
});