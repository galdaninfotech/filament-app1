<div>
    @once
        <script>
            const ticketPrice = {{ $activeGame->ticket_price }};
        </script>
    @endonce

    <x-bladewind.centered-content size="big" class="p-4">
        @php
        // Serialize the tickets into a query string
            $serializedTickets = http_build_query(['newTickets' => json_decode($newTickets)]);
        @endphp

        <b>Select your tickets</b>
        <div class="grid grid-cols-2 gap-4 justify-center mt-6 text-black">
            <x-bladewind.input numeric="true"  required="true" id="noOfTicketsInput" name="noOfTickets"
            label="How many tickets?" error_message="You must select at least 1 ticket!" value="6" />

            <input type="hidden" name="amount" value="10">
            <button
                wire:click.prevent="updateNewTickets(document.getElementById('noOfTicketsInput').value)"
                class="flex items-center justify-center py-2 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                >
                Generate Tickets
            </button>
        </div>

        <a href="#" onclick="payNow()" class="flex items-center justify-center p-6 mb-4 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            {{ __('PAY NOW') }}
        </a>


        @if (isset($newTickets))
            <div class="tickets-list mt-4 py-2">
                @foreach (json_decode($newTickets) as $key => $ticket)
                    <div class="ticket bg-[{{ $ticket->color }}]">
                        <div class="flex items-center justify-between mb-1">
                            <div class="sno">#{{ $key + 1 }}</div>
                            <div class="ticket-id">No : ..{{ strtoupper(substr($ticket->id, 28, 8)) }}</div>
                            <div class="hidden border-red-300 border-yellow-300 border-pink-300 border-purple-300 border-cyan-300 border-orange-300 border-green-300 border-black border-blue-300"></div>
                            <label class="inline-flex items-center cursor-pointer text-sm">
                                <input type="checkbox" name="checkbox" class="text-primary-500 w-4.5 h-4.5 mr-2 mb-1 rtl:ml-2 disabled:opacity-50 focus:ring-primary-500 border-2 border-primary-300 focus:ring-opacity-25 dark:bg-dark-700 bw-checkbox rounded-md"
                                value=""
                                data-ticket="{{ json_encode($ticket) }}">
                                Select Ticket
                            </label>
                        </div>

                        @foreach ($ticket->object as $row)
                            <div class="row flex justify-center">
                                @foreach ($row as $cell)
                                    <div class="cell number unchecked">
                                        @if ($cell->value > 0)
                                            <span class="text-lg md:text-xl">{{ $cell->value }}</span>
                                        @else
                                            <span class="text-lg md:text-xl"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif


    </x-bladewind.centered-content>

    @pushOnce('scripts')
        <script>
             document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('div.tickets-list.mt-4 div.flex.items-center.justify-between > label').forEach((label) => {
                    label.classList.remove('mb-3', 'mr-6');
                });
            });

            function payNow() {
                var selectedTickets = [];

                // Iterate over each checked checkbox and extract the ticket data
                document.querySelectorAll('.ticket input[type="checkbox"]:checked').forEach(function(checkbox) {
                    var ticketData = checkbox.dataset.ticket;
                    if (ticketData) {
                        selectedTickets.push(ticketData);
                    }
                });

                // Construct the URL to the payment page
                var noOfTickets = selectedTickets.length;
                var amount = noOfTickets * ticketPrice;
                var url = "{{ url('payment') }}?tickets=" +'['+ encodeURIComponent(selectedTickets.join(','))+']' + "&amount=" + amount + "&noOfTickets=" + noOfTickets;
                console.log(url);
                window.location.href = url;
            }


        </script>
    @endPushOnce

    <style>
         .tickets-list {
            max-width: 680px; /* Change maximum width for responsiveness */
            margin: 0 auto; /* Center align the tickets */
        }

        .ticket {
            border-bottom: 1px dashed;
            padding: 18px 8px;
        }
        .ticket:last-child {
            border-bottom: 0;
        }

        .row {
            display: flex;
            justify-content: center; /* Center content horizontally */
        }
        .row:last-child {
            border-bottom: 1px solid #888;
        }

        .cell {
            width: calc(100% / 9); /* Set width for each cell dynamically based on the container's width */
            padding-top: calc(92% / 9); /* Set height for each cell dynamically based on the width */
            position: relative; /* Position each cell relative to its container */
            border-left: 1px solid #888;
            border-top: 1px solid #888;
            /* margin: 0.25rem; Add margin for spacing between cells */
            max-width: 75px; /* Set maximum width for cell */
            max-height: 75px; /* Set maximum height for cell */
            flex: 1; /* Allow cells to grow and shrink to fit the container */
        }

        .cell:last-child {
            border-right: 1px solid #888;
        }

        .cell > span {
            position: absolute; /* Position content absolutely within each cell */
            top: 50%; /* Align content vertically to the center */
            left: 50%; /* Align content horizontally to the center */
            transform: translate(-50%, -50%); /* Center content both horizontally and vertically */
            display: block; /* Ensure content spans the entire cell */
            text-align: center; /* Center text horizontally */
            width: 100%; /* Make sure content spans the entire cell width */
            max-width: 100%; /* Set maximum width for content */
            max-height: 100%; /* Set maximum height for content */
        }

    </style>

</div>
