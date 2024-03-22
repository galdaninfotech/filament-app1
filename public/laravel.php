

Broadcasting Events:
use App\Events\OrderShipmentStatusUpdated;

OrderShipmentStatusUpdated::dispatch($order);
or
broadcast(new OrderShipmentStatusUpdated($update))->toOthers();

Your event must use the Illuminate\Broadcasting\InteractsWithSockets trait in order to call the toOthers method.


Receiving Broadcasts

Listening for Events
Once you have installed and instantiated Laravel Echo, you are ready to start listening for events that are broadcast from your Laravel application. First, use the channel method to retrieve an instance of a channel, then call the listen method to listen for a specified event:

Echo.channel(`orders.${this.order.id}`)
    .listen('OrderShipmentStatusUpdated', (e) => {
        console.log(e.order.name);
    });

or for private channel:
Echo.private(`orders.${this.order.id}`)
    .listen(/* ... */)
    .listen(/* ... */)
    .listen(/* ... */);


Stop Listening for Events
If you would like to stop listening to a given event without leaving the channel, you may use the stopListening method:

Echo.private(`orders.${this.order.id}`)
    .stopListening('OrderShipmentStatusUpdated')


Leaving a Channel
To leave a channel, you may call the leaveChannel method on your Echo instance:

Echo.leaveChannel(`orders.${this.order.id}`);

Namespaces
You may have noticed in the examples above that we did not specify the full App\Events namespace for the event classes. This is because Echo will automatically assume the events are located in the App\Events namespace. However, you may configure the root namespace when you instantiate Echo by passing a namespace configuration option:

window.Echo = new Echo({
    broadcaster: 'pusher',
    // ...
    namespace: 'App.Other.Namespace'
});

Alternatively, you may prefix event classes with a . when subscribing to them using Echo. This will allow you to always specify the fully-qualified class name:

Echo.channel('orders')
    .listen('.Namespace\\Event\\Class', (e) => {
        // ...
    });

===========================

Authorizing Presence Channels
All presence channels are also private channels; therefore, users must be authorized to access them. However, when defining authorization callbacks for presence channels, you will not return true if the user is authorized to join the channel. Instead, you should return an array of data about the user.

The data returned by the authorization callback will be made available to the presence channel event listeners in your JavaScript application. If the user is not authorized to join the presence channel, you should return false or null:

use App\Models\User;

Broadcast::channel('chat.{roomId}', function (User $user, int $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});

Joining Presence Channels
To join a presence channel, you may use Echo's join method. The join method will return a PresenceChannel implementation which, along with exposing the listen method, allows you to subscribe to the here, joining, and leaving events.

Echo.join(`chat.${roomId}`)
    .here((users) => {
        // ...
    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    })
    .error((error) => {
        console.error(error);
    });

The here callback will be executed immediately once the channel is joined successfully, and will receive an array containing the user information for all of the other users currently subscribed to the channel. The joining method will be executed when a new user joins a channel, while the leaving method will be executed when a user leaves the channel. The error method will be executed when the authentication endpoint returns a HTTP status code other than 200 or if there is a problem parsing the returned JSON.


===============================


Broadcasting to Presence Channels
Presence channels may receive events just like public or private channels. Using the example of a chatroom, we may want to broadcast NewMessage events to the room's presence channel. To do so, we'll return an instance of PresenceChannel from the event's broadcastOn method:

/**
 * Get the channels the event should broadcast on.
 *
 * @return array<int, \Illuminate\Broadcasting\Channel>
 */
public function broadcastOn(): array
{
    return [
        new PresenceChannel('chat.'.$this->message->room_id),
    ];
}


As with other events, you may use the broadcast helper and the toOthers method to exclude the current user from receiving the broadcast:

broadcast(new NewMessage($message));

broadcast(new NewMessage($message))->toOthers();

As typical of other types of events, you may listen for events sent to presence channels using Echo's listen method:

Echo.join(`chat.${roomId}`)
    .here(/* ... */)
    .joining(/* ... */)
    .leaving(/* ... */)
    .listen('NewMessage', (e) => {
        // ...
    });

=======================================


















