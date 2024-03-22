<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestTicket extends Component
{
    public $tickets = [];
    public $user;
    public  $activeGame;


    protected $listeners = ['refreshComponent' => 'refreshComponent'];

    public function refreshComponent()
    {
        $this->dispatch('$refresh');
    }

    public function mount() {
        $this->user = Auth::user();
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->tickets = Ticket::whereBelongsTo($this->user)
            ->where('game_id', '=', $this->activeGame->id)
            ->with('claims')
            ->get();
    }

    public function updateChecked($ticket_id, $row, $column)
    {
        $ticket = Ticket::find($ticket_id);
        $ticketObject = $ticket->object;
        $ticketObject[$row][$column]['checked'] = !$ticketObject[$row][$column]['checked'];

        // Update the ticket object
        $ticket->object = $ticketObject;
        $ticket->save();

        $this->dispatch('refreshComponent');

    }

    public function render()
    {
        return view('livewire.test-ticket');
    }
}
