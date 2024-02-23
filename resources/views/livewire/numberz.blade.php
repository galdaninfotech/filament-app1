<div>
    <div class="px-6 py-6 ">
        <h5 id="game-status">Game : {{ $activeGame->name }}</h5>

        <div id="new-number">
            New Number: {{ $newNumber }}
        </div>

        <h5 id="count">Numbers Count: {{ $count }}</h5>

        <h5 id="game-status">Game Status : {{ $currentGameStatus }}</h5>
    </div>

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
                        class="flex-1 text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                        :class="activeTab === 1 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                        :tabindex="activeTab === 1 ? 0 : -1"
                        :aria-selected="activeTab === 1"
                        aria-controls="tabpanel-1"
                        @click="activeTab = 1"
                        @focus="activeTab = 1"
                    >TICKETS</button>
                    <!-- Button #2 -->
                    <button
                        id="tab-2"
                        class="flex-1 text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                        :class="activeTab === 2 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                        :tabindex="activeTab === 2 ? 0 : -1"
                        :aria-selected="activeTab === 2"
                        aria-controls="tabpanel-2"
                        @click="activeTab = 2"
                        @focus="activeTab = 2"
                    >NUMBERS</button>
                    <!-- Button #3 -->
                    <button
                        id="tab-3"
                        class="flex-1 text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                        :class="activeTab === 3 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                        :tabindex="activeTab === 3 ? 0 : -1"
                        :aria-selected="activeTab === 3"
                        aria-controls="tabpanel-3"
                        @click="activeTab = 3"
                        @focus="activeTab = 3"
                    >PRIZES</button>
                    <button
                        id="tab-4"
                        class="flex-1 text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                        :class="activeTab === 4 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                        :tabindex="activeTab === 4 ? 0 : -1"
                        :aria-selected="activeTab === 4"
                        aria-controls="tabpanel-3"
                        @click="activeTab = 4"
                        @focus="activeTab = 4"
                    >CLAIMS</button>
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
                                {{-- Game Prizes --}}
                                @if(isset($gamePrizes))
                                    <x-bladewind::table>
                                        <x-slot name="header">
                                            <th>Prize</th>
                                            <th>Prize Amount</th>
                                            <th>Remaining</th>
                                        </x-slot>
                                        @foreach($gamePrizes as $prize)
                                            <tr>
                                                <td> {{ $prize->name }} </td>
                                                <td> {{ $prize->prize_amount }} </td>
                                                <td> {{ $prize->quantity }} </td>
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
                            <div class="text-slate-500 text-sm mb-2 flex flex-col items-center">
                                {{-- Game Prizes --}}
                                @if(isset($activeClaims))
                                    @foreach($activeClaims as $claim)
                                        <div class="mb-6">
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
                                                                    @else
                                                                        @php
                                                                            $class = 'unchecked';
                                                                        @endphp
                                                                    @endif
                                                                    <span class="w-full h-full text-xs p-2 {{$class}}"> {{ $cell['value'] }} </span>
                                                                @elseif (is_int($cell))
                                                                    <span class="cell w-full h-full text-xs p-2 {{$class}}">  </span>
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

    <style>
        .drawn, .checked {
            background-color: #00FF00; /* Green background for drawn numbers */
        }
        .unchecked {
            background-color: #d4d2d2; /* Green background for drawn numbers */
        }
    </style>
</div>
