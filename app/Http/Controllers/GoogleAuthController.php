<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    //
   public function redirect(){
         return Socialite::driver('google')->redirect();    

   }
   public function callbackGoogle(){
       try {
        $google_user = Socialite::driver('google')->user();
        $user = User::where('google_id', $google_user->getId())->orWhere('email', $google_user->getEmail())->first();
        if(!$user){
            $new_User = User::create(
                [
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'password' => bcrypt(uniqid()), // Mật khẩu ngẫu nhiên
                    'auth_token' => Str::random(60), // Dùng auth_token
                ]);
            Auth::login($new_User);
            return redirect('/home');
        }
        else{
            $user->auth_token = Str::random(60); // Dùng auth_token
            $user->save();
            Auth::login($user);
            return redirect('/home');
        }
       }
         catch(\Throwable $th){
             dd("error".$th->getMessage());
         }
   }
}