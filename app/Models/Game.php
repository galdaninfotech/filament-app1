<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'start', 'end', 'status', 'comment'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['id', 'user_id']);
    }

    public function numbers()
    {
        return $this->belongsToMany(Number::class)->withPivot(['id', 'declared_at']);
    }

    public function prizes()
    {
        return $this->belongsToMany(Prize::class)->withPivot(['id', 'prize_amount', 'quantity', 'active', 'comment']);
    }

}
