<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ClaimForm extends Form
{
    #[Validate('required')]
    public $ticketId = '';
 
    #[Validate('required')]
    public $gamePrizeId = '';
}
