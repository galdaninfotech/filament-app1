<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'start' => $this->faker->dateTime('now', 'UTC'),
            'end' => $this->faker->dateTime('now', 'UTC'),
            'status' => $this->faker->boolean(),
            'comment' => $this->faker->text(),
        ];
    }
}
