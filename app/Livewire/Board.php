<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    public $user;

    public function mount() {
        $this->user = Auth::user();
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

        // get users who has enabled AutoMode
        $users = User::where('automode', 1)->get();

        foreach ($users as $user) {
            $tickets = Ticket::where('game_id', $this->activeGame->id)
                ->where('user_id', $user->id)
                ->get();
        
            foreach ($tickets as $ticket) {
                $ticketObject = $ticket->object; // No need to decode if it's already an array
                $modified = false; // Flag to track if any modifications were made to the ticket
                
                for ($j = 0; $j < 3; $j++) {
                    for ($k = 0; $k < 9; $k++) {
                        // Check if the ticket object is an array and matches the new number
                        if (is_array($ticketObject[$j][$k]) && $ticketObject[$j][$k]['value'] == $this->newNumber) {
                            // Update the 'checked' attribute of the ticket object
                            $ticketObject[$j][$k]['checked'] = 1;
                            $modified = true; // Set the flag to true since modifications were made
                        }
                    }
                }
        
                // If modifications were made, save the updated ticket
                if ($modified) {
                    $ticket->object = $ticketObject; // No need to encode if it's already an array
                    $ticket->save();
                }
            }
        }

        // //get ticket by id and ticket object from it
        // $ticket = Ticket::where('id', $ticket_id)->get();
        // $ticketObject = $ticket[0]->object;

        // //toggle checked 
        // if($ticketObject[$row][$column]['checked'] == 0) {
        //     $ticketObject[$row][$column]['checked'] = 1;
        // }
        // else {
        //     $ticketObject[$row][$column]['checked'] = 0;
        // }

        // //save to db
        // $ticket[0]->object = $ticketObject;
        // $ticket[0]->save();
    }

    public function render()
    {
        return view('livewire.board');
    }
}
