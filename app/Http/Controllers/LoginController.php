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
            return response()->json(['message'=>'მომხმარებლის ნომერი არ მოიძებნა'], 401);
        }

        // გააგზავნე ერთჯერადი კოდი მომხმარებელს
        $user ->notify(new LoginVerification());

        // დააბრუნე პასუხი
        return response()->json(['message' => 'კოდი გაიგზავნა']);

    }


    public function verify(Request $request)
    {
        // გადაამოწმე შემომავალი მოთხოვნა
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:11111, 99999',
        ]);

        // მომხმარებელის პოვნა 
        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
            ->first();

        // არის თუ არა ეს იგივე შენახული კოდი
        
        // თუ არის დააბრუნე ავტორიზაციის ტოკენი 
        if ($user) {
            $user->update([
                'login_code' => null
            ]);
          return $user->createToken($request->login_code)->plainTextToken;  
        }
        
        // თუ არა დააბრუნე შეტყობინება
        return response()->json(['message' => 'არასწორი ვერიფიკაციის კოდი'], 401);
    }
}
