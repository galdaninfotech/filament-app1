<div>
    @php use Carbon\Carbon; @endphp
    <!-- Claims Table -->
    <div class="container mx-auto divide-y divide-gray-400 divide-dotted">
        <table class="table-auto text-sm w-full">
            <thead>
                <tr class="bg-gray-400 text-white p-4">
                    <th class="py-2 text-gray-900">#</th>
                    <th class="py-2">Prize</th>
                    <th class="py-2">Ticket</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Remarks</th>
                    <th class="py-2">Time</th>
                    <th class="py-2">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y text-gray-700 divide-dotted divide-purple-500">
                @foreach($claimsz as $claim)
                    <tr class="">
                        <td class="py-2 text-gray-900"> {{ $claim->claim_id }} </td>
                        <td class="py-2"> {{ $claim->prize_name }} </td>
                        <td class="py-2"> ..{{ strtoupper(substr($claim->ticket_id, 28, 8)) }} </td>
                        <td class="py-2"> {{ $claim->status }} </td>
                        <td class="py-2"> {{ $claim->remarks }} </td>
                        <td class="py-2"> {{ Carbon::createFromTimeStamp(strtotime($claim->created_at))->diffForHumans() }} </td>
                        <td class="py-2">
                            <button
                                x-data
                                wire:click="updateSelectedClaimWithDetails({{ $claim->claim_id }})"
                                @click="$dispatch('open-modal', { name: 'claim-modal' })"
                                class="button-sm"
                            >
                                {{ __('View') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- end Claims Table -->

    {{-- Modal Dialog --}}
    <x-modal name="claim-modal" title="Claims" claim_id="55555">
        <x-slot:body>
            <section class="px-8 bg-white dark:bg-gray-900">
                <div class="py-8 lg:py-16 mx-auto max-w-screen-md">
                    <x-validation-errors class="mb-4" />
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        @csrf
                        @if (isset($selectedClaimWithDetails))
                            <div class="grid grid-cols-1 grid-gap-1">
                                <!-- Claim Details -->
                                <div class="claim-details flex items_center justify-content space-x-6 text-xs">
                                    <div class="left p-2">
                                        <span class="block"> Claim No: {{$selectedClaimWithDetails[0]->claim_id}} </span>
                                        <span class="block"> Ticket No: ..{{strtoupper(substr($selectedClaimWithDetails[0]->ticket_id, 28, 8))}} </span>
                                        <span class="block"> Game Prize No: {{$selectedClaimWithDetails[0]->prize_name}} </span>
                                        <span class="block"> Claim Status: {{$selectedClaimWithDetails[0]->status}} </span>
                                        <span class="block"> Claimed At: {{ Carbon::createFromTimeStamp(strtotime($claim->created_at))->diffForHumans() }} </span>
                                    </div>
                                    <div class="right p-2">
                                        <span class="block"> Player: {{$selectedClaimWithDetails[0]->user_name}} </span>
                                        <span class="block"> Game: {{$selectedClaimWithDetails[0]->game_id}} </span>
                                        <span class="block"> Prize Amount: {{$selectedClaimWithDetails[0]->prize_amount}} </span>
                                        <span class="block"> Remarks: {{$selectedClaimWithDetails[0]->remarks}} </span>
                                    </div>
                                </div>

                                <!-- Action Bttons -->
                                <div class="flex items-center justify-between space-x-6">
                                    <button x-data
                                        wire:click="updateClaimWinner({{ $selectedClaimWithDetails[0]->claim_id }})"
                                        class="button p-2"
                                    >
                                        {{ __('Winner') }}
                                    </button>
                                    <button x-data
                                        wire:click="updateClaimBoggy({{ $selectedClaimWithDetails[0]->claim_id }})"
                                        class="button p-2"
                                    >
                                        {{ __('Boggy') }}
                                    </button>
                                </div>

                                <div class="ticket">
                                    @php $ticket = json_decode($selectedClaimWithDetails[0]->object, true); @endphp
                                    @foreach ($ticket as $row)
                                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                                            @foreach ($row as $cell)
                                                <div class="column w-9 h-9 flex justify-center items-center">
                                                    @if (is_array($cell))
                                                        @if($cell['checked'] == 1)
                                                            @php
                                                                $class = 'checked';
                                                            @endphp
                                                        @else
                                                            @php
                                                                $class = 'unchecked';
                                                            @endphp
                                                        @endif
                                                        <span class="w-full h-full p-2 {{$class}}"> {{ $cell['value'] }} </span>
                                                    @elseif (is_int($cell))
                                                        <span class="cell w-full h-full p-2"> {{ $cell }} </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endif
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ms-4"> {{ __('Update Claim') }} </x-button>
                        </div>
                        <div class="hidden" wire:loading> Updating claim... </div>
                    </div>
                </div>
            </section>
        </x-slot>
    </x-modal>
    {{-- end Modal Dialog --}}

    <style>
        .checked {
            background-color: #00FF00; /* Green background for drawn numbers */
        }
        .unchecked, .cell {
            background-color: #d4d2d2; /* Green background for drawn numbers */
        }
    </style>


</div>
