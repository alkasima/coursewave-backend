<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //Google  Social  Auth
    public function googleDirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle() {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('social_id', $googleUser->getId)->first();

            if(!$user) {
                $new_user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'social_id' => $googleUser->getId(),
                ]);

                Auth::login($user);
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }else {
                
                Auth::login($user);
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }

        } catch(\Throwable $th) {
            //Throw $th;
        }
    }
}
