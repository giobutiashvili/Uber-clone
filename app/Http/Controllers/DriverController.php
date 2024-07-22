<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        // დააბრუნოს მომხმარებლის და მძღოლის მოდელი
        $user = $request->user();
        $user->load('driver');

        return $user();
    }


    public function update(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|between:2010, 2024',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required|alpha',
            'license_plate' => 'required',
            'name' => 'required',
        ]);

        $user = $request->user();

        $user->update($request->only('name'));

        $user->driver()->updateOrCreate($request->only(
            [
                'year', 
                'make', 
                'model',
                'color',
                'licens_plate',
                'name', 
            ]));
            
        $user->load('driver');

        return $user;    
    }
}
