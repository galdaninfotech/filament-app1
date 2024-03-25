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


        // $table = new Table();
        // $table->generate();
        // $tickets = array_slice($table->getTickets(), 0, 6);

        // // Create a new structure to hold ticket data along with ticketId
        // $formattedTickets = [];
        // foreach ($tickets as $index => $ticket) {
        //     $formattedTickets[] = [
        //         'id' => $this->generateRandomString($length = 8),
        //         'isSelected' => 0,
        //         'numbers' => $ticket->numbers,
        //     ];
        // }

        $tickets = TicketRepository::take(6)->get();
        // dd($tickets[0]['object']);
        $this->newTickets = $tickets;

        // $this->newTickets = json_encode($formattedTickets);

    }

    public function updateNewTickets($noOfTicketsToGenerate = 1) {
        $table = new Table();
        $table->generate();
        $tickets = array_slice($table->getTickets(), 0, $noOfTicketsToGenerate);

        // Create a new structure to hold ticket data along with id
        $formattedTickets = [];
        foreach ($tickets as $index => $ticket) {
            $formattedTickets[] = [
                'id' => $this->generateRandomString($length = 8),
                'isSelected' => 0,
                'numbers' => $ticket->numbers,
            ];
        }
        $this->newTickets = json_encode($formattedTickets);
    }

    function generateRandomString($length = 10) {
        return strtoupper(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));
        // return strtoupper(substr(uniqid(),5));
    }

    public function render()
    {
        return view('livewire.player-payment');
    }
}
