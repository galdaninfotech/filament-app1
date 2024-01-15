<?php

namespace Database\Factories;

use App\Models\Number;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class NumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Number::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->randomNumber(),
            'tagline' => $this->faker->word(80),
        ];
    }
}
