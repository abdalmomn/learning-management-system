<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course_user_pivot>
 */
class Course_user_pivotFactory extends Factory
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
            'course_id'=>Course::get()->random()->id,
            'paid'=>fake()->boolean(),
            'favorite'=>fake()->boolean,
        ];
    }
}
