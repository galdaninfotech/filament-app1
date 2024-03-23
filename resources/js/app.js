import './bootstrap';
import { startFireworks } from './fireworks.js';

var pusher = new Pusher('c64c2df76cdf6f1f4e73', {
    cluster: 'ap2',
    authEndpoint: '/pusher/auth'
  });

  var channel1 = pusher.subscribe('numbers-channel');
  channel1.bind('numbers-event', function(data) {
      console.log(JSON.stringify(data));
      Swal.fire({
          position: "top-end",
          title: "New Number : " + data.message[0],
          showConfirmButton: false,
          timer: 2500
      });

    updateLatestRecentNumbers(data);

    document.getElementById('numbers-count').innerHTML = data.message[1];
    let numbers = data.message[2][0];
    updatenumbersDrawn(numbers);

    document.getElementById('game-status').innerHTML = 'Game Status : ' + data.message[3];

     // Play the corresponding audio file
     var audio = new Audio('/storage/58-micmonster.mp3');

    // Play the audio
    audio.play().catch(error => {
        // Autoplay was prevented, handle the error (e.g., show a play button)
        console.error('Autoplay prevented:', error.message);
    });
  });

  function updateLatestRecentNumbers(data) {
      const lastDivs = document.querySelectorAll(".slider__item:last-child");
      lastDivs.forEach(lastDiv => {
          // Remove specific classes from the classList
          lastDiv.classList.remove("animate__animated", "animate__bounceIn", "new-number");

          // Alternatively, to remove all classes, you can use:
          // lastDiv.className = '';
      });

      const newDiv = document.createElement("div");
      newDiv.innerHTML = data.message[0];
      newDiv.classList.add('slider__item', 'animate__animated', 'animate__bounceIn', 'new-number');
      document.getElementById("slider__content").appendChild(newDiv);
  }

  function updatenumbersDrawn(numbers) {
    let newNumber = numbers[numbers.length - 1];
    var ul = document.getElementById("drawn-numbers-sequance");
    var li = document.createElement("li");
    li.appendChild(document.createTextNode(newNumber));
    li.setAttribute("class", "w-10 h-10 bg-gray-300 flex justify-center items-center"); // added line
    ul.appendChild(li);

    let el = document.querySelector('#all-numbers > li.number-box:nth-child('+ newNumber +')');
    el.setAttribute("class", "number-box drawn w-10 h-10 bg-gray-300 flex justify-center items-center")
  //   window.location.reload();
  //   Livewire.dispatch('refreshComponent');
  }

  function updateActiveClaims(data) {
       // Assuming data.message[0] contains the newly received claim
      const claim = data.message[0];

      // Create a new claim item container
      const newClaimContainer = document.createElement('div');
      newClaimContainer.classList.add('claim-item');

      // Create HTML structure for the claim
      newClaimContainer.innerHTML = `
      <div class="flex items-center text-xs mb-1 w-full" style="justify-content: space-between">
          <div>${claim.user_name}</div>
          <div>Prize : ${claim.prize_name}</div>
          <div>Ticket No : ${claim.ticket_id}</div>
          <div>Status : ${claim.status}</div>
      </div>
      <div class="claim-ticket">
          <!-- Ticket details here -->
      </div>
      `;

      // Parse ticket details from the claim object
      const ticketDetails = JSON.parse(claim.ticket);

      // Loop through ticketDetails to create ticket elements
      ticketDetails.forEach(row => {
          const rowContainer = document.createElement('div'); rowContainer.classList.add('claim-row');
          row.forEach(cell => {
              const column = document.createElement('div'); column.classList.add('claim-cell');
              const span = document.createElement('span');
              if(cell.checked == 1) {
                  span.classList.add('flex', 'justify-center', 'items-center', 'checked');
              }
              if (cell.checked == 0) {
                span.classList.add('flex', 'justify-center', 'items-center', 'unchecked');
              }
              span.textContent = cell.value || '';
              column.appendChild(span);
              rowContainer.appendChild(column);
          });

          newClaimContainer.querySelector('.claim-ticket').appendChild(rowContainer);
      });

      // Append the new claim item to the claims list
      const claimContainer = document.querySelector('.claim-list');
      claimContainer.appendChild(newClaimContainer);
  }

  //Listen for claim event and set game status
  var channel2 = pusher.subscribe('claim-channel');
  channel2.bind('claim-event', function(data) {
    console.log(data.message[0]);
      Swal.fire({
          position: "top-end",
          title: "New Claim From : " + data.message[0].user_name,
          showConfirmButton: false,
          timer: 2500
      });

    // increment the claims count by adding 1
    const spanElement = document.getElementById('active-claims');
    let currentNumber = parseInt(spanElement.textContent);
    let newNumber = currentNumber + 1;
    spanElement.textContent = newNumber.toString();

    updateActiveClaims(data);

    document.getElementById('game-status').innerHTML = 'Game Status : Paused';
  });



// Subscribe to the Private Winner Channel:
const channelName = `private-winner-channel.${userId}`;
const privateChannel = pusher.subscribe(channelName);
privateChannel.bind('winner-event', function(data) {
    console.log(data.message[1]);
    alert('You Won!!!!!');
    startFireworks();

    Swal.fire({
        position: "top-end",
        title: "You Won!" + data.message[1].prize_name,
        showConfirmButton: false,
        timer: 2500
    });
});


  var callback = (eventName, data) => {
    console.log(
      `bind global channel: The event ${eventName} was triggered with data ${JSON.stringify(
        data
      )}`
    );
  };
  // bind to all events on the channel
//   privateChannel.bind_global(callback);
//   privateChannel.bind('winner-event', function(data) {
//     alert('cccccccccccccccccccccccccccccccccccccccccccc');
//       console.log(data.message[1]);
//       startFireworks();

//       Swal.fire({
//           position: "top-end",
//           title: "You Won!" + data.message[1].prize_name,
//           showConfirmButton: false,
//           timer: 2500
//       });
//   });


  // Subscribe to the Public Winner Channel
  var publicWinnerChannel = pusher.subscribe('winner-channel');
  publicWinnerChannel.bind('winner-event', function(data) {
      console.log(data.message);

      Swal.fire({
          position: "top-end",
          title: "New Winner : " + data.message[1].user_name,
          showConfirmButton: false,
          timer: 2500
      });

      // increment the claims count by adding 1
      const spanElement = document.getElementById('winners-count');
      let currentNumber = parseInt(spanElement.textContent);
      let newNumber = currentNumber + 1;
      spanElement.textContent = newNumber.toString();

      // update winners list
      const prizeName = data.message[1].prize_name;
      const userName = data.message[1].user_name;
      const el = document.querySelector('td[data-prize="'+prizeName+'"]');
      el.innerText = userName;

  });
