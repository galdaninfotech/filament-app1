<div>
    <div class="px-6">
        <button type="button" x-on:click="$wire.$refresh()">
            Refresh 2
        </button>
        @if (isset($tickets[0]))
            @foreach ($tickets as $ticket)
                <div class="mb-10">
                    <div class="ticket-header w-full flex">
                        <div class="grid grid-cols-2 gap-1">
                            <div class="text-left text-[11px] divide-y divide-gray-400 divide-dotted">
                                <x-ticket-details :claims="$ticket->claims"></x-ticket-details>
                            </div>
                            <div class="text-right">
                                {{-- @include('includes.claim-prize') --}}
                            </div>
                        </div>
                    </div>
                    @for ($j = 0; $j < 3; $j++)
                        <div class="row flex gap-x-2 gap-y-2 mt-2">
                            @for ($k = 0; $k < 9; $k++)
                                <div
                                    x-data="{
                                        checked: @json(isset($ticket->object[$j][$k]['checked']) && $ticket->object[$j][$k]['checked'] == 1),
                                        loading: false
                                    }"
                                    :class="checked ? 'column checked w-9 h-9 flex justify-center items-center' : 'column unchecked w-9 h-9 flex justify-center items-center'"
                                >
                                {{-- {{ dd($ticket->object[$j][$k]) }} --}}
                                    @isset($ticket->object[$j][$k]['checked'], $ticket->object[$j][$k]['value'])
                                        <div class="checkable_div">
                                            <input type="checkbox" class="hidden" name="{{ $ticket->object[$j][$k]['id'] }}">
                                            <span
                                                class="block w-full h-full p-2 cursor-pointer"
                                                wire:click="updateChecked({{ $ticket->id }}, {{ $j }}, {{ $k }})"
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


    <script>
        // document.addEventListener('livewire:load', function () {
        //     Livewire.on('refreshComponent', function () {
        //         window.location.reload();
        //     });
        // });

        Livewire.on('refreshComponent', function () {
            window.location.reload();
        });
    </script>



</div>
