<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameNumber extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['game_id', 'number_id'];

    protected $searchableFields = ['*'];

    
}
