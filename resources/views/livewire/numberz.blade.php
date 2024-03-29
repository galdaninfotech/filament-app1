<div>
<!--
Event Broadcasts:
Board 87 broadcast(new NumbersEvent([$newNumber, $this->count, $this->drawnNumbers, $this->currentGameStatus]))->toOthers();
Board 119  $this->dispatch('numbers-event');

Ticket 121 $this->dispatch('claim-event');
Ticket 144 event(new ClaimEvent(['Paused']));
Numberz 105 #[On('claim-event')]

Claim 81 event(new WinnerEvent($this->selectedClaimWithDetails[0]->user_id));

Page Refresh:
Ticket 80 redirect(url('/dashboard'))->with('message','Operation Successful !');

return $this->redirect(request()->header('Referer'), navigate: true); // livewire
$this->js('window.location.reload()'); //livewire
return redirect(request()->header('Referer')); //livewire
return redirect()->to('/route');
return back();
return redirect()->back()
redirect(Request::url())


From Javascript:
Livewire.navigate('/new/url');



====================================

@push('css')
    <link rel="stylesheet" href="{{ asset('themes/default/assets/css/shop.css') }}">
@endpush


https://codepen.io/ossvahid/pen/JjaMrpR
https://codepen.io/jkantner/pen/XWzePgp
https://codepen.io/gatoledo1/pen/mdXLReX

https://codepen.io/MillerTime/pen/NevqWJ


-->
<div class="px-4 md:px-6">
    <livewire:video />
</div>

<button type="button" wire:click="$refresh">
    Refresh
</button>


    <div class="px-6 py-2 ">
        {{-- Session message --}}
        @if(Session::has('status'))
            <x-bladewind::alert type="success"> {{ Session::get('status') }} </x-bladewind.alert>
        @endif

        <h5 id="active-game"> {{ $activeGame->name }}</h5>

        <h5 id="game-status">Game Status : {{ $currentGameStatus }}</h5>

    </div>

    <x-recent-numbers :numbers="$drawnNumbers[0]"></x-recent-numbers>

    {{-- Tabs --}}
    <div class="w-full max-w-6xl mx-auto px-4 md:px-6 py-2">
        <!-- Tabs component -->
        <div x-data="{ activeTab: 1 }">
            <!-- Buttons -->
            <div class="flex justify-center">
                <div
                    role="tablist"
                    class="inline-flex justify-center bg-slate-200 p-1 mb-4 min-[480px]:my-6 rounded-2xl"
                    @keydown.right.prevent.stop="$focus.wrap().next()"
                    @keydown.left.prevent.stop="$focus.wrap().prev()"
                    @keydown.home.prevent.stop="$focus.first()"
                    @keydown.end.prevent.stop="$focus.last()"
                >
                    <!-- Button #1 -->
                    <button
                                id="tab-1"
                                class="flex items-center justify-center text-xs font-medium h-8 px-2 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                                :class="activeTab === 1 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                                :tabindex="activeTab === 1 ? 0 : -1"
                                :aria-selected="activeTab === 1"
                                aria-controls="tabpanel-1"
                                @click="activeTab = 1"
                                @focus="activeTab = 1"
                            >
                        <span>TICKETS</span>
                        <span class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{ $tickets->count() }}</span>
                    </button>
                    <!-- Button #2 -->
                    <button
                                id="tab-2"
                                class="flex items-center justify-center text-xs font-medium h-8 px-2 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                                :class="activeTab === 2 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                                :tabindex="activeTab === 2 ? 0 : -1"
                                :aria-selected="activeTab === 2"
                                aria-controls="tabpanel-2"
                                @click="activeTab = 2"
                                @focus="activeTab = 2"
                            >
                            <span>NUMBERS</span>
                        <span id="numbers-count" class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{ $count }}</span>
                    </button>
                    <!-- Button #3 -->
                    <button
                            id="tab-3"
                            class="flex items-center justify-center text-xs font-medium h-8 px-2 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                            :class="activeTab === 3 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                            :tabindex="activeTab === 3 ? 0 : -1"
                            :aria-selected="activeTab === 3"
                            aria-controls="tabpanel-3"
                            @click="activeTab = 3"
                            @focus="activeTab = 3"
                        >
                        <span>PRIZES</span>
                        <span class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{$allPrizes->count()}}</span>
                        <span>/WINNERS</span>
                        <span id="winners-count" class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{ $allWinners->count() }}</span>
                    </button>
                    <button
                            id="tab-4"
                            class="flex items-center justify-center text-xs font-medium h-8 px-2 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                            :class="activeTab === 4 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                            :tabindex="activeTab === 4 ? 0 : -1"
                            :aria-selected="activeTab === 4"
                            aria-controls="tabpanel-3"
                            @click="activeTab = 4"
                            @focus="activeTab = 4"
                        >
                        <span>CLAIMS</span>
                        <span id="active-claims" class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{ $activeClaims->count() }}</span>
                    </button>
                </div>
            </div>

            <!-- Tab panels -->
            <div class="max-w-[680px] mx-auto">
                <div class="relative flex flex-col">

                    <!-- Panel #1 -->
                    <article
                        id="tabpanel-1"
                        class="w-full bg-white rounded-2xl shadow-xl items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
                        role="tabpanel"
                        tabindex="0"
                        aria-labelledby="tab-1"
                        x-show="activeTab === 1"
                        x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                        x-transition:enter-start="opacity-0 -translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-12"
                    >
                        <div class="flex flex-col justify-center">
                            <div class="text-slate-500 text-sm line-clamp-3 mb-2">
                                <livewire:tickets />
                            </div>
                        </div>
                    </article>

                    <!-- Panel #2 -->
                    <article
                        id="tabpanel-2"
                        class="w-full bg-white rounded-2xl shadow-xl items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
                        role="tabpanel"
                        tabindex="0"
                        aria-labelledby="tab-2"
                        x-show="activeTab === 2"
                        x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                        x-transition:enter-start="opacity-0 -translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-12"
                    >
                        <div class="flex flex-col justify-center">
                            <div class="text-slate-500 text-sm line-clamp-3 mb-2">
                                {{-- Drwan Numbers in sequence --}}
                                <h2>Numbers in Sequence:</h2>
                                <ul id="drawn-numbers-sequance" class="w-full flex gap-2 flex-wrap justify-between mt-2 mb-6">
                                    @if(isset($drawnNumbers[0]))
                                        @foreach($drawnNumbers[0] as $number)
                                            <li class="w-10 h-10 bg-gray-300 flex justify-center items-center">{{ $number }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                                {{-- end Drwan Numbers --}}

                                {{-- All Numbers Table --}}
                                <h2>All Numbers:</h2>
                                @php
                                    $drawnNumbers = $drawnNumbers[0]->sort();
                                    $drawnNumbers = $drawnNumbers->values();
                                    // dd($drawnNumbers);
                                @endphp
                                <ul id="all-numbers" class="w-full flex gap-2 flex-wrap justify-between mt-2 mb-6">
                                    @for ($i = 1; $i <= 90; $i++)
                                        <li class="number-box {{ $drawnNumbers->contains($i) ? 'drawn w-10 h-10 bg-gray-300 flex justify-center items-center' : 'w-10 h-10 bg-gray-300 flex justify-center items-center' }}">{{ $i }}</li>
                                    @endfor
                                </ul>

                                <br>
                            </div>
                        </div>
                    </article>

                    <!-- Panel #3 -->
                    <article
                        id="tabpanel-3"
                        class="w-full bg-white rounded-2xl shadow-xl focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
                        role="tabpanel"
                        tabindex="0"
                        aria-labelledby="tab-3"
                        x-show="activeTab === 3"
                        x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                        x-transition:enter-start="opacity-0 -translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-12"
                    >
                        <div class="">
                            <div class="text-slate-500 text-sm">
                                {{-- All Prizes & Winners --}}
                                @if(isset($allPrizes))
                                    <x-bladewind::table>
                                        <x-slot name="header">
                                            <th>#</th>
                                            <th>Prize</th>
                                            <th>Amount</th>
                                            <th>Winner</th>
                                        </x-slot>
                                        @php $index = 1; @endphp
                                        @foreach($allPrizes as $prize)
                                            <tr>
                                                <td> {{ $index }} </td>
                                                <td> {{ $prize->prize_name }} </td>
                                                <td> {{ $prize->prize_amount }} </td>
                                                @if($prize->user_name != '')
                                                    <td data-prize="{{ $prize->prize_name }}-{{ $prize->user_name }}"> {{ $prize->user_name }} </td>
                                                @else
                                                    <td data-prize="{{ $prize->prize_name }}"> - </td>
                                                @endif
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
                                    </x-bladewind::table>
                                @endif
                                {{-- end Game Prizes --}}
                            </div>
                        </div>
                    </article>

                    <!-- Panel #4 -->
                    <article
                        id="tabpanel-4"
                        class="w-full bg-white rounded-2xl shadow-xl flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
                        role="tabpanel"
                        tabindex="0"
                        aria-labelledby="tab-4"
                        x-show="activeTab === 4"
                        x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                        x-transition:enter-start="opacity-0 -translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-12"
                    >
                        <div class="claim-list">
                            @if(isset($activeClaims))
                                @foreach($activeClaims as $claim)
                                    <div class="claim-item">
                                        <div class="flex items-center text-xs mb-1 w-full" style="justify-content: space-between">
                                            <div> {{ $claim->user_name }} </div>
                                            <div> Prize: {{ $claim->prize_name }} </div>
                                            <div> Ticket No: {{ $claim->ticket_id }} </div>
                                            <div> Status: <span class="text-green-500">{{ strtoupper($claim->status) }}</span> </div>
                                        </div>
                                        <div class="claim-ticket">
                                            @php $ticket = json_decode($claim->object, true); @endphp
                                            @foreach ($ticket as $row)
                                                <div class="claim-row ">
                                                    @foreach ($row as $cell)
                                                        @if( $cell['value'] > 0)
                                                            @if ($cell['checked'])
                                                                <div class="claim-cell checked">
                                                                    <span class="flex justify-center items-center"> {{ $cell['value'] }} </span>
                                                                </div>
                                                            @else
                                                                <div class="claim-cell unchecked">
                                                                    <span class="flex justify-center items-center"> {{ $cell['value'] }} </span>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="claim-cell unchecked">
                                                                <span class="flex justify-center items-center"></span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </article>
                </div>
            </div>
        </div>
        <!-- End: Tabs component -->
    </div>
    {{-- end Tabs --}}




    <style>

        /* Claim List */
        .claim-list {
            width: 100%; /* Ensure the container spans the full width */
        }
        .claim-item {

        }

        .claim-item .mb-6 {
            margin-bottom: 1.5rem; /* Adjust the margin-bottom as needed */
        }

        .claim-item .claim-row {
            display: flex;
            justify-content: center;
            width: 100%; /* Ensure the row spans the full width */
        }

        .claim-item .claim-cell {
            width: 100%; /* Ensure the cell spans the full width */
            margin: 0.2rem; /* Adjust the padding as needed */
            padding: 14px 0px;
            max-width: 75px;
            max-height: 75px;
            text-align: center;
            box-sizing: border-box; /* Ensure padding and border are included in the width */
        }

        .claim-item .claim-cell span {
            font-size: 0.8em; /* Adjust the font size as needed */
            display: block; /* Ensure the span occupies the full width of the cell */
        }

        .claim-item .claim-ticket {
            margin-bottom: 20px;
        }

        /* Media Queries for Responsiveness */
        @media (min-width: 768px) {
            .claim-item .claim-cell {
                width: 100%; /* Ensure the cell spans the full width */
                padding-top: 6%; /* Set the padding top to create a square */
                position: relative;
                border: 1px solid #ccc;
                box-sizing: border-box; /* Ensure padding and border are included in the width */
            }

            .claim-item .claim-cell span {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 0.8em; /* Adjust the font size as needed */
            }
        }

        @media screen and (max-width: 576px) {
            .claim-item .claim-cell {
                max-width: 40px; /* Further adjust the maximum width for even smaller screens */
                max-height: 40px; /* Further adjust the maximum hmax-height for even smaller screens */
                padding: 8px 0px;
            }
        }

   </style>

</div>
