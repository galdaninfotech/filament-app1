<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasFactory;
    use Searchable;
    use HasUuids;

    protected $fillable = ['game_id', 'user_id', 'object', 'status', 'comment'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'object' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
