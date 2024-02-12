<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Claim;
use App\Events\WinnerEvent;

class Claims extends Component
{
    public $claimsz;
    public $activeGame;
    public $user;
    public $selectedClaimWithDetails;

    public function mount() {
        $this->activeGame = DB::table('games')->where('status', 1)->first();
        $this->user = Auth::user();

        $this->claimsz = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
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
                'users.name as user_name',
                'prizes.name as prize_name',
            )
            ->get();
            
        // dd($results);
    }

    public function updateSelectedClaimWithDetails($claim_id) {
        $this->selectedClaimWithDetails = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->where('games.id', '=', $this->activeGame->id)
            ->where('tickets.user_id', '=', $this->user->id)
            ->where('claims.id', '=', $claim_id)
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


        // $claim = Claim::find($claim_id);
        // $claim->update([
        //     'is_winner' => 1
        // ]);
        // dd($this->selectedClaimWithDetails);
    }

    public function updateClaimWinner($claim_id) {
        $claim = Claim::find($claim_id)
        ->update([
            'status' => 'Winner',
            'is_winner' => 1,
        ]);
        
        if($claim) {
            // dd($claim_id);
            event(new WinnerEvent($claim_id));
            $this->render();
            return 'success';
        }
        
    }

    public function updateClaimBoggy($claim_id) {
        $claim = Claim::find($claim_id)
            ->update([
                'is_boogy' => 1
            ]);
    }

    public function render()
    {
        return view('livewire.claims');
    }
}
