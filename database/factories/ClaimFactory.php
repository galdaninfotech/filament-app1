<?php

namespace Database\Factories;

use App\Models\Claim;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Claim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_prize_id' => $this->faker->randomNumber(),
            'status' => $this->faker->word(),
            'comment' => $this->faker->text(),
            'is_winner' => $this->faker->boolean(),
            'is_boogy' => $this->faker->boolean(),
            'ticket_id' => \App\Models\Ticket::factory(),
            'winner_id' => \App\Models\Winner::factory(),
        ];
    }
}
