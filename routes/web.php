<?php

use Illuminate\Support\Facades\Route;
use App\Classes\Table;
use App\Classes\Test;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    // $prizes = DB::table('game_prize')
    //             ->where('game_id', '=', 2)
    //             ->where('prize_id', '=', 3)
    //             ->update([
    //                 'active' => 0
    //             ]);
    // dd($prizes);
        
    
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::get('send-email', function(){
    $mailData = [
        "name" => "Test NAME",
        "email" => "email.email.com",
        "message" => "message"
    ];

    Mail::to("hello@example.com")->send(new TestMail($mailData));

    // dd("Mail Sent Successfully!");
});