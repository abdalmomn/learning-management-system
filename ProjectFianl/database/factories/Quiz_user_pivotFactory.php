<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz_user_pivot>
 */
class Quiz_user_pivotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::get()->random()->id,
            'quiz_id'=>Quiz::get()->random()->id,
            'type'=>fake()->text(5),
            'mark'=>fake()->randomDigit(),
        ];
    }
}
