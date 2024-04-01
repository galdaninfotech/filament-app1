<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use App\Models\Ticket;
use App\Models\Winner;
use App\Models\User;
use App\Models\Claim;
use App\Events\ClaimEvent;
use App\Classes\AutoMode\AutoMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Events\NumbersEvent;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;

class Board extends Component
{
    public $allNumbers = [];
    public $drawnNumbers = [];
    public $newNumber;
    public $activeGame;
    public $currentGameStatus;
    public $count = 0;
    public $user;
    public $isPrizesSet = false;
    public $noOfPrizes;
    public $noOfPrizeTypes;
    public $noOfLoggedInUsers;
    public $noOfPlayers;
    public $noOfTicketsSold;
    public $totalPrizeAmount;

    public function mount() {
        $this->user = Auth::user();
        $this->allNumbers = DB::table('numbers')->get();
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->currentGameStatus = $this->activeGame->status;
        $this->noOfPrizes = DB::table('winners')->count();
        $this->noOfPrizeTypes = DB::table('game_prize')->count();

        $numbersCollection = DB::table('game_number')->where('game_id', $this->activeGame->id)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

        $this->noOfPlayers = DB::table('tickets')
            ->where('game_id', $this->activeGame->id)
            ->get()
            ->unique('user_id')
            ->count();

        // Get Logged in users
        $activeSessions = DB::table('sessions')->where('last_activity', '>', now()->subMinutes(5))->get();
        $loggedInUserIds = $activeSessions->pluck('user_id')->unique();
        $this->noOfLoggedInUsers = User::whereIn('id', $loggedInUserIds)->count();

        $this->noOfTicketsSold = DB::table('tickets')->where('game_id',  $this->activeGame->id)->count();
        $this->totalPrizeAmount = DB::table('game_prize')->where('game_id',  $this->activeGame->id)->sum('prize_amount');
    }

    public function draw() {
        if($this->count >= 90) { dd('All Numbers Out!'); }

        // TASK 1: update game status
        if($this->currentGameStatus == 'Starting Shortly') {
            DB::table('games')->where('active', true)->update(['status' => 'Started']);
        } else if($this->currentGameStatus == 'Started') {
            DB::table('games')->where('active', true)->update(['status' => 'On']);
        } else if($this->currentGameStatus == 'Paused') {
            DB::table('games')->where('active', true)->update(['status' => 'Started']);
        }
        $this->mount();

        // TASK 2: get new number & update it
        $numbersCollection = Number::select('number')
                    ->whereNotIn('number', $this->drawnNumbers[0])
                    ->pluck('number');
        $numbersArray = $numbersCollection->toArray();
        $numbersArray = Arr::shuffle($numbersArray);
        $newNumber = Arr::first($numbersArray);
        $this->newNumber = $newNumber;

        // TASK 3: Insert into DB
        DB::table('game_number')->insert([
            'game_id' => $this->activeGame->id,
            'number_id' => $this->newNumber,
            'declared_at' => now(),
        ]);

        // TASK 4: drawnNumbers & count
        $numbersCollection = DB::table('game_number')->where('game_id', $this->activeGame->id)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

        // TASK 5: broadcast the new number
        broadcast(new NumbersEvent([$newNumber, $this->count, $this->drawnNumbers, $this->currentGameStatus]))->toOthers();
        Alert::toast('New Number : '.$newNumber, 'success');

        //TASK 6: AutoMode
        if($this->user->autotick) {
            $autoMode = new AutoMode($newNumber);
            $autoMode->updateAutoTickTickets();
            $autoMode->updateAutoClaimTickets();
        }
    }

    public function setPrizes(){
        if(Winner::first())
            dd('Prizes Alreadt Set!!');

        $gamePrizes = DB::table('game_prize')
                ->leftJoin('prizes', 'game_prize.prize_id', '=', 'prizes.id')
                ->where('game_prize.quantity', '>', '0')
                ->where('game_prize.game_id', '=', $this->activeGame->id)
                ->select('game_prize.*', 'prizes.name')
                ->get();

        foreach($gamePrizes as $gamePrize) {
            if($gamePrize->quantity > 1) {
                for($i = 0; $i < $gamePrize->quantity; $i++) {
                    Winner::create([
                        'game_prize_id' => $gamePrize->id,
                        'game_id' => $gamePrize->game_id,
                    ]);
                }
            } else {
                Winner::create([
                    'game_prize_id' => $gamePrize->id,
                    'game_id' => $gamePrize->game_id,
                ]);
            }
        }
        $this->isPrizesSet = true; // not using this property, safely delete
        $this->mount();
    }

    public function pauseGame(){
        $game = Game::where('active', true)->first();
        $game->update([
            'status' => 'Paused',
        ]);

        $this->currentGameStatus = 'Paused';
        //send GamePausedEvent
    }

    public function render()
    {
        return view('livewire.board');
    }
}
