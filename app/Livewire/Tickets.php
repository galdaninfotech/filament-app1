<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;

use App\Models\Prize;
use App\Models\Ticket;
use App\Models\Claim;
use App\Models\User;
use App\Classes\Table;

use App\Events\MyEvent;
use App\Events\NewNumber;
use App\Events\ClaimEvent;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;

class Tickets extends Component
{
    public $gamePrizes = [];
    public $remainingPrizes = [];
    public $prizesForSelectInput = [];
    public $ticketSelected;
    public $prizeSelected;
    public $tickets = [];
    public $newTickets = [];
    public $user;
    public $autoTick;
    public $autoClaim;
    public  $activeGame;
    public $loading = false; // Add loading flag

    public function mount() {
        // Alert::success('Success Title', 'Success Messagezzzzzzzzzzzzzzzzzzzzzzzzzzzz');

        $this->user = Auth::user();
        // $this->autoMode = $this->user->automode;
        $this->autoTick = $this->user->autotick;
        $this->autoClaim = $this->user->autoclaim;
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->tickets = Ticket::whereBelongsTo($this->user)
            ->where('game_id', '=', $this->activeGame->id)
            ->with('claims')
            ->get();

        // initial tickets when but ticket first loads
        $table = new Table();
        $table->generate();
        $tickets = array_slice($table->getTickets(), 0, 2);
        $this->newTickets = json_encode($tickets);

        $this->prizesForSelectInput = DB::table('prizes')
            ->join('game_prize', 'prizes.id', 'game_prize.prize_id')
            ->distinct('prizes.name')
            ->get();

        Alert::toast('Game Status : ' . $this->activeGame->status,'success');
    }

    public function updateChecked() {
        $ticket_id = request()->input('ticket_id');
        $row = request()->input('row');
        $column = request()->input('column');

        // Update the ticket object
        $ticket = Ticket::where('id', $ticket_id)->first();
        $ticketObject = $ticket->object;
        $ticketObject[$row][$column]['checked'] = !$ticketObject[$row][$column]['checked'];
        $ticket->object = $ticketObject;
        $ticket->save();

        $this->render();

    }

    public function updateTicketSelected($ticketSelected){
        $this->ticketSelected = $ticketSelected;
    }

    public function claimPrize($ticketId) {
        if($this->hasClaim($ticketId, $this->prizeSelected)) {
            // Flash the status message with a 10-second expiration time
            session()->now('status', strtoupper($this->getPrizeName($this->prizeSelected)) .' already claimed for ticket no.: ' . $this->ticketSelected, now()->addSeconds(5));
            return;
        }

        //  create a new claim in the DB
        $claim = Claim::create([
            'ticket_id'     => $ticketId,
            'game_prize_id' => $this->prizeSelected,
            'status'        => 'Open',
            'remarks'       => 'Claim created by '.$this->user->name.' for TicketId ..'. strtoupper(substr($ticketId, 28, 8))
        ]);

        //set active game status to 'Paused'
        DB::table('games')->where('active', true)->update(['status' => 'Paused']);

        //get the new claim with all details from DB, and fire an event
        $newClaim = DB::table('claims')
            ->join('game_prize', 'claims.game_prize_id', '=', 'game_prize.prize_id')
            ->join('tickets', 'claims.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('games', 'tickets.game_id', '=', 'games.id')
            ->join('prizes', 'game_prize.prize_id', '=', 'prizes.id')
            ->where('claims.id', '=', $claim->id)
            ->where('games.id', '=', $this->activeGame->id)
            ->select(
                    'claims.id as claim_id',
                    'claims.ticket_id',
                    'claims.game_prize_id',
                    'claims.status',
                    'claims.remarks',
                    'claims.created_at',
                    'game_prize.*',
                    'tickets.object as ticket',
                    'tickets.id as ticket_id',
                    'users.name as user_name',
                    'prizes.name as prize_name',
            )
            ->get();

        Session::flash('message', 'Success! Claim sent. Game is paused for processing..');
        event(new ClaimEvent($newClaim));

        // get quantity of prize and decrement it
        $prizes = DB::table('game_prize')
            ->where('game_id', '=', $this->activeGame->id)
            ->where('prize_id', '=', $this->prizeSelected)
            ->get();
        $quantity = $prizes[0]->quantity;
        if($quantity > 0){
            $prizes = DB::table('game_prize')
                ->where('game_id', '=', $this->activeGame->id)
                ->where('prize_id', '=', $this->prizeSelected)
                ->update([
                    'quantity' => $quantity - 1,
                ]);
        }

    }

    public function toggleAutoTick() {
        $user = User::where('id', request()->input('user_id'))->first();
        $user->update(['autotick' => !$user->autotick]);
        $this->autoTick = !$user->autotick;
    }

    public function toggleAutoClaim() {
        $user = User::where('id', request()->input('user_id'))->first();
        $user->update(['autoclaim' => !$user->autoclaim]);
        $this->autoClaim = !$user->autoclaim;
    }

    public function hasClaim($ticketId, $game_prize_id) {
        $claim = Claim::where('ticket_id', $ticketId)
            ->where('status', 'Open')
            ->where('game_prize_id', $game_prize_id)
            ->first();

        if ($claim)
            return true;

        return false;
    }

    public function getPrizeName($game_prize_id) {
        $prizeName = DB::table('prizes')
            ->join('game_prize', 'prizes.id', 'game_prize.prize_id')
            ->where('game_prize.id', $game_prize_id)
            ->pluck('prizes.name');

        return $prizeName[0];
    }

    public function render()
    {
        return view('livewire.tickets');
    }
}
