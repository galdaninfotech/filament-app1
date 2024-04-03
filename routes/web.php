<?php

use Illuminate\Support\Facades\Route;
use App\Classes\Table;
use App\Classes\Test;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PusherAuthController;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PaymentController;
use App\Models\Game;
use App\Models\Ticket;
use App\Livewire\Tickets;
use App\Livewire\TestTicket;
use App\Livewire\PlayerPayment;
use App\Livewire\Board;
use App\Models\User;

// use RealRashid\SweetAlert\Facades\Alert;
use Laravel\Socialite\Facades\Socialite;
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
        Route::post('/toggleAutoTick', [Tickets::class, 'toggleAutoTick']); // toggle autotick
        Route::post('/toggleAutoClaim', [Tickets::class, 'toggleAutoClaim']); // toggle autoclaim

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

// Google Login
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google-auth');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


// Test Routes
Route::get('/checkTicket', function(){
    $ticket = Ticket::find(9);
    // dd($ticket->object);
    // $drawnNumbers = DB::table('game_number')->pluck('number_id')->toArray();
    $drawnNumbers = [0,1,2,3,4,5,6,7,8,9,10,
                     11,12,13,14,15,16,17,18,19,20,
                     21,22,23,24,25,26,27,28,29,30,
                     31,32,33,34,35,36,37,38,39,40,
                     41,42,43,44,45,46,47,48,49,50,
                     51,52,53,54,55,56,57,58,59,60,
                     61,62,63,64,65,66,67,68,69,70,
                     71,72,73,74,75,76,77,78,79,80,
                     81,82,83,84,85,86,87,88,89,90,
                    ];
    // dd($drawnNumbers);
    $flag = true;
    foreach ($ticket->object[0] as $number) {
        if($number['value'] > 0){
            if (!in_array($number['value'], $drawnNumbers)) {
                $flag = false;
                break;
            }
        }

    }
    if($flag) {
        // $this->autoClaim($ticket->id, $gamePrizeId = 1, $prizeName = 'Full House');
        return 'true';
    } else {
        return 'False';
    }


});
