<div>

    <div class="px-6 py-6 ">
        Game: {{ $activeGame->name }}
        <br>
        <br>
        <div zzzwire:poll.keep-alive="updateNumber" id="new-number">
            New Number: {{ $newNumber }}
        </div>
        <br>
        <h5 id="count">Numbers Count: {{ $count }}</h5>
        <br>

        <h5 id="game-status">Game Status : {{ $currentGameStatus }}</h5>
        <br>
        <br>

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

        
        
        <h5>Drawn Numbers:</h5>
        <ul id="drawn-numbers-sequance" class="w-full flex gap-2 flex-wrap justify-between">
            @if(isset($drawnNumbers[0]))
                @foreach($drawnNumbers[0] as $number)
                    <li class="w-10 h-10 bg-gray-300 flex justify-center items-center">{{ $number }}</li>
                @endforeach
            @endif
        </ul>

        <br>
        <br>
        <div x-on:new-number="{{ $newNumber }}"></div>
    </div>

    <!-- All Numbers Table -->
    <h5>All Numbers:</h5>
    @php
    $drawnNumbers = $drawnNumbers[0]->sort();
    $drawnNumbers = $drawnNumbers->values();
    // dd($drawnNumbers);
    @endphp
    <div class="px-6 py-6 ">
        <ul id="all-numbers" class="w-full flex gap-2 flex-wrap justify-between">
            @for ($i = 1; $i <= 90; $i++)
                <li class="number-box {{ $drawnNumbers->contains($i) ? 'drawn w-10 h-10 bg-gray-300 flex justify-center items-center' : 'w-10 h-10 bg-gray-300 flex justify-center items-center' }}">{{ $i }}</li>
            @endfor
        </ul>
    </div>
    <!-- end All Numbers Table -->

    <br>
    <br>

    <style>
        .drawn, .checked {
            background-color: #00FF00; /* Green background for drawn numbers */
        }
        .unchecked {
            background-color: #d4d2d2; /* Green background for drawn numbers */
        }
    </style>
</div>
