<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Events\NumbersEvent;

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

        //TODOs remove hardcoding of game_id $this->activeGame->id
        $numbersCollection = DB::table('game_number')->where('game_id', $this->activeGame->id)->pluck('number_id');
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

        event(new NumbersEvent([$newNumber, $this->count, $this->drawnNumbers]));
        // dd($var);

        // AutoMode
        $users = DB::table('users')
              ->where('automode', 1)
              ->get();

        // dd($users[0]->id);
        foreach($users as $user) {
            $tickets = DB::table('tickets')
                ->where('user_id', $user->id)
                // ->whereJsonContains('object->value', 6)
                ->get();
                dd($tickets);
        }
    //     $users = DB::table('users')
    //     ->whereJsonContains('options->languages', $this->newNumber)
    //     ->get();
    }

    public function render()
    {
        return view('livewire.board');
    }
}
