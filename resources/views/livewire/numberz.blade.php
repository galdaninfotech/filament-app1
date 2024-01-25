<div>

    <div class="px-6 py-6 ">
        Game: {{ $activeGame->name }}
        <br>
        <br>
        <div zzwire:poll.keep-alive="updateNumber">
        New Number: {{ $newNumber }}
        </div>
        <br>
        <h5>Count: {{ $count }}</h5>
        <br>

        
        <h5>Drawn Numbers:</h5>
        <ul class="w-full flex gap-2 flex-wrap justify-between">
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

</div>
