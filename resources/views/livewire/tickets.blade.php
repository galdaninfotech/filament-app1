<div>
    {{-- The whole world belongs to you. --}}
    
    {{-- {{ dd($tickets) }} --}}
    <div class="grid place-items-center">
        <div class="flext items-start">
            <button wire:click="generateTicket({{ $noOfTickets = 1 }})"
                class="p-6 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                {{ __('Buy Tickets') }}
            </button>

            <button wire:click="setAutoMode"
                class="p-6 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-4 py-2 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                {{ __('Auto Mode') }}
            </button>
        </div>

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
                        <div class="grid grid-cols-2 gap-1">
                            <div class="text-left text-[11px] divide-y divide-gray-400 divide-dotted">
                                <x-ticket-details :claims="$ticket->claims"></x-ticket-details>
                            </div>
                            <div class="text-right">
                                <button 
                                    x-data
                                    @click="$dispatch('open-modal', { name: 'claim' })"
                                    wire:click="updateTicketSelected({{ $ticket->id }})"
                                    class="bg-gray-800 text-white active:bg-pink-600 font-bold text-xs px-2 py-1 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                >
                                    {{ __('Claim Prize') }}
                                </button>
                            </div>
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
                                                <span wire:loading.remove>{{ $ticket->object[$j][$k]['value'] }} </span>
                                                <span wire:loading>
                                                    <svg aria-hidden="true" class="inline w-2 h-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                    </svg>
                                                </span>
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
                            @foreach ($gamePrizes as $prize)
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
