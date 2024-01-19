<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class Board extends Component
{
    public $allNumbers = [];
    public $drawnNumbers = [];
    public $newNumber;
    public $activeGame;
    public $count = 0;

    public function mount() {
        $this->allNumbers = DB::table('numbers')->get();
        $this->activeGame = DB::table('games')->where('status', 1)->first();

        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        // dd($this->drawnNumbers[0]);
        // dd($numbersCollection);

        $this->count = DB::table('game_number')->where('game_id', $this->activeGame->id)->count();

    }

    public function draw() {
        if($this->count >= 100) { return; }

        $numbersCollection = Number::select('number')
                    ->whereNotIn('number', $this->drawnNumbers[0])
                    ->pluck('number');

        $numbersArray = $numbersCollection->toArray();
        $numbersArray = Arr::shuffle($numbersArray);
        $newNumber = Arr::first($numbersArray);

        $this->newNumber = $newNumber;
        // $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $newNumber);

        // dd($this->newNumber);

        DB::table('game_number')->insert([
            'game_id' => $this->activeGame->id,
            'number_id' => $this->newNumber,
            'declared_at' => now(),
        ]);

        // $numbersCollection = GameNumber::select('number_id')
        //     ->where('game_id', $this->activeGame->id)
        //     ->pluck('number_id');
        // $this->drawnNumbers = $numbersCollection->toArray();

        // $numbersCollection = Number::with('games')->get();
        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        // dd($numbersCollection);
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);

        $this->count = DB::table('game_number')->where('game_id', $this->activeGame->id)->count();

        $this->dispatch('new-number', newNumber: $newNumber);
    }

    public function render()
    {
        return view('livewire.board');
    }
}
