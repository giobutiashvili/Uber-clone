<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function subtmit(Request $request)
    {
        // ვალიდაცია ტელეფონის ნომრის 
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        // შექმენი და იპოვე მომხმარებლის მოდელი
        $user-> User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if(!$user){
            return response()->json(['massage'=>'მომხმარებლის ნომერი არ მოიძებნა'], 401);
        }

        // გააგზავნე ერთჯერადი კოდი მომხმარებელს
        $user ->notify();

        // დააბრუნე პასუხი


    }
}
