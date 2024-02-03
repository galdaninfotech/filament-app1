<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Events\NewNumber;
use App\Events\MyEvent;

class Board extends Component
{
    public $allNumbers = [];
    public $drawnNumbers = [];
    public $newNumber;
    public $activeGame;
    public $currentGameStatus = 'Starting shortly..';
    public $count = 0;

    public function mount() {
        $this->allNumbers = DB::table('numbers')->get();
        $this->activeGame = DB::table('games')->where('status', 1)->first();

        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

    }

    public function draw() {
        if($this->count >= 90) { return; }

        $numbersCollection = Number::select('number')
                    ->whereNotIn('number', $this->drawnNumbers[0])
                    ->pluck('number');

        // Convert to array, shuffle, get the first number and set $this->newNumber
        $numbersArray = $numbersCollection->toArray();
        $numbersArray = Arr::shuffle($numbersArray);
        $newNumber = Arr::first($numbersArray);
        $this->newNumber = $newNumber;

        //Insert into DB
        DB::table('game_number')->insert([
            'game_id' => $this->activeGame->id,
            'number_id' => $this->newNumber,
            'declared_at' => now(),
        ]);

        //Update $this->drawnNumbers & $this->count
        $numbersCollection = DB::table('game_number')->where('game_id', $this->activeGame->id)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

        // $this->dispatch('new-number', newNumber: $newNumber);
        // MyEvent::dispatch($newNumber);
        dd('kkkkkkkkkkkkkk');
        $this->mount();
        event(new MyEvent($newNumber));
    }

    public function render()
    {
        return view('livewire.board');
    }
}
