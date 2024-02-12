/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import { startFireworks } from './fireworks.js';
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

var pusher = new Pusher('c64c2df76cdf6f1f4e73', {
  cluster: 'ap2'
});

var channel1 = pusher.subscribe('numbers-channel');
channel1.bind('numbers-event', function(data) {
  console.log(JSON.stringify(data));
  document.getElementById('new-number').innerHTML = 'New Number : ' + data.message[0];
  document.getElementById('count').innerHTML = 'Number Count : ' + data.message[1];
  let numbers = data.message[2][0];
  updatenumbersDrawn(numbers);

   // Play the corresponding audio file
   var audio = new Audio('/storage/58-micmonster.mp3');

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

// //Listen for game status channel
var channel2 = pusher.subscribe('claim-channel');
channel2.bind('claim-event', function(data) {
  console.log(JSON.stringify(data));
  document.getElementById('game-status').innerHTML = 'Game Status : ' + data.message[0];
});

// //Listen for winner event
var channel3 = pusher.subscribe('winner-channel');
channel3.bind('winner-event', function(data) {
  // let el = document.querySelectorAll('td.claim-id')
  // for(var i = 0; i => el.length; i++){
  //   // alert(el.length);
  //   if(el.innerText == data){
  //     startFireworks();
  //   }
  // }
  startFireworks();
  console.log(JSON.stringify(data));
  // document.getElementById('game-status').innerHTML = 'Game Status : ' + data.message[0];
});

