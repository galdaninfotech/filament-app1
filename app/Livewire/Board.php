<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Number;
use Illuminate\Support\Facades\DB;

class Board extends Component
{
    public $allNumbers = [];
    public $drawnNumbers = [];
    public $activeGame;

    public function mount() {
        $this->allNumbers = DB::table('numbers')->get();
        $this->activeGame = DB::table('games')->where('status', 1)->first();
        
    }

    public function render()
    {
        return view('livewire.board');
    }
}
