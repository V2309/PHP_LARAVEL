<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class FacebookAuthController extends Controller
{
    //
    public function redirect(){
        return Socialite::driver('facebook')->redirect();
    }

    public function callbackFacebook(){
        try {
            $facebook_user = Socialite::driver('facebook')->user();
            $user = User::where('facebook_id', $facebook_user->getId())->first();
            if(!$user){
                $new_User = User::create(
                    [
                        'name' => $facebook_user->getName(),
                        'email' => $facebook_user->getEmail(),
                        'facebook_id' => $facebook_user->getId(),
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
