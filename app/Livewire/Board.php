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

        // dd($numbersCollection);

        $numbersArray = $numbersCollection->toArray();
        // dd($numbersArray);
        $numbersArray = Arr::shuffle($numbersArray);
        // dd($numbersArray);


        $newNumber = Arr::first($numbersArray);
        // dd($newNumber);


        $this->newNumber = $newNumber;
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $newNumber);
        // dd($this->drawnNumbers);
    }

    public function draw2() {
        $numbers = DB::table('numbers')
                    ->whereNotIn('number', $this->drawnNumbers)
                    ->get();

        // dd($numbers);

        // $array = Arr::shuffle($numbers);
        $shuffled = $numbers->shuffle();
        $shuffled->toArray();
        $newNumber = Arr::first($shuffled);

        $this->newNumber = $newNumber;
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $newNumber);
    }

    public function render()
    {
        return view('livewire.board');
    }
}
