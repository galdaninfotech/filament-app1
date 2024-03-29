<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Claim;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('numbers-channel', function(){
    return true;
});

Broadcast::channel('claim-channel', function(){
    return true;
});

Broadcast::channel('winner-channel', function(){
    // dd('public');
    return true;
});

Broadcast::channel('winner-channel.{userId}', function ($user, $userId) {
    // dd('private');
    return $user->id === $userId;
});



// Videos Channels
Broadcast::channel('video-channel', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
Broadcast::channel('agora-online-channel', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

