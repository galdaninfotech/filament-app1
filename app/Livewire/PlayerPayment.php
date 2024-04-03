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
        foreach ($this->newTickets as $ticket) {
            $ticket->delete();
        }
    }

    public function updateNewTickets($noOfTickets = 6) {
        $number = $noOfTickets;
        if($number < 6) {
            $noOfTables = 1;
            $noOfExtraTickets = 0;
         } else {
            $noOfTables = intdiv($number, 6);
            $noOfExtraTickets = fmod($number, 6);
            if(fmod($number, 6) > 0) {
               $noOfTables++;
            }
         }
         $noOfTicketsToGenerate = $noOfTables * 6;
        // dd($noOfTables, $noOfTicketsToGenerate);
        $newTickets = TicketRepository::take($noOfTicketsToGenerate)->get();
        // Delete the tickets from DB
        foreach ($newTickets as $ticket) {
            $ticket->delete();
        }
        // Take only that is required
        $this->newTickets = $newTickets->take($number);

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
