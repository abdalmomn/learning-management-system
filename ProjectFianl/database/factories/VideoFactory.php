<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id'=>Course::get()->random()->id,
            'title'=>fake()->text(10),
            'description'=>fake()->paragraph(1),
            'duration'=>fake()->randomDigit(),
            'like'=>fake()->randomDigit(),
            'dislike'=>fake()->randomDigit(),
            'view'=>fake()->randomDigit(),
            'url'=>fake()->text(),
        ];
    }
}
