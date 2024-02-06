<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Claim;

class Claims extends Component
{
    public $claimsz;
    public $activeGame;

    public function mount() {
        $this->activeGame = DB::table('games')->where('status', 1)->first();
        $this->user = Auth::user();

        $this->claimsz = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->where('games.id', '=', $this->activeGame->id)
            ->where('tickets.user_id', '=', $this->user->id)
            ->select(
                'claims.id as claim_id', 
                'claims.ticket_id', 
                'claims.game_prize_id', 
                'claims.status', 
                'claims.comment', 
                'claims.created_at', 
                'game_prize.*',
                'tickets.object',
                'users.name',
            )
            ->get();
            
        // dd($results);
    }
    public function render()
    {
        return view('livewire.claims');
    }
}
