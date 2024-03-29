<?php

namespace Database\Factories;

use App\Models\Winner;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class WinnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Winner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id' => \App\Models\Game::factory(),
            'game_prize_id' => $this->faker->randomNumber(),
            'ticket_id' => \App\Models\Ticket::factory(),
            'claim_id' => \App\Models\Game::factory(),
        ];
    }
}
