<div>
    {{-- The whole world belongs to you. --}}
    <button wire:click="generateTicket({{ $noOfTickets = 1 }})"
        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
        {{ __('Buy Tickets') }}
    </button>

    {{-- Modal --}}
   
    <button x-data @click="$dispatch('open-modal',{name:'claim'})" class="text-white px-3 py-1 bg-blue-500 rounded text-xs"> 
        Claim
    </button>
    {{-- end Modal --}}

    <br>
    {{-- {{ dd($tickets) }} --}}
    <div class="grid grid-col-1">
        @if (isset($tickets[0]))
            @foreach ($tickets as $ticket)
                <div class="mb-10"><br>
                    <div class="ticket-header w-full flex">
                        <div style="margin-right: 90px;" class="left mr-10">Ticket No: {{ $ticket->id }}</div>
                        <div class="right">
                            <select wire:model="prizeSelected">
                                <option value=""> Select Prize </option>
                                @foreach ($game_prizes as $prize)
                                    <option value="{{ $prize->id }}" @selected(old($prize->name) == $prize->name)>
                                        {{ $prize->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button 
                                x-data @click="$dispatch('open-modal',{name:'claim'})"
                                wire:click="updateTicketSelected({{ $ticket->id }})"
                                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                {{ __('Claim Prize') }}
                            </button>

                        </div>
                    </div>
                    @for ($j = 0; $j < 3; $j++)
                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                            @for ($k = 0; $k < 9; $k++)
                                <div class="column w-10 h-10 bg-gray-300 flex justify-center items-center">
                                    @isset($ticket->object[$j][$k]['checked'], $ticket->object[$j][$k]['value'])
                                        @if ($ticket->object[$j][$k]['checked'] == 1)
                                            @php
                                                $checked = 'checked';
                                                $class = 'number_ticked before-old';
                                            @endphp
                                        @else
                                            @php
                                                $checked = '';
                                                $class = '';
                                            @endphp
                                        @endif
                                        <div>
                                            @if ($ticket->object[$j][$k]['value'] != '')
                                                <div class="checkable_div {{ $class }}">
                                                    <input
                                                        wire:click="updateChecked({{ $ticket->id }}, {{ $ticket->object[$j][$k]['id'] }})"
                                                        type="checkbox" class="xhidden"
                                                        name="{{ $ticket->object[$j][$k]['id'] }}" {{ $checked }}>
                                                @else
                                                    <div>
                                            @endif
                                            {{ $ticket->object[$j][$k]['value'] }}
                                        </div>
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


{{-- Modal Dialog --}}

    <x-modal name="claim" title="Claims" ticketId="{{ $ticket->id }}">
        <x-slot:body>
            <section class="px-8 bg-white dark:bg-gray-900">
                <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="claimPrize(ticketId)">
                        @csrf
                        <select wire:model="prizeSelected" class="block mt-1 w-full rounded-md">
                            <option value=""> Select Prize </option>
                            @foreach ($game_prizes as $prize)
                                <option value="{{ $prize->id }}" @selected(old($prize->name) == $prize->name)>
                                    {{ $prize->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ms-4"> {{ __('Claim Prize') }} </x-button>
                        </div>
                    </form>
                </div>
            </section>
        </x-slot>
    </x-modal>

{{-- end Modal Dialog --}}
</div>
