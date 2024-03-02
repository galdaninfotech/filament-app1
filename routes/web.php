<?php

use Illuminate\Support\Facades\Route;
use App\Classes\Table;
use App\Classes\Test;

use App\Http\Controllers\PusherAuthController;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
// use RealRashid\SweetAlert\Facades\Alert;
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

// Route::post('/pusher/auth', 'PusherAuthController@authenticate');
Route::post('/pusher/auth', function(Request $request) {

    // dd($request);
    $socketId = $request->post('socket_id');
    $channelName = $request->post('channel_name');

    if (Auth::check()) {
        $user = Auth::user();
        // Check if the user has permission to subscribe to the channel
        if ($user->hasPermissionToSubscribeToChannel($channelName)) {
            // Authenticate the user for the channel
            $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'));
            echo $pusher->socket_auth($channelName, $socketId);
        } else {
            abort(403, 'Unauthorized action.');
        }
    } else {
        abort(401, 'Unauthenticated.');
    }
    // echo $pusher->socket_auth('winner-channel.2', '28167.375585');
});
