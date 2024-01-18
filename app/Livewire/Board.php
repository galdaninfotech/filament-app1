<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class Board extends Component
{
    public $allNumbers = [];
    public $drawnNumbers = [1,2,3,4,5,6,7,8,9];
    public $newNumber;
    public $activeGame;

    public function mount() {
        $this->allNumbers = DB::table('numbers')->get();
        $this->activeGame = DB::table('games')->where('status', 1)->first();
        
    }

    public function draw() {
        $numbersCollection = Number::select('number')
                    ->whereNotIn('number', $this->drawnNumbers)
                    ->pluck('number');

        $numbersArray = $numbersCollection->toArray();
        $numbersArray = Arr::shuffle($numbersArray);
        $newNumber = Arr::first($numbersArray);

        $this->newNumber = $newNumber;
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $newNumber);

        DB::table('game_number')->insert([
            'game_id' => $this->activeGame,
            'number_id' => $this->newNumber,
        ]);

        $this->dispatch('new-number', newNumber: $newNumber);
    }

    public function render()
    {
        return view('livewire.board');
    }
}
