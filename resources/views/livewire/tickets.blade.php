<div>
    @once
        <script>
            const userId = {{ auth()->id() }};
        </script>
    @endonce

    <button type="button" wire:click="$refresh">
        Refresh
    </button>

</br>




    <div class="grid place-items-center">
        <div class="flex items-start mt-1">
            @php $disabled = $activeGame->status == 'Starting Shortly' ? false : true; @endphp

            <a href="{{ url('player-payment') }}"
                class="flex items-center justify-center p-4 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 @if($disabled)bg-gray-300 cursor-not-allowed @endif"
                @if($disabled) onclick="handleBuyTickets(event);" @endif
                style="@if($disabled) background-color: #e5e5e5; @endif"
                >
                {{ __('Buy Tickets') }}
            </a>

            <script>
                function handleBuyTickets(event) {
                    event.preventDefault()
                    alert('Game Already Started!')
                    Swal.fire({
                        position: "top-end",
                        title: "You Won!" + data.message[1].prize_name,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            </script>


            <button class="flext items-start relative p-4 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                <svg id="automode-enabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoMode == 1) block @else hidden @endif h-6 w-6 text-green-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg id="automode-disabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoMode == 0) block @else hidden @endif h-6 w-6 text-red-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span onclick="handleAutoMode(event);" class="ml-12">{{ __('Auto') }}</span>
            </button>
        </div>

        @if(Session::has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">{{ Session::get('message') }}</span>
            </div>
        @endif

        <br>
        <div class="tickets-list">
            @if (isset($tickets[0]))
                @foreach ($tickets as $ticket)
                    <div class="ticket mb-10">
                        <div class="ticket-header">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="text-[11px] divide-y divide-gray-400 divide-dotted">
                                    <x-ticket-details :claims="$ticket->claims"></x-ticket-details>
                                </div>
                                <div class="text-right">
                                    @include('includes.claim-prize')
                                </div>
                            </div>
                            <div class="ticket-id">{{ $ticket->id }}-{{ strtoupper(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8)) }}</div>
                        </div>
                        @for ($j = 0; $j < 3; $j++)
                            <div class="row flex gap-1 sm:gap-2 mt-2">
                                @for ($k = 0; $k < 9; $k++)
                                    <div
                                        x-data="{
                                            checked: @json(isset($ticket->object[$j][$k]['checked']) && $ticket->object[$j][$k]['checked'] == 1),
                                            loading: false
                                        }"
                                        :class="checked ? 'column checked w-9 h-9 flex justify-center items-center' : 'column bg-[#d4d2d2] w-9 h-9 flex justify-center items-center'"
                                        class="cell"
                                        id="ticket-cell"
                                        onclick="toggleCheckedClass(this, {{ $ticket->id }}, {{ $j }}, {{ $k }})"
                                    >
                                    {{-- {{ dd($ticket->object[$j][$k]) }} --}}
                                        @isset($ticket->object[$j][$k]['checked'], $ticket->object[$j][$k]['value'])
                                            <div class="checkable_div">
                                                <input type="checkbox" class="hidden" name="{{ $ticket->object[$j][$k]['id'] }}">
                                                <span
                                                    class="flex items-center justify-center w-full h-full text-lg md:text-2xl p-2 cursor-pointer"
                                                    {{-- wire:click="updateChecked({{ $ticket->id }}, {{ $j }}, {{ $k }})" --}}
                                                >
                                                    <span wire:loading.remove wire:target="updateChecked({{ $ticket->id }}, {{ $j }}, {{ $k }})">{{ $ticket->object[$j][$k]['value'] }}</span>
                                                    <span wire:loading wire:target="updateChecked({{ $ticket->id }}, {{ $j }}, {{ $k }})">
                                                        <!-- Show loading spinner only when loading is true -->
                                                        <svg aria-hidden="true" class="inline w-4 h-4 text-gray-400 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                        @endisset
                                    </div>
                                @endfor
                            </div>
                        @endfor

                    </div>
                @endforeach
            @endif
        </div>


    </div>

    <script>
        function toggleCheckedClass(element, ticketId, j, k) {
            console.log("CONSOLE : " + ticketId, j, k);
            // Toggle 'checked' class
            element.classList.toggle('checked');

            axios.post(`/updateChecked`, { ticket_id: ticketId, row: j, column: k })
                .then(response => response.status)
                .catch(err => console.warn("ERROR : " + err));
        }

        function handleAutoMode(event) {
            event.preventDefault();
            const el1 = document.getElementById('automode-enabled');
            const el2 = document.getElementById('automode-disabled');
            el1.classList.toggle('hidden');
            el2.classList.toggle('hidden');
            // alert('Auto Mode is not available!');
            axios.post(`/toggleAutoMode`, { user_id: userId })
                .then(response => response.status)
                .catch(err => console.warn("ERROR : " + err));
        }

    </script>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin-animation {
            animation: spin 1s linear infinite;
        }


       /* Tickets */
        .tickets-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        .ticket {
            width: 100%;
            max-width: 680px;
            margin-bottom: 20px;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 8px;
        }

        .ticket-details {
            width: calc(50% - 10px); /* Adjust width based on your design */
            text-align: left;
            font-size: 0.8em; /* Adjust the font size as needed */
        }

        .claim-prize {
            width: calc(50% - 10px); /* Adjust width based on your design */
            text-align: right;
            font-size: 0.8em; /* Adjust the font size as needed */
        }

        .row {
            display: flex;
            justify-content: center;
        }

        .cell {
            flex: 0 0 auto;
            width: calc(80% / 9);
            padding-top: calc(80% / 9);
            position: relative;
            border: 1px solid #ccc;
            margin: 0 2px; /* Adjust margin based on your design */
            max-width: 75px;
            max-height: 75px;
        }


        .cell .checkable_div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .checkable_div span {
            cursor: pointer;
        }

        .checkable_div span span {
            display: inline-block;
            width: 100%;
            text-align: center;
            font-size: 0.8em; /* Adjust the font size as needed */
        }






    </style>


</div>
