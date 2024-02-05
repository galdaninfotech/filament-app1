<div>
    {{-- The whole world belongs to you. --}}
    
    {{-- {{ dd($tickets) }} --}}
    <div class="grid place-items-center p-6">
        <button wire:click="generateTicket({{ $noOfTickets = 1 }})"
            class="p-6 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
            {{ __('Buy Tickets') }}
        </button>
        @if(Session::has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">{{ Session::get('message') }}</span>
            </div>
        @endif
        <br>
        @if (isset($tickets[0]))
            @foreach ($tickets as $ticket)
                <div class="mb-10">
                    <div class="ticket-header w-full flex">
                        <div style="margin-right: 180px;" class="left mr-10">Ticket No: {{ $ticket->id }}</div>
                        <div style="margin-right: 180px;" class="left mr-10">Claims : {{ $ticket->claims }}</div>
                        <div class="right">
                            <button 
                                x-data
                                @click="$dispatch('open-modal', { name: 'claim' })"
                                wire:click="updateTicketSelected({{ $ticket->id }})"
                                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                            >
                                {{ __('Claim Prize') }}
                            </button>
                        </div>
                    </div>
                    @for ($j = 0; $j < 3; $j++)
                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                            @for ($k = 0; $k < 9; $k++)
                            <div x-data="{ checked: @json(isset($ticket->object[$j][$k]['checked']) && $ticket->object[$j][$k]['checked'] == 1) }"
                                    x-on:click="checked = ! checked"
                                    :class="checked ? 'column checked w-9 h-9 flex justify-center items-center' : 'column unchecked w-9 h-9 flex justify-center items-center'">
                                    @isset($ticket->object[$j][$k]['checked'], $ticket->object[$j][$k]['value'])
                                        @if ($ticket->object[$j][$k]['checked'] == 1)
                                            @php
                                                $checked = 'checked';
                                                $class = 'number_ticked before-old checkedzzz';
                                            @endphp
                                            @script
                                                checked = true;
                                            @endscript
                                        @else
                                            @php
                                                $checked = '';
                                                $class = '';
                                            @endphp
                                            @script
                                                checked = false;
                                            @endscript
                                        @endif
                                        <div>
                                            @if ($ticket->object[$j][$k]['value'] != '')
                                                <div class="checkable_div ">
                                                    <input
                                                        type="checkbox" class="hidden"
                                                        name="{{ $ticket->object[$j][$k]['id'] }}" {{ $checked }}>
                                            @else
                                                    <div>
                                            @endif
                                            <span class="block w-full h-full p-2 cursor-pointer" wire:click="updateChecked({{ $ticket->id }}, {{ $ticket->object[$j][$k]['id'] }})"> 
                                                {{ $ticket->object[$j][$k]['value'] }} 
                                            </span>
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

    <x-modal name="claim" title="Claims" ticketId="">
        <x-slot:body>
            <section class="px-8 bg-white dark:bg-gray-900">
                <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="claimPrize()" x-on:received-claim.window="isShowing = false">
                        @csrf
                        <select wire:model="prizeSelected" class="block mt-1 w-full rounded-md" required>
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
                        <div class="hidden" wire:loading> Claiming prize... </div>
                    </form>
                </div>
            </section>
        </x-slot>
    </x-modal>

    {{-- end Modal Dialog --}}
</div>
