<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketRepository extends Model
{
    use HasFactory;
    use HasUuids;


    protected $guarded = ['id'];

    protected $casts = [
        'object' => 'json',
    ];
}
