<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Winner extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['game_prize_id', 'claim_id'];

    protected $searchableFields = ['*'];

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
