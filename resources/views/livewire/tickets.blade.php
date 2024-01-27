<div>
    {{-- The whole world belongs to you. --}}
    <button wire:click="generateTicket({{ $noOfTickets = 1 }})" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
        {{ __('Buy Tickets') }}
    </button>
    <br>
    {{-- {{ dd($tickets) }} --}}
    <div class="grid grid-col-1">
        @if(isset($tickets[0]))
            @foreach($tickets as $ticket)
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
                            <button wire:click="claimPrize({{ $ticket->id }})" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                {{ __('Claim Prize') }}
                            </button>
                        </div>
                    </div>
                    @for ($j=0; $j <3 ; $j++)
                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                            @for ($k=0; $k <9 ; $k++)
                                <div class="column w-10 h-10 bg-gray-300 flex justify-center items-center">
                                    @isset( $ticket->object[$j][$k]['checked'], $ticket->object[$j][$k]['value'] )
                                        @if ( $ticket->object[$j][$k]['checked'] == 1 )
                                            @php
                                                $checked = 'checked';
                                                $class = 'number_ticked before-old';
                                            @endphp
                                        @else
                                            @php
                                                $checked='';
                                                $class = '';    
                                            @endphp
                                        @endif
                                        <div>
                                            @if ( $ticket->object[$j][$k]['value'] != '' )
                                                <div class="checkable_div {{ $class }}">
                                                    <input wire:click="updateChecked({{ $ticket->id }}, {{ $ticket->object[$j][$k]['id'] }})" type="checkbox" class="xhidden" name="{{ $ticket->object[$j][$k]['id'] }}" {{ $checked }}>

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
    <div id="claim-modal">
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Products') }}
            </h2>
        </x-slot>
     
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 overflow-hidden overflow-x-auto bg-white border-b border-gray-200">
     
                        <div class="min-w-full align-middle">
                            <table class="min-w-full border divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Name</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Description</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                </tr>
                                </thead>
     
                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                    @forelse($tickets as $ticket)
                                        <tr class="bg-white">
                                            <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                {{ $ticket->status }}
                                            </td>
                                            <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                {{ $ticket->comment }}
                                            </td>
                                            <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                {{-- Edit Button --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white">
                                            <td colspan="3" class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                                No products found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
     
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end Modal Dialog --}}
</div>
