/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.PUSHER_APP_KEY,
//     cluster: import.meta.env.PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.PUSHER_HOST ? import.meta.env.PUSHER_HOST : `ws-${import.meta.env.PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

// import Echo from 'laravel-echo'

// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: 'c64c2df76cdf6f1f4e73',
//   cluster: 'ap2',
//   forceTLS: true
// });

// var channel = Echo.channel('my-channel');
// channel.listen('.my-event', function(data) {
//   alert(JSON.stringify(data));
//   console.log(JSON.stringify(data));
// });

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

var pusher = new Pusher('c64c2df76cdf6f1f4e73', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
  console.log(JSON.stringify(data));
});

var channel2 = pusher.subscribe('channel-new-number');
channel2.bind('new-number', function(data) {
  console.log(JSON.stringify(data));
  document.getElementById('new-number').innerHTML = 'New Number : ' + data;
});