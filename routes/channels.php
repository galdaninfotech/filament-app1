<?php

use Illuminate\Support\Facades\Broadcast;

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

// Broadcast::channel('channel-new-number', []);
// Broadcast::channel('my-channel', []);

Broadcast::channel('channel-new-number', function(){
    return true;
});
Broadcast::channel('my-channel', function(){
    return true;
});
