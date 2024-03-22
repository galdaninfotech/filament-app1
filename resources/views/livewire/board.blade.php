<div>
    @php use Carbon\Carbon; @endphp
    <div class="px-4 py-4 ">
        <h5 id="game-status">Game : {{ $activeGame->name }}</h5>
        <h5 id="game-status">Start Time : {{ $activeGame->start }}</h5>
        <h5 id="game-status">Time Lapse : {{ Carbon::createFromTimeStamp(strtotime($activeGame->start))->diffForHumans() }}</h5>
        <h5 id="game-status">End Time: </h5>

        <h5 id="game-status">Game Status : {{ $currentGameStatus }}</h5>

        <br>

        <button wire:click="draw" @click="$dispatch('new-number')" class="fi-btn-label button">
            {{ __('New Number') }}
        </button>

        <button wire:click="setPrizes" class="fi-btn-label button">
            {{ __('Set Prizes') }}
        </button>

        <button wire:click="pauseGame" class="fi-btn-label button">
            {{ __('Pause Game') }}
        </button>

        <h5 id="new-number"> New Number: {{ $newNumber }} </h5>

        <h5 id="count"> Numbers Count: {{ $count }}</h5>
        <h5 id="count"> No. Of Prizes: {{ $noOfPrizes }}</h5>
        <h5 id="count"> Prize Types: {{ $noOfPrizeTypes }}</h5>
        <h5 id="count"> No. Of Online Users: {{ $noOfLoggedInUsers }}</h5>
        <h5 id="count"> No. Of Players: {{ $noOfPlayers }}</h5>
        <h5 id="count"> Tickets Sold: {{ $noOfTicketsSold }}</h5>
        <h5 id="count"> Total Ticket Amount: {{ $noOfTicketsSold * $activeGame->ticket_price }}</h5>
        <h5 id="count"> Total Prize Amount: {{ $totalPrizeAmount }}</h5>

    </div>


    <div class="px-4 py-4 ">
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
        <h2 class="pt-4">All Numbers:</h2>
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
    </div>

</div>
