<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On; 

use Illuminate\Support\Facades\Session;

use App\Models\Prize;
use App\Models\Ticket;
use App\Models\Claim;
use App\Models\User;
use App\Classes\Table;

use App\Events\MyEvent;
use App\Events\NewNumber;
use App\Events\ClaimReceived;

class Tickets extends Component
{
    public $gamePrizes = [];
    public $ticketSelected;
    public $prizeSelected;
    public $tickets = [];
    public $user;
    public  $activeGame;

    public function mount() {
        $this->user = Auth::user();
        $this->activeGame = DB::table('games')->where('status', 1)->first();
        $this->tickets = Ticket::whereBelongsTo($this->user)
                            ->where('game_id', '=', $this->activeGame->id)
                            ->with('claims')
                            ->get();

        $this->gamePrizes = DB::table('game_prize')
                ->leftJoin('prizes', 'game_prize.prize_id', '=', 'prizes.id')
                ->where('game_prize.quantity', '>', '0')
                ->where('game_prize.game_id', '=', $this->activeGame->id)
                ->select('game_prize.*', 'prizes.name')
                ->get();
        // dd($this->game_prizes);
    }

    public function generateTicket($noOfTickets) {
        $table = new Table();
        $table->generate();

        $tickets = array_slice($table->getTickets(), 0, 2);
        // dd($tickets);

        // $tickets = Ticket::whereBelongsTo($this->user)->get();
        // dd($this->user->id);
        foreach ($tickets as $ticket) {
            $this->user->tickets()->create([
                'game_id' => $this->activeGame->id,
                'user_id' => $this->user->id,
                'object' => $ticket->numbers,
                'status' => 'active',
                'comment' => 'Some comments here..'
            ]);
        }
        // $tickets = $this->user->tickets()->create([
        //     'user_id' => 1,
        //     'object' => $tambola,
        //     'status' => 'active',
        //     'comment' => 'Some comments here..'
        // ]);
        
        $this->tickets = Ticket::whereBelongsTo($this->user)->get();
    }

    public function updateChecked($ticket_id, $object_id){

        //break object_id in two [row][column]
        $object_id = str_pad($object_id, 2, '0', STR_PAD_LEFT);
        $row = str_split($object_id)[0];
        $column = str_split($object_id)[1];

        //get ticket by id and ticket object from it
        $ticket = Ticket::where('id', $ticket_id)->get();
        $ticketObject = $ticket[0]->object;

        //toggle checked 
        if($ticketObject[$row][$column]['checked'] == 0) {
            $ticketObject[$row][$column]['checked'] = 1;
        }
        else {
            $ticketObject[$row][$column]['checked'] = 0;
        }

        //save to db
        $ticket[0]->object = $ticketObject;
        $ticket[0]->save();

        $this->mount();

    }

    public function updateTicketSelected($ticketSelected){
        $this->ticketSelected = $ticketSelected;
    }

    public function claimPrize() {
        $claim = Claim::create([
            'ticket_id'     => $this->ticketSelected,
            'game_prize_id' => $this->prizeSelected,
            'status'        => 'Open',
            'comment'       => 'Some comment here..'
        ]);
        
        // dd($claim);
        // Set status (fire an event)
        $this->dispatch('received-claim');
        Session::flash('message', 'Success! Claim sent. Game is paused for processing..');
        event(new ClaimReceived(['Game Paused']));

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

        $this->mount();
        $this->render();

            
    }

    public function setAutoMode() {
        $user = DB::table('users')
              ->where('id', $this->user->id)
              ->update(['automode' => 1]);
    }

    public function generateTambolaTicket(){
        $empty_index=array();
        $tambola=array();
        for ($i=0; $i <3 ; $i++) { 
            $empty_index[$i][] = $this->UniqueRandomNumbersWithinRange(0,8,4);
        }
        while(array_intersect($empty_index[0][0],$empty_index[1][0],$empty_index[2][0])){
            $empty_index[2][0] = $this->UniqueRandomNumbersWithinRange(0,8,4);
        }
        $n=0;
        for ($i=0; $i <9 ; $i++) { 
            if(!in_array($i, $empty_index[0][0])&&!in_array($i, $empty_index[1][0])){
                $empty_index[2][0][$n]=$i;
                $n++;
            }
        }
        $empty_count=count(array_unique($empty_index[2][0]));
        while($empty_count<4){
            for ($i=$empty_count; $i <4 ; $i++) { 
                $temp=rand(0,8);
                while(in_array($temp,array_intersect($empty_index[0][0],$empty_index[1][0]))){
                    $temp=rand(0,8);
                }
                $empty_index[2][0][$i]=$temp;
                $empty_count=count(array_unique($empty_index[2][0]));
            }
        }
        $list=array();
        for ($row=0; $row <3 ; $row++) { 
            for ($col=0; $col <9 ; $col++) {
                $min=$col*10+1;
                $max=$col*10+10;
                $tambola[$row][$col]['id']=$row.$col;
                if(!in_array($col, $empty_index[$row][0])){
                    $temp=rand($min,$max);
                    while (in_array($temp, $list)) {
                        $temp=rand($min,$max);
                    }
                    $list[]=$temp;
                    $tambola[$row][$col]['value']=$temp;
                }
                else{
                    $tambola[$row][$col]['value']='';   
                }
                $tambola[$row][$col]['checked']=0;
            }
        }

        $tickets = Ticket::whereBelongsTo($this->user)->get();

        $tickets = $this->user->tickets()->create([
            'user_id' => 1,
            'object' => $tambola,
            'status' => 'active',
            'comment' => 'Some comments here..'
        ]);
        
        $this->tickets = Ticket::whereBelongsTo($this->user)->get();
    }

    public function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
    
    public function render()
    {
        return view('livewire.tickets');
    }
}
