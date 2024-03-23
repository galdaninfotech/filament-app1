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
use App\Http\Controllers\PaymentController;
use App\Models\Game;
use App\Livewire\Tickets;
use App\Livewire\TestTicket;
use App\Livewire\PlayerPayment;
use App\Models\User;

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

        Route::get('/agora-chat', function () {
            return view('agora-chat');
        });

        Route::post('/updateChecked', [Tickets::class, 'updateChecked']); // Update toggle state of numbers
        Route::post('/toggleAutoMode', [Tickets::class, 'toggleAutoMode']); // toggle automode

        Route::get('/test-ticket', TestTicket::class);
});


// Route::post('/pusher/auth', 'PusherAuthController@authenticate');
Route::post('/pusher/auth', function(Request $request) {
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
});

// Video Call Endpoints
Route::post('/agora/token', 'App\Http\Controllers\AgoraVideoController@token');
Route::post('/agora/call-user', 'App\Http\Controllers\AgoraVideoController@callUser');


// Payment Page
Route::get('/player-payment', PlayerPayment::class);
// Payment With Omnipay for paypal
Route::get('/payment', [PaymentController::class, 'payment'])->name('payment');
Route::get('/success', [PaymentController::class, 'success']);
Route::get('/error', [PaymentController::class, 'error']);

// Test Routes
// Route::post('/updateChecked', [Tickets::class, 'updateChecked']);
