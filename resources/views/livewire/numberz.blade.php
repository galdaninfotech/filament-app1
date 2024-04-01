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

<button type="button" wire:click="$refresh" class="px-4">
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
                        <span class="hidden mr-1 md:block">TICKETS</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                        </svg>
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
                            <span class="hidden mr-1 md:block">NUMBERS</span>
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
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
                        <span class="hidden mr-1 md:block">PRIZES</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                        </svg>
                        <span class="inline-flex items-center justify-center w-4 h-4 text-[px] text-blue-800 bg-blue-200 rounded-full">
                        {{$allPrizes->count()}}</span>
                        <span class="hidden mr-1 md:block">WINNERS</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
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
                        <span class="hidden mr-1 md:block">CLAIMS</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
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
                        class="w-full px-2 bg-white rounded-2xl shadow-xl items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        class="w-full px-2 bg-white rounded-2xl shadow-xl items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                        class="w-full px-2 bg-white rounded-2xl shadow-xl focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                                    <table id="prize-winner-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Prize</th>
                                                <th>Amount</th>
                                                <th>Winner</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                        </tbody>
                                    </table>
                                @endif
                                {{-- end Game Prizes --}}
                            </div>
                        </div>
                    </article>

                    <!-- Panel #4 -->
                    <article
                        id="tabpanel-4"
                        class="w-full px-2 bg-white rounded-2xl shadow-xl flex items-stretch focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300"
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
                                            <div> <span class="hidden md:inline-block">Prize:</span> {{ $claim->prize_name }} </div>
                                            <div> ..{{ strtoupper(substr($claim->ticket_id, 28, 8)) }} </div>
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

        /* Prize/Winner table */
        #prize-winner-table {
        line-height: 1.5;
        min-width: 100%;
        }
        #prize-winner-table th {
        padding: 1rem;
        background-color: #F3F4F6;
        color: #374151;
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-align: left;
        text-transform: uppercase;
        border-bottom-width: 2px;
        }
        #prize-winner-table td {
        padding: 1rem;
        background-color: #ffffff;
        font-size: 0.875rem;
        line-height: 1rem;
        border-bottom-width: 1px;
        border-color: #E5E7EB; ;
        }

        #prize-winner-table td > span {
        display: inline-block;
        position: relative;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        color: #064E3B;
        font-weight: 600;
        line-height: 1.25;
        }


        /* Claim List */
        .claim-list {
            width: 100%; /* Ensure the container spans the full width */
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
            font-size: 1em; /* Adjust the font size as needed */
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
