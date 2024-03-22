
Livewire

Events:
$this->dispatch('post-created');

#[On('post-updated.{title}')]
public function updatePostList($title)
{
    // ...
}


Listening for events from specific child components
Livewire allows you to listen for events directly on individual child components in your Blade template like so:

<div>
    <livewire:edit-post @saved="$refresh">
 
    <!-- ... -->
</div>
In the above scenario, if the edit-post child component dispatches a saved event, the parent's $refresh will be called and the parent will be refreshed.

Instead of passing $refresh, you can pass any method you normally would to something like wire:click. Here's an example of calling a close() method that might do something like close a modal dialog:

<livewire:edit-post @saved="close">
If the child dispatched parameters along with the request, for example $this->dispatch('close', postId: 1), you can forward those values to the parent method using the following syntax:

<livewire:edit-post @saved="close($event.detail.postId)">

===========================================================================

Using JavaScript to interact with events
Livewire's event system becomes much more powerful when you interact with it from JavaScript inside your application. This unlocks the ability for any other JavaScript in your app to communicate with Livewire components on the page.

#Listening for events inside component scripts
You can easily listen for the post-created event inside your component's template from a @script directive like so:

@script
    <script>
        $wire.on('post-created', () => {
            //
        });
    </script>
@endscript
The above snippet would listen for the post-created from the component it's registered within. If the component is no longer on the page, the event listener will no longer be triggered.


======================================================================

Dispatching events from component scripts
Additionally, you can dispatch events from within a component's @script like so:

@script
<script>
    $wire.dispatch('post-created', { refreshPosts: true });
</script>
@endscript
When the above @script is run, the post-created event will be dispatched to the component it's defined within.

To dispatch the event only to the component where the script resides and not other components on the page (preventing the event from "bubbling" up), you can use dispatchSelf():

$wire.dispatchSelf('post-created');

You can now access those event parameters from both your Livewire class and also other JavaScript event listeners.

Here's an example of receiving the refreshPosts parameter within a Livewire class:

#[On('post-created')]
public function handleNewPost($refreshPosts = false)
{
    //
}

You can also access the refreshPosts parameter from a JavaScript event listener from the event's detail property:

@script
    <script>
        $wire.on('post-created', (event) => {
            let refreshPosts = event.detail.refreshPosts
    
            // ...
        });
    </script>
@endscript
=========================================================================

Listening for Livewire events from global JavaScript
Alternatively, you can listen for Livewire events globally using Livewire.on from any script in your application:

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('post-created', (event) => {
           //
       });
    });
</script>
The above snippet would listen for the post-created event dispatched from any component on the page.



==============================================================================================================================================================================================================================================================================================================================


Events in Alpine
Because Livewire events are plain browser events under the hood, you can use Alpine to listen for them or even dispatch them.

#Listening for Livewire events in Alpine
For example, we may easily listen for the post-created event using Alpine:

<div x-on:post-created="..."></div>
The above snippet would listen for the post-created event from any Livewire components that are children of the HTML element that the x-on directive is assigned to.

To listen for the event from any Livewire component on the page, you can add .window to the listener:

<div x-on:post-created.window="..."></div>
If you want to access additional data that was sent with the event, you can do so using $event.detail:

<div x-on:post-created="notify('New post: ' + $event.detail.title)"></div>

==================================================================

Dispatching Livewire events from Alpine
Any event dispatched from Alpine is capable of being intercepted by a Livewire component.

For example, we may easily dispatch the post-created event from Alpine:

<button @click="$dispatch('post-created')">...</button>
Like Livewire's dispatch() method, you can pass additional data along with the event by passing the data as the second parameter to the method:

<button @click="$dispatch('post-created', { title: 'Post Title' })">...</button>

You might not need events
If you are using events to call behavior on a parent from a child, you can instead call the action directly from the child using $parent in your Blade template. For example:

<button wire:click="$parent.showCreatePostForm()">Create Post</button>

=======================================================================


Dispatching directly to another component
If you want to use events for communicating directly between two components on the page, you can use the dispatch()->to() modifier.

Below is an example of the CreatePost component dispatching the post-created event directly to the Dashboard component, skipping any other components listening for that specific event:

use Livewire\Component;
 
class CreatePost extends Component
{
    public function save()
    {
        // ...
 
        $this->dispatch('post-created')->to(Dashboard::class);
    }
}

Dispatching a component event to itself
Using the dispatch()->self() modifier, you can restrict an event to only being intercepted by the component it was triggered from:

use Livewire\Component;
 
class CreatePost extends Component
{
    public function save()
    {
        // ...
 
        $this->dispatch('post-created')->self();
    }
}


=====================================================


Dispatching events from Blade templates
You can dispatch events directly from your Blade templates using the $dispatch JavaScript function. This is useful when you want to trigger an event from a user interaction, such as a button click:

<button wire:click="$dispatch('show-post-modal', { id: {{ $post->id }} })">
    EditPost
</button>
In this example, when the button is clicked, the show-post-modal event will be dispatched with the specified data.

If you want to dispatch an event directly to another component you can use the $dispatchTo() JavaScript function:

<button wire:click="$dispatchTo('posts', 'show-post-modal', { id: {{ $post->id }} })">
    EditPost
</button>
In this example, when the button is clicked, the show-post-modal event will be dispatched directly to the Posts component.

======================================================================
===========================================================================================================================================================================================================================================================================================================================

Real-time events using Laravel Echo
Livewire pairs nicely with Laravel Echo to provide real-time functionality on your web-pages using WebSockets.

=======================================================================

Listening for Echo events
Imagine you have an event in your Laravel application named OrderShipped:

<?php
 
namespace App\Events;
 
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class OrderShipped implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    public Order $order;
 
    public function broadcastOn()
    {
        return new Channel('orders');
    }
}

You might dispatch this event from another part of your application like so:

use App\Events\OrderShipped;
 
OrderShipped::dispatch();
If you were to listen for this event in JavaScript using only Laravel Echo, it would look something like this:

Echo.channel('orders')
    .listen('OrderShipped', e => {
        console.log(e.order)
    })
Assuming you have Laravel Echo installed and configured, you can listen for this event from inside a Livewire component.

Below is an example of an OrderTracker component that is listening for the OrderShipped event in order to show users a visual indication of a new order:

<?php
 
namespace App\Livewire;
 
use Livewire\Attributes\On; 
use Livewire\Component;
 
class OrderTracker extends Component
{
    public $showNewOrderNotification = false;
 
    #[On('echo:orders,OrderShipped')]
    public function notifyNewOrder()
    {
        $this->showNewOrderNotification = true;
    }
 
    // ...
}
If you have Echo channels with variables embedded in them (such as an Order ID), you can define listeners via the getListeners() method instead of the #[On] attribute:

<?php
 
namespace App\Livewire;
 
use Livewire\Attributes\On; 
use Livewire\Component;
use App\Models\Order;
 
class OrderTracker extends Component
{
    public Order $order;
 
    public $showOrderShippedNotification = false;
 
    public function getListeners()
    {
        return [
            "echo:orders.{$this->order->id},OrderShipped" => 'notifyShipped',
        ];
    }
 
    public function notifyShipped()
    {
        $this->showOrderShippedNotification = true;
    }
 
    // ...
}
Or, if you prefer, you can use the dynamic event name syntax:

#[On('echo:orders.{order.id},OrderShipped')]
public function notifyNewOrder()
{
    $this->showNewOrderNotification = true;
}
If you need to access the event payload, you can do so via the passed in $event parameter:

#[On('echo:orders.{order.id},OrderShipped')]
public function notifyNewOrder($event)
{
    $order = Order::find($event['orderId']);
 
    //
}

==============================================================================

Private & presence channels
You may also listen to events broadcast to private and presence channels:

Before proceeding, ensure you have defined Authentication Callbacks for your broadcast channels.

<?php
 
namespace App\Livewire;
 
use Livewire\Component;
 
class OrderTracker extends Component
{
    public $showNewOrderNotification = false;
 
    public function getListeners()
    {
        return [
            // Public Channel
            "echo:orders,OrderShipped" => 'notifyNewOrder',
 
            // Private Channel
            "echo-private:orders,OrderShipped" => 'notifyNewOrder',
 
            // Presence Channel
            "echo-presence:orders,OrderShipped" => 'notifyNewOrder',
            "echo-presence:orders,here" => 'notifyNewOrder',
            "echo-presence:orders,joining" => 'notifyNewOrder',
            "echo-presence:orders,leaving" => 'notifyNewOrder',
        ];
    }
 
    public function notifyNewOrder()
    {
        $this->showNewOrderNotification = true;
    }
}


=======================================================================
















