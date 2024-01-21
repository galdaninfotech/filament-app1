<div>
    {{-- The whole world belongs to you. --}}
    <button wire:click="generateTambolaTicket" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
        {{ __('Buy Tickets') }}
    </button>
    <br>

    <div class="grid grid-col-1">
        @if(isset($tickets[0]))
            @foreach($tickets as $ticket)
                <div class="mb-6">
                    @for ($j=0; $j <3 ; $j++)
                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                            @for ($k=0; $k <9 ; $k++)
                                <div class="column w-10 h-10 bg-gray-300 flex justify-center items-center">
                                    @isset($ticket->object)
                                        {{ $ticket->object[$j][$k]['value'] }}
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
