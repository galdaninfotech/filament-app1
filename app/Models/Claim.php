<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'ticket_id',
        'game_prize_id',
        'status',
        'remarks',
        'is_winner',
        'is_boogy',
        'winner_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'is_winner' => 'boolean',
        'is_boogy' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function winner()
    {
        return $this->belongsTo(Winner::class);
    }

    public function gamePrize()
    {
        return $this->belongsTo(GamePrize::class);
    }
}
