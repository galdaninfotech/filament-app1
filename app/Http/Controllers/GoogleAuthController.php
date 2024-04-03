<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;


class GoogleAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        // return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // try {
        //     $googleUser = Socialite::driver('google')->user();
        //     $user = User::where('google_id', $googleUser->getId())->first();

        //     if(!$user){
        //         $newUser = User::create([
        //             'name' => $googleUser->getName(),
        //             'email' => $googleUser->getEmail(),
        //             'google_id'=> $googleUser->getId()
        //         ]);

        //         Auth::login($newUser);
        //         return redirect()->intended('dashboard');
        //     }else{
        //         Auth::login($user);
        //         return redirect()->intended('dashboard');
        //     }

        // } catch (Exception $e) {
        //     return redirect()->intended('login');
        // }
    }
}
