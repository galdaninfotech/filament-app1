<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Classes\Table;
use App\Models\TicketRepository;

class PlayerPayment extends Component
{
    public $newTickets = [];
    public $activeGame;

    public function mount() {
        $this->activeGame = DB::table('games')->where('active', true)->first();
        $this->newTickets = $tickets = TicketRepository::take(6)->get();
    }

    public function updateNewTickets($noOfTicketsToGenerate = 6) {
        $this->newTickets = TicketRepository::whereBetween('id', [18, 23])
                 ->orderBy('id')
                 ->get();

        // foreach ($this->newTickets as $ticket) {
        //     $ticket->delete();
        // }
    }

    // function generateRandomString($length = 10) {
    //     return strtoupper(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));
    //     // return strtoupper(substr(uniqid(),5));
    // }

    public function render()
    {
        return view('livewire.player-payment');
    }
}
