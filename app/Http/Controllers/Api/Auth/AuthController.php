<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Create user using email
    public function createUser(Request $request) {
        
        try {
            
                $validate = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]);

                if($validate->fails()){
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validate->errors()
                    ], 401);
                }

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'User created successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        
    }

    //Login The User
    public function loginUser(Request $request) {
        try {
            $validate = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


   
}
