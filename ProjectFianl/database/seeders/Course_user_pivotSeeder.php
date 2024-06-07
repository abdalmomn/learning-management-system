<?php

namespace Database\Seeders;

use App\Models\Course_user_pivot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Course_user_pivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Course_user_pivot::factory(10)->create();
        $relationTeachers = [
            [
                'user_id' => 2 ,
                'course_id' => 1 ,
            ],
            [
                'user_id' => 3 ,
                'course_id' => 2 ,
            ],
            [
                'user_id' => 4 ,
                'course_id' => 3 ,
            ],
            [
                'user_id' => 5 ,
                'course_id' => 4 ,
            ],
            [
                'user_id' => 6 ,
                'course_id' => 5 ,
            ],
            [
                'user_id' => 7 ,
                'course_id' => 6 ,
            ],
            [
                'user_id' => 8 ,
                'course_id' => 7 ,
            ],
            [
                'user_id' => 9 ,
                'course_id' => 8 ,
            ],
            [
                'user_id' => 10 ,
                'course_id' => 9 ,
            ],
            [
                'user_id' => 11 ,
                'course_id' => 10 ,
            ],
        ];

        $relationStudents = [
            [
                'user_id' => 12 ,
                'course_id' => 1 ,
                'paid' => 1 ,
            ],
            [
                'user_id' => 12 ,
                'course_id' => 5 ,
                'paid' => 1 ,
            ],
            [
                'user_id' => 13 ,
                'course_id' => 2 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 13 ,
                'course_id' => 1 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 13 ,
                'course_id' => 7 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 14 ,
                'course_id' => 3 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 14 ,
                'course_id' => 1 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 15 ,
                'course_id' => 4 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 15 ,
                'course_id' => 1 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 15 ,
                'course_id' => 9 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 15 ,
                'course_id' => 8 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 16 ,
                'course_id' => 3 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 16 ,
                'course_id' => 6 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 16 ,
                'course_id' => 9 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 17 ,
                'course_id' => 6 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 17 ,
                'course_id' => 1 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 17 ,
                'course_id' => 8 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 18 ,
                'course_id' => 7 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 19 ,
                'course_id' => 6 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 20 ,
                'course_id' => 9 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 20 ,
                'course_id' => 10 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 20 ,
                'course_id' => 1 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 20 ,
                'course_id' => 2 ,
                'paid' => 1 ,

            ],
            [
                'user_id' => 21 ,
                'course_id' => 10 ,
                'paid' => 1 ,

            ],
        ];

        foreach ($relationTeachers as $relationTeacher){
            Course_user_pivot::create($relationTeacher);
        }

        foreach ($relationStudents as $relationStudent){
            Course_user_pivot::create($relationStudent);
        }
    }
}
