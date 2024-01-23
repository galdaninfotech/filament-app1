<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Livewire\Attributes\On; 

use App\Models\Ticket;
use App\Models\User;


class Tickets extends Component
{
    public $tickets = [];
    public $user;

    public function mount() {
        $this->user = Auth::user();
        $this->tickets = Ticket::whereBelongsTo($this->user)->get();
        // dd($this->tickets);
        // dd($this->user->id);
        $tickets = DB::table('tickets')->where('user_id', $this->user->id)->get();
        // dd($tickets[0]->object);
        // dd(Ticket::find(1)->object[0][0]['value']);

    }

    public function updateChecked($ticket_id, $object_id){
        // dd($ticket_id.'ggggggggg'.$object_id);
        $object_id = str_pad($object_id, 2, '0', STR_PAD_LEFT);
        $row = str_split($object_id)[0];
        $column = str_split($object_id)[1];

        $ticket = Ticket::where('id', $ticket_id)->get();
        $ticketObject = $ticket[0]->object;
        if($ticketObject[$row][$column]['meta_checked'] = 0) {
            $ticketObject[$row][$column]['meta_checked'] = 1;
        }
        else {
            $ticketObject[$row][$column]['meta_checked'] = 0;
        }
        $ticket[0]->object = $ticketObject;
        $ticket[0]->save();

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
                $tambola[$row][$col]['meta_checked']=0;
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
