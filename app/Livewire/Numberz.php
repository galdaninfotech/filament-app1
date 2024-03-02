<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use App\Models\Game;
use App\Models\Ticket;
use App\Models\GameNumber;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use RealRashid\SweetAlert\Facades\Alert;


class Numberz extends Component
{
    public $activeGame;
    public $count = 0;
    public $newNumber;
    public $drawnNumbers = [];
    public $currentGameStatus;
    // public $gamePrizes = [];
    public $allPrizes = [];
    public $allWinners = [];
    public $activeClaims = [];
    public $tickets = [];
    public $user;

    public function mount() {
        // notify()->success('Laravel Notify is awesome!');
        // Alert::toast('Success Title', 'Success Message from Numberz');

        $this->user = Auth::user();
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->currentGameStatus = $this->activeGame->status;

        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();

        // $this->gamePrizes = DB::table('game_prize')
        //             ->leftJoin('prizes', 'game_prize.prize_id', '=', 'prizes.id')
        //             ->where('game_prize.quantity', '>', '0')
        //             ->where('game_prize.game_id', '=', $this->activeGame->id)
        //             ->select('game_prize.*', 'prizes.name')
        //             ->get();

        $this->allPrizes = DB::table('winners')
                    ->join('game_prize', 'winners.game_prize_id', '=', 'game_prize.id')
                    ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
                    ->leftJoin('users', 'winners.user_id', '=', 'users.id')
                    ->select(
                        'winners.*',
                        'game_prize.prize_amount as prize_amount',
                        'prizes.name as prize_name',
                        'users.name as user_name',
                    )
                    ->get();

        $this->allWinners = DB::table('winners')
                    ->join('game_prize', 'winners.game_prize_id', '=', 'game_prize.id')
                    ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
                    ->join('users', 'winners.user_id', '=', 'users.id')
                    ->where('user_id', '!=', null)
                    ->where('ticket_id', '!=', null)
                    ->where('claim_id', '!=', null)
                    ->get();

        // dd($this->allPrizes);

        $this->activeClaims = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->where('games.id', '=', $this->activeGame->id)
            ->where('claims.status', '=', 'Open')
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

        $this->tickets = Ticket::whereBelongsTo($this->user)
            ->where('game_id', '=', $this->activeGame->id)
            ->with('claims')
            ->get();
    }

    // #[On('new-number')]
    #[On('echo:channel-new-number,NewNumber')]
    public function updateNumber() {
        $numbersCollection = DB::table('game_number')->where('game_id', 2)->pluck('number_id');
        $this->drawnNumbers = Arr::prepend($this->drawnNumbers, $numbersCollection);
        $this->count = $numbersCollection->count();
        $this->newNumber = DB::table('game_number')->where('game_id', 2)->pluck('number_id')->last();
    }

    #[On('claim-event')]
    public function currentGameStatus() {
        // dd('public function currentGameStatus()');
        return $this->redirect(request()->header('Referer'), navigate: true);
        $this->mount();
        $this->render();
    }

    #[On('numbers-event')]
    public function refreshComponent() {
        dd('public function refreshComponent()- gggggggggggggggggggggggggggggggggggg');
        $this->mount();
        $this->render();
    }

    public function render()
    {
        return view('livewire.numberz');
    }
}
