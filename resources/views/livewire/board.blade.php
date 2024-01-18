<div>
    <h1>Number Board</h1>
    <button wire:click="draw">Draw Number</button>

    {{ $activeGame->name }}

    <ul>
        @foreach($allNumbers as $number)
            <li>{{ $number->number }}</li>
        @endforeach
    </ul>

</div>
