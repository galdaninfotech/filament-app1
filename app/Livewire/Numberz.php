<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On; 

class Numberz extends Component
{
    public $activeGame;
    public $count = 0;
    public $newNumber;
    public $drawnNumbers = [];

    public function mount() {
        $this->activeGame = DB::table('games')->where('status', 1)->first();

        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

    }

    // #[On('new-number')] 
    #[On('echo:channel-new-number,NewNumber')]
    public function updateNumber($newNumber) {
        dd($newNumber);
        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();
    }

    public function render()
    {
        return view('livewire.numberz');
    }
}
