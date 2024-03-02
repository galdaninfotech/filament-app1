// /**
//  * We'll load the axios HTTP library which allows us to easily issue requests
//  * to our Laravel back-end. This library automatically handles sending the
//  * CSRF token as a header based on the value of the "XSRF" token cookie.
//  */
import { startFireworks } from './fireworks.js';
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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
    newClaimContainer.classList.add('claim__item', 'mb-6');

    // Create HTML structure for the claim
    newClaimContainer.innerHTML = `
        <div class="flex items-center justify-between space-x-6">
            <div>${claim.user_name}</div>
            <div>Status: ${claim.status}</div>
        </div>
        <div class="ticket">
            <!-- Ticket details here -->
        </div>
    `;

    // Parse ticket details from the claim object
    const ticketDetails = JSON.parse(claim.ticket);

    // Loop through ticketDetails to create ticket elements
    ticketDetails.forEach(row => {
        const rowContainer = document.createElement('div');
        rowContainer.classList.add('row', 'flex', 'gap-1', 'mt-2');

        row.forEach(cell => {
            const column = document.createElement('div');
            column.classList.add('column', 'w-8', 'h-8', 'flex', 'justify-center', 'items-center');
            const span = document.createElement('span');
            if(cell.checked == 1) {
                span.classList.add('w-full', 'h-full', 'text-xs', 'p-2', 'checked');
            } else {
                span.classList.add('w-full', 'h-full', 'text-xs', 'p-2', 'unchecked');
            }
            span.textContent = cell.value || '';
            column.appendChild(span);
            rowContainer.appendChild(column);
        });

        newClaimContainer.querySelector('.ticket').appendChild(rowContainer);
    });

    // Append the new claim item container to the main container of claims
    const claimContainer = document.querySelector('.claim__container');
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



// Subscribe to the Private Winner Channel
const channelName = `private-winner-channel.${userId}`;
console.log(channelName);
const channel3 = pusher.subscribe(channelName); // Replace '5' with the claimer's ID
var callback = (eventName, data) => {
  console.log(
    `bind global channel: The event ${eventName} was triggered with data ${JSON.stringify(
      data
    )}`
  );
};
// bind to all events on the channel
channel3.bind_global(callback);
channel3.bind('winner-event', function(data) {
    console.log(data.message[1]);
    startFireworks();

    Swal.fire({
        position: "top-end",
        title: "You Won!" + data.message[1].prize_name,
        showConfirmButton: false,
        timer: 2500
    });
});


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

    //TODO: update winners list

    const winner = data.message[1];
    const prizeName = winner.prize_name;

    // Find all existing rows with matching prize_name
    const existingRows = document.querySelectorAll(`#tabpanel-3 table tbody tr[data-prize="${prizeName}"]`);

    // Flag to track if the winner has been updated
    let winnerUpdated = false;

    existingRows.forEach(row => {
        // Check if winner is empty in the current row
        const winnerCell = row.querySelector('td:nth-child(3)');
        if (!winnerUpdated && !winnerCell.textContent.trim()) {
            // Update the winner in the current row
            winnerCell.textContent = winner.user_name;
            winnerUpdated = true; // Set the flag to true indicating winner has been updated
        }
    });

    // If winner hasn't been updated yet, create a new row
    if (!winnerUpdated) {
        // Find the first empty row index
        let emptyRowIndex = 0;
        const allRows = document.querySelectorAll('#tabpanel-3 table tbody tr');
        allRows.forEach((row, index) => {
            const winnerCell = row.querySelector('td:nth-child(3)');
            if (!winnerCell.textContent.trim()) {
                emptyRowIndex = index;
                return;
            }
        });

        // Create a new row
        const newWinnerTr = document.createElement('tr');
        newWinnerTr.setAttribute('data-prize', prizeName);
        newWinnerTr.innerHTML = `
            <td>${winner.prize_name}</td>
            <td>${winner.prize_amount}</td>
            <td>${winner.user_name}</td>
        `;

        // Insert the new row at the found index
        const tableBody = document.querySelector('#tabpanel-3 table > tbody');
        const referenceRow = allRows[emptyRowIndex];
        tableBody.insertBefore(newWinnerTr, referenceRow);
    }


    // const winner = data.message[1];
    // const newWinnerTr = document.createElement('tr');
    // // Create HTML structure for the winner
    // newWinnerTr.innerHTML = `
    //     <td>${winner.prize_name}</td>
    //     <td>${winner.prize_amount}</td>
    //     <td>${winner.user_name}</td>
    // `;
    // const tableBody = document.querySelector('#tabpanel-3 table > tbody');
    // tableBody.appendChild(newWinnerTr);
});

// alert('ggggggggggggg');
// swal({
//     title: "Success",
//     text:
//       "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
//     icon: "success",
//     buttons: {
//       cancel: "Cancel",
//       confirm: "Okay"
//     },
//     closeOnClickOutside: false
// });






