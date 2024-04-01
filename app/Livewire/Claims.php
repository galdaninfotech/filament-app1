<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Claim;
use App\Models\Winner;
use App\Events\WinnerEvent;

class Claims extends Component
{
    public $claimsz;
    public $activeGame;
    public $user;
    public $selectedClaimWithDetails;

    public function mount() {
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->user = Auth::user();

        // get all active claims for list view
        $this->claimsz = DB::table('claims')
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
                'claims.remarks',
                'claims.created_at',
                'game_prize.*',
                'tickets.object',
                'users.name as user_name',
                'prizes.name as prize_name',
            )
            ->get();
    }

    public function updateSelectedClaimWithDetails($claim_id) {
        // get details of the claim clicked
        $this->selectedClaimWithDetails = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->leftJoin('winners', 'claims.id', '=', 'winners.claim_id')
            ->where('games.id', '=', $this->activeGame->id)
            ->where('claims.id', '=', $claim_id)
            ->select(
                'claims.id as claim_id',
                'claims.ticket_id',
                'claims.game_prize_id',
                'claims.status',
                'claims.remarks',
                'claims.created_at',
                'game_prize.*',
                'tickets.object',
                'users.name as user_name',
                'users.id as user_id',
                'prizes.name as prize_name',
            )
            ->get();
        // dd($this->selectedClaimWithDetails);
    }

    public function updateClaimWinner($claim_id) {
        $claim = Claim::find($claim_id)
            ->update([
                'status' => 'Winner',
                'is_winner' => 1,
            ]);

        // on successful update fire an event
        if($claim) {
            event(new WinnerEvent([$this->selectedClaimWithDetails[0]->user_id, $this->selectedClaimWithDetails[0]]));
            $this->render();
        }
        // update winners table
        $winner = Winner::where('game_prize_id', $this->selectedClaimWithDetails[0]->game_prize_id)
                ->where('user_id', null)
                ->where('ticket_id', null)
                ->where('claim_id', null)
                ->first();

        if($winner) {
            $winner->update([
                'user_id' => $this->selectedClaimWithDetails[0]->user_id,
                'ticket_id' => $this->selectedClaimWithDetails[0]->ticket_id,
                'claim_id' => $this->selectedClaimWithDetails[0]->claim_id,
            ]);
            $winner->save();
        } else {
            dd('There is no prize left to set winner for!');
        }

    }

    public function updateClaimBoggy($claim_id) {
        $claim = Claim::find($claim_id)
        ->update([
            'status' => 'Boggy',
            'is_boggy' => 1,
        ]);

        // on successful update fire an event
        if($claim) {
            // event(new BoggyEvent([$this->selectedClaimWithDetails[0]->user_id, $this->selectedClaimWithDetails[0]]));
            $this->render();
        }
    }



    public function render()
    {
        return view('livewire.claims');
    }
}
