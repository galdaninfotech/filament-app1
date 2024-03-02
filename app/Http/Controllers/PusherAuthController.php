<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

use Illuminate\Support\Facades\Auth;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
    {
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
    }
}
