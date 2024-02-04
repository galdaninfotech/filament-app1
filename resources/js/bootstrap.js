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
  document.getElementById('new-number').innerHTML = 'New Number : ' + data.message[0];
  document.getElementById('count').innerHTML = 'Number Count : ' + data.message[1];
  let numbers = data.message[2][0];
  updatenumbersDrawn(numbers);

   // Play the corresponding audio file
   var audio = new Audio('/storage/58.mp3');

  // Play the audio
  audio.play().catch(error => {
    // Autoplay was prevented, handle the error (e.g., show a play button)
    console.error('Autoplay prevented:', error.message);
});
});


function updatenumbersDrawn(numbers) {
  let newNumber = numbers[numbers.length - 1];
  var ul = document.getElementById("drawn-numbers-sequance");
  var li = document.createElement("li");
  li.appendChild(document.createTextNode(newNumber));
  li.setAttribute("class", "w-10 h-10 bg-gray-300 flex justify-center items-center"); // added line
  ul.appendChild(li);

  let el = document.querySelector('#all-numbers > li.number-box:nth-child('+ newNumber +')');
  el.setAttribute("class", "number-box drawn w-10 h-10 bg-gray-300 flex justify-center items-center")
}

//Listen for game status channel
var channel = pusher.subscribe('claim-channel');
channel.bind('claim-event', function(data) {
  console.log(JSON.stringify(data));
  document.getElementById('game-status').innerHTML = 'Game Status : ' + data.message[0];
});