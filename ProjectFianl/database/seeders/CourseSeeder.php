<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'subject_id' => 5,
                'name' => 'Kinetic physics',
                'content' => 'Study everything related to kinetic physics',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'The basic principles in physics, the science of constants and variables',
                'price'=>36
            ],
            [
                'subject_id' => 7,
                'name' => 'Probability and statistics',
                'content' => 'Study of probability and statistics',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Basic knowledge of algebra and critical thinking',
                'price'=>20
            ],
            [
                'subject_id' => 8,
                'name' => '1A',
                'content' => 'Study of tenses',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'The student must study the first three levels',
                'price'=>18
            ],
            [
                'subject_id' => 9,
                'name' => 'Paleontology',
                'content' => 'Study of fossils in ancient times',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Excellent knowledge of fossil extraction method',
                'price'=>40
            ],
            [
                'subject_id' => 35,
                'name' => 'C++',
                'content' => 'basics of language',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'none',
                'price'=>19
            ],
            [
                'subject_id' => 36,
                'name' => 'analysis',
                'content' => 'integration',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Good knowledge of probabilities',
                'price'=>25
            ],
            [
                'subject_id' => 37,
                'name' => 'electrical_circuits',
                'content' => 'Explain how circuits are connected to each other',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Good knowledge of the basics of electricity',
                'price'=>20
            ],
            [
                'subject_id' => 38,
                'name' => 'linear_algebra',
                'content' => 'Explaining the mechanism of logical thinking',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Good knowledge of logic rules',
                'price'=>20
            ],
            [
                'subject_id' => 39,
                'name' => 'national_culture',
                'content' => 'Explaining the mechanism of logical thinking',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Good knowledge of logic rules',
                'price'=>10
            ],
            [
                'subject_id' => 40,
                'name' => 'arabic',
                'content' => 'Interpretation of the Koran',
                'poster' => '/storage/images/eqr2ynXA5AesJ43Kd2L4iUDrK7wBosVVBXuGdCRz.png',
                'requirements' => 'Memorizing the last 5 parts of the Qur’an',
                'price'=>0
            ],
        ];


       // Course::factory(15)->create();

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
