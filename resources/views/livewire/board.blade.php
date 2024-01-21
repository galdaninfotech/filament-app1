<div>

    Game: {{ $activeGame->name }}
    <br>
    <br>

    <button wire:click="draw" @click="$dispatch('new-number')" class="bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
        {{ __('Draw Number') }}
    </button>

    <br>
    <br>

    New Number : {{ $newNumber }}

    <br>
    <h5>Count: {{ $count }}</h5>

    <br>
    <h5>Drawn Numbers:</h5>
    <ul class="flex gap-2 flex-wrap"> 
        @if(isset($drawnNumbers[0]))
            @foreach($drawnNumbers[0] as $number)

                <li>{{ $number }},</li>
            @endforeach
        @endif
    </ul>

    <br>


</div>
