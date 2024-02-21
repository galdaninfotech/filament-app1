<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\GameNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On; 

class Numberz extends Component
{
    public $activeGame;
    public $count = 0;
    public $newNumber;
    public $drawnNumbers = [];
    public $currentGameStatus = 'Starting shortly..';
    public $gamePrizes = [];
    public $activeClaims = [];

    public function mount() {
        $this->activeGame = DB::table('games')->where('status', 1)->first();

        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

        $this->gamePrizes = DB::table('game_prize')
                    ->leftJoin('prizes', 'game_prize.prize_id', '=', 'prizes.id')
                    ->where('game_prize.quantity', '>', '0')
                    ->where('game_prize.game_id', '=', $this->activeGame->id)
                    ->select('game_prize.*', 'prizes.name')
                    ->get();

        // dd( $this->gamePrizes);
                    
        $this->activeClaims = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->where('games.id', '=', $this->activeGame->id)
            ->select(
                'claims.id as claim_id', 
                'claims.ticket_id', 
                'claims.game_prize_id', 
                'claims.status', 
                'claims.comment', 
                'claims.created_at', 
                'game_prize.*',
                'tickets.object',
                'users.name as user_name',
                'prizes.name as prize_name',
            )
            ->get();
        // dd( $this->activeClaims);

    }

    // #[On('new-number')] 
    #[On('echo:channel-new-number,NewNumber')]
    public function updateNumber() {
        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();
        $this->newNumber = DB::table('game_number')->where('game_id', 2)->pluck('number_id')->last();
    }

    public function render()
    {
        return view('livewire.numberz');
    }
}
