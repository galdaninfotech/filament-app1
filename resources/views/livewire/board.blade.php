<div>
    <h1>Number Board</h1>
    <button wire:click="draw">Draw Number</button>

    {{ $activeGame->name }}

    <br>
    <h5>New Number : </h5>

    @if(isset($newNumber))
        {{ $newNumber }}
    @endif


    <h5>Drawn Numbers:</h5>
    <ul class="flex gap-2"> 
        @if(isset($drawnNumbers))
            @foreach($drawnNumbers as $number)
                <li>{{ $number}},</li>
            @endforeach
        @endif
    </ul>

    <br>

    <ul class="flex gap-2"> 
        @foreach($allNumbers as $number)
            <li>{{ $number->number }}, </li>
        @endforeach
    </ul>

</div>
