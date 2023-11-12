<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //Google Social Auth
    public function googleDirect() {
        return Socialite::driver('google')->redirect();
    }
}
