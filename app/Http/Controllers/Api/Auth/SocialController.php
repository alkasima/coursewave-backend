<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //Google  Social  Auth
    public function googleDirect() {
        $authUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

         return response()->json([
            'status' => true,
            'message' => 'Google Auth URL',
            'auth_url' => $authUrl
            
            ]
        
        );
    }

    public function callbackGoogle(Request $request) {
        
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        
            $user = User::where('social_id', $googleUser->getId())->first();
        
            if (!$user) {
                $new_user = User::create([
                    'first_name' => $googleUser->user['given_name'],
                    'last_name' => $googleUser->user['family_name'],
                    'email' => $googleUser->getEmail(),
                    'social_id' => $googleUser->user['id'],
                ]);
        
                Auth::login($new_user); // Use $new_user here, not $user
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $new_user->createToken("API TOKEN")->plainTextToken
                ], 200);
            } else {
                Auth::login($user);
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
        
        } catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //Github  Social  Auth
    public function githubDirect() {
        $authUrl = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();

         return response()->json([
            'status' => true,
            'message' => 'Github Auth URL',
            'auth_url' => $authUrl
            
            ]
        
        );
    }

    public function callbackGithub(Request $request) {
        
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

             // Split the full name into first name and last name
            $fullName = $githubUser->name;
            $nameParts = explode(' ', $fullName);
            $first_name = $nameParts[0];
            $last_name = isset($nameParts[1]) ? $nameParts[1] : '';

            // Get the email and id
            $email = $githubUser->email;
            //$id = $githubUser->id;
            
            
            $user = User::where('social_id', $githubUser->id)->first();
        
            if (!$user) {
                $new_user = User::create([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'social_id' => $githubUser->id,
                ]);
        
                Auth::login($new_user); // Use $new_user here, not $user
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $new_user->createToken("API TOKEN")->plainTextToken
                ], 200);
            } else {
                Auth::login($user);
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
        
        } catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
