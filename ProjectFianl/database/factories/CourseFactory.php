<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_id'=>Subject::get()->random()->id,
            'name'=>fake()->title(),
            'content'=>fake()->paragraph(1),
            'poster'=>fake()->text(20),
            'hour'=>fake()->randomDigit(),
            'requirements'=>fake()->text(100),
            'valuation'=>fake()->randomDigit(),
            'price'=>fake()->randomDigit()
        ];
    }
}
