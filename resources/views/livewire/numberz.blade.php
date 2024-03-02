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
{{-- {{ dd($allPrizes) }} --}}



    <div class="px-6 py-6 ">
        <h5 id="active-game">Game : {{ $activeGame->name }}</h5>

        <h5 id="game-status">Game Status : {{ $currentGameStatus }}</h5>

        <!-- <div id="new-number">
            New Number: {{ $newNumber }}
        </div>

        <h5 id="count">Numbers Count : {{ $count }}</h5>
        <h5 id="count">Number Of Prizes : {{ $allPrizes->count() }}</h5>
        -->
    </div>

    <x-recent-numbers :numbers="$drawnNumbers[0]"></x-recent-numbers>

    {{-- Tabs --}}
    <div class="w-full max-w-6xl mx-auto px-4 md:px-6 py-6">
        <!-- Tabs component -->
        <div x-data="{ activeTab: 1 }">
            <!-- Buttons -->
            <div class="flex justify-center">
                <div
                    role="tablist"
                    class="inline-flex justify-center bg-slate-200 p-1 mb-4 min-[480px]:mb-12 rounded-2xl"
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
                        {{ $allPrizes->count() }} </span>
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
            <div class="max-w-[640px] mx-auto">
                <div class="relative flex flex-col">

                    <!-- Panel #1 -->
                    <article
                        id="tabpanel-1"
                        class="w-full bg-white rounded-2xl shadow-xl min-[480px]:flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        class="w-full bg-white rounded-2xl shadow-xl min-[480px]:flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        <div class="flex flex-col justify-center p-5 pl-3">
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
                                {{-- end All Numbers Table --}}

                                <br>
                            </div>
                        </div>
                    </article>

                    <!-- Panel #3 -->
                    <article
                        id="tabpanel-3"
                        class="w-full bg-white rounded-2xl shadow-xl min-[480px]:flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        <div class="flex flex-col justify-center p-5 pl-3">
                            <div class="text-slate-500 text-sm line-clamp-3 mb-2">
                                {{-- All Prizes & Winners --}}
                                @if(isset($allPrizes))
                                    <x-bladewind::table>
                                        <x-slot name="header">
                                            <th>Prize</th>
                                            <th>Amount</th>
                                            <th>Winner</th>
                                        </x-slot>
                                        @foreach($allPrizes as $prize)
                                            <tr data-prize="{{ $prize->prize_name }}">
                                                <td> {{ $prize->prize_name }} </td>
                                                <td> {{ $prize->prize_amount }} </td>
                                                <td> {{ $prize->user_name }} </td>
                                            </tr>
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
                        class="w-full bg-white rounded-2xl shadow-xl min-[480px]:flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        <div class="flex flex-col justify-center p-5 pl-3">
                            <div class="claim__container text-slate-500 text-sm mb-2 flex flex-col items-center">
                                {{-- Game Prizes --}}
                                @if(isset($activeClaims))
                                    @foreach($activeClaims as $claim)
                                        <div class="claim__item mb-6">
                                            <div class="flex items-center justify-between space-x-6">
                                                <div> {{ $claim->user_name }} </div>
                                                <div> Status: {{ $claim->status }} </div>
                                            </div>
                                            <div class="ticket">
                                                @php $ticket = json_decode($claim->object, true); @endphp
                                                @foreach ($ticket as $row)
                                                    <div class="row flex gap-1 mt-2">
                                                        @foreach ($row as $cell)
                                                            <div class="column w-8 h-8 flex justify-center items-center">
                                                                @if (is_array($cell))
                                                                    @if($cell['checked'] == 1)
                                                                        @php
                                                                            $class = 'checked';
                                                                        @endphp
                                                                    @elseif($cell['checked'] == 0)
                                                                        @php
                                                                            $class = 'unchecked';
                                                                        @endphp
                                                                    @endif
                                                                    <span class="w-full h-full text-xs p-2 {{$class}}"> {{ $cell['value'] }} </span>
                                                                @elseif (is_int($cell) && $cell == 0 )
                                                                    <span class="cell w-full h-full text-xs p-2 {{$class}}"></span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- end Game Prizes --}}
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
        <!-- End: Tabs component -->
    </div>
    {{-- end Tabs --}}

</div>
