<div>

    Game: {{ $activeGame->name }}
    <br>
    <br>

    <button wire:click="draw">Draw Number</button>

    <br>
    <br>

    New Number : {{ $newNumber }}

    <br>
    <h5>Count: {{ $count }}</h5>

    <br>
    <h5>Drawn Numbers:</h5>
    <ul class="flex gap-2"> 
        @if(isset($drawnNumbers[0]))
            @foreach($drawnNumbers[0] as $number)

                <li>{{ $number }},</li>
            @endforeach
        @endif
    </ul>

    <br>


</div>
