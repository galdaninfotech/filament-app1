<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Number extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['number', 'tagline'];

    protected $searchableFields = ['*'];

    public function games()
    {
        return $this->belongsToMany(Game::class)->withPivot(['id', 'declared_at']);
    }
}
