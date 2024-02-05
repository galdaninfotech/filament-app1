<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePrize extends Pivot
{
    use HasFactory;

    protected $fillable = ['game_id', 'prize_id', 'prize_amount', 'quantity', 'active', 'comment'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}
