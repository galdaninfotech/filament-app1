<div>
    @once
        <script>
            const userId = {{ auth()->id() }};
        </script>
    @endonce

    <button type="button" wire:click="$refresh" class="px-4">
        Refresh
    </button>

</br>




    <div class="grid place-items-center">
        <div class="flex items-start mt-1">
            @php $disabled = $activeGame->status == 'Starting Shortly' ? false : true; @endphp

            <a href="{{ url('player-payment') }}"
                class="inline-flex items-center py-1.5 px-2 m-1 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-md @if($disabled)bg-gray-300 cursor-not-allowed @endif"
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

            <button class="inline-flex items-center py-1.5 px-2 m-1 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-md">
                <svg id="autotick-enabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoTick == 1) block @else hidden @endif h-5 w-5 md:w-6 md:h-6 text-green-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg id="autotick-disabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoTick == 0) block @else hidden @endif h-5 w-5 md:w-6 md:h-6 text-red-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>

                <span onclick="handleAutoTick(event);">{{ __('Auto Tick') }}</span>
            </button>

            <button class="inline-flex items-center py-1.5 px-2 m-1 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-md">
                <svg id="autotick-enabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoTick == 1) block @else hidden @endif h-5 w-5 md:w-6 md:h-6 text-green-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg id="autotick-disabled" xmlns="http://www.w3.org/2000/svg" class="@if ($autoTick == 0) block @else hidden @endif h-5 w-5 md:w-6 md:h-6 text-red-400 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span onclick="handleAutoTick(event);" class="ml-1">{{ __('Auto Claim') }}</span>
            </button>
            </br>
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
                    <div class="ticket px-2 pb-5 bg-[{{ $ticket->color }}]">
                        <div class="ticket-header">
                            <div class="grid grid-cols-2 gap-1">
                                <div class="text-[11px] divide-y divide-gray-400 divide-dotted">
                                    <x-ticket-details :claims="$ticket->claims"></x-ticket-details>
                                </div>
                                <div class="text-right">

                                    <button
                                        x-data
                                        @click="$dispatch('open-modal', { name: 'claim' })"
                                        {{-- wire:click="updateTicketSelected({{ $ticket->id }})" --}}
                                        class="bg-teal-500 rounded-sm shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                        data-ticket-id="{{ $ticket->id }}"
                                        onclick="getTicketId(this)"
                                    >
                                        {{ __('Claim Prize') }}
                                    </button>
                                    {{-- @include('includes.claim-prize') --}}

                                </div>
                            </div>
                            {{-- <div class="ticket-id">{{ $ticket->id }}-{{ strtoupper(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8)) }}</div> --}}
                            <div class="ticket-id">..{{ strtoupper(substr($ticket->id, 28, 8)) }}</div>
                        </div>
                        @for ($j = 0; $j < 3; $j++)
                            <div class="row flex ">
                                @for ($k = 0; $k < 9; $k++)
                                    <div
                                        x-data="{ checked: @json(isset($ticket->object[$j][$k]['checked']) && $ticket->object[$j][$k]['checked'] == 1), loading: false }"
                                        :class="checked ? 'column yellow w-9 h-9 flex justify-center items-center' : 'column w-9 h-9 flex justify-center items-center'"
                                        class="cell"
                                        id="ticket-cell"
                                        onclick='toggleCheckedClass(this, {{ $ticket->object[$j][$k]["value"] }}, @if ($ticket->object[$j][$k]["checked"]) true @else false @endif, {{"'$ticket->id'" }}, {{ $j }}, {{ $k }})'
                                    >
                                        @if($ticket->object[$j][$k]['value'] > 0)
                                            <div class="checkable_div">
                                                <input type="checkbox" class="hidden" name="{{ $ticket->object[$j][$k]['id'] }}">
                                                <span class="flex items-center justify-center w-full h-full number text-md md:text-2xl p-2 cursor-pointer">{{ $ticket->object[$j][$k]['value'] }}</span>
                                            </div>
                                        @else
                                            <div class="uncheckable_div"><input type="checkbox" class="hidden" disable></div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        @endfor
                    </div>
                    <div class="my4 w-full h-[1px] border-1 border-dotted"></div>
                @endforeach
            @endif
        </div>

    </div>

    @include('includes.claim-prize')

    <script>
        function toggleCheckedClass(element, value, checked, ticketId, j, k) {
            console.log('-------'+ticketId+'-------');
            console.log('CONSOLE : Value-' + value + ', Checked-'+ checked + ', TicketId-' + ticketId + ', j-' + j + ', k-' + k);
            // Toggle 'checked' and update only when value is non zero
            // console.log(value);
            if(value > 0) {
                element.classList.toggle('yellow');
                // if(checked) {
                //     element.style.background = "transparent";
                // } else {
                //     element.style.background = "yellow";
                // }
                axios.post(`/updateChecked`, { ticket_id: ticketId, row: j, column: k })
                    .then(response => response.status)
                    .catch(err => console.warn("ERROR : " + err));
            }
        }

        function handleAutoTick(event) {
            event.preventDefault();
            const el1 = document.getElementById('autotick-enabled');
            const el2 = document.getElementById('autotick-disabled');
            el1.classList.toggle('hidden');
            el2.classList.toggle('hidden');

            axios.post(`/toggleAutoTick`, { user_id: userId })
                .then(response => response.status)
                .catch(err => console.warn("ERROR : " + err));
        }

        function handleAutoClaim(event) {
            event.preventDefault();
            const el1 = document.getElementById('autoclaim-enabled');
            const el2 = document.getElementById('autoclaim-disabled');
            el1.classList.toggle('hidden');
            el2.classList.toggle('hidden');

            axios.post(`/toggleAutoClaim`, { user_id: userId })
                .then(response => response.status)
                .catch(err => console.warn("ERROR : " + err));
        }

    </script>

    <style>
        .yellow {
            background: yellow;
        }
        /* .oc-5be196 {
            background: #5be196;
        }
        .oc-red {
            background: red;
        } */
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
            display: table; border-collapse: collapse;
            /* flex-wrap: wrap; */
            /* justify-content: center; */
            width: 100%;
            /* background: aliceblue; */
            /* position: relative; */
        }

        .ticket {
            width: 100%;
            max-width: 680px;
            /* margin-bottom: 20px; */
            padding-top: 12px;
            border-bottom: 1px dashed;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-details {
            width: calc(50% - 10px); /* Adjust width based on your design */
            text-align: left;
            font-size: 0.8em; /* Adjust the font size as needed */
        }

        .ticket button {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 10px;
            font-weight: normal;
            padding: 0px 6px;
            box-shadow: 1px 2px 2px;
            /* background: seagreen; */
            color: white;
        }

        .ticket:last-child {
            border: 0;
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
            width: calc(100% / 9);
            padding-top: calc(80% / 9);
            position: relative;
            border-left: 1px solid #555;
            border-top: 1px solid #555;
            max-width: 75px;
            max-height: 75px;
        }
        .cell:last-child {
            border-right: 1px solid #555;
        }
        .row:last-child {
            border-bottom: 1px solid #555;
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
