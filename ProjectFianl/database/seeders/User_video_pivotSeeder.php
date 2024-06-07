<?php

namespace Database\Seeders;

use App\Models\User_video_pivot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User_video_pivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User_video_pivot::factory(10)->create();

        $videoTeachers = [
            [
                'user_id' => 2,
                'video_id' => 1,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 2,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 3,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 4,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 5,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 6,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 7,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 8,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 9,
                'course_id' => 1,
                'watched' => 0,
            ],
            [
                'user_id' => 2,
                'video_id' => 10,
                'course_id' => 1,
                'watched' => 0,
            ],


            [
                'user_id' => 3,
                'video_id' => 1,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 2,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 3,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 4,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 5,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 6,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 7,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 8,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 9,
                'course_id' => 2,
                'watched' => 0,
            ],
            [
                'user_id' => 3,
                'video_id' => 10,
                'course_id' => 2,
                'watched' => 0,
            ],

            [
                'user_id' => 4,
                'video_id' => 1,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 2,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 3,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 4,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 5,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 6,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 7,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 8,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 9,
                'course_id' => 3,
                'watched' => 0,
            ],
            [
                'user_id' => 4,
                'video_id' => 10,
                'course_id' => 3,
                'watched' => 0,
            ],

            [
                'user_id' => 5,
                'video_id' => 1,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 2,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 3,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 4,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 5,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 6,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 7,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 8,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 9,
                'course_id' => 4,
                'watched' => 0,
            ],
            [
                'user_id' => 5,
                'video_id' => 10,
                'course_id' => 4,
                'watched' => 0,
            ],

            [
                'user_id' => 6,
                'video_id' => 1,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 2,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 3,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 4,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 5,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 6,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 7,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 8,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 9,
                'course_id' => 5,
                'watched' => 0,
            ],
            [
                'user_id' => 6,
                'video_id' => 10,
                'course_id' => 5,
                'watched' => 0,
            ],

            [
                'user_id' => 7,
                'video_id' => 1,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 2,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 3,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 4,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 5,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 6,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 7,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 8,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 9,
                'course_id' => 6,
                'watched' => 0,
            ],
            [
                'user_id' => 7,
                'video_id' => 10,
                'course_id' => 6,
                'watched' => 0,
            ],

            [
                'user_id' => 8,
                'video_id' => 1,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 2,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 3,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 4,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 5,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 6,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 7,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 8,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 9,
                'course_id' => 7,
                'watched' => 0,
            ],
            [
                'user_id' => 8,
                'video_id' => 10,
                'course_id' => 7,
                'watched' => 0,
            ],

            [
                'user_id' => 9,
                'video_id' => 1,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 2,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 3,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 4,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 5,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 6,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 7,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 8,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 9,
                'course_id' => 8,
                'watched' => 0,
            ],
            [
                'user_id' => 9,
                'video_id' => 10,
                'course_id' => 8,
                'watched' => 0,
            ],

            [
                'user_id' => 10,
                'video_id' => 1,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 2,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 3,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 4,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 5,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 6,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 7,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 8,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 9,
                'course_id' => 9,
                'watched' => 0,
            ],
            [
                'user_id' => 10,
                'video_id' => 10,
                'course_id' => 9,
                'watched' => 0,
            ],

            [
                'user_id' => 11,
                'video_id' => 1,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 2,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 3,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 4,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 5,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 6,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 7,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 8,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 9,
                'course_id' => 10,
                'watched' => 0,
            ],
            [
                'user_id' => 11,
                'video_id' => 10,
                'course_id' => 10,
                'watched' => 0,
            ],
        ];
        $videoStudents = [
            [
                'user_id' => 12,
                'video_id' => 1,
                'course_id' => 5,
                'watched' => 1,
            ],
            [
                'user_id' => 12,
                'video_id' => 2,
                'course_id' => 5,
                'watched' => 1,
            ],
            [
                'user_id' => 12,
                'video_id' => 3,
                'course_id' => 5,
                'watched' => 1,
            ],
            [
                'user_id' => 12,
                'video_id' => 4,
                'course_id' => 5,
                'watched' => 1,
            ],
            [
                'user_id' => 13,
                'video_id' => 1,
                'course_id' => 1,
                'watched' => 1,
            ],
            [
                'user_id' => 13,
                'video_id' => 2,
                'course_id' => 1,
                'watched' => 1,
            ],
            [
                'user_id' => 14,
                'video_id' => 1,
                'course_id' => 1,
                'watched' => 1,
            ],
            [
                'user_id' => 14,
                'video_id' => 2,
                'course_id' => 1,
                'watched' => 1,
            ],
            [
                'user_id' => 14,
                'video_id' => 1,
                'course_id' => 3,
                'watched' => 1,
            ],
            [
                'user_id' => 14,
                'video_id' => 1,
                'course_id' => 4,
                'watched' => 1,
            ],
            [
                'user_id' => 14,
                'video_id' => 1,
                'course_id' => 5,
                'watched' => 1,
            ],
            [
                'user_id' => 15,
                'video_id' => 1,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 15,
                'video_id' => 2,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 15,
                'video_id' => 3,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 16,
                'video_id' => 1,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 17,
                'video_id' => 1,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 17,
                'video_id' => 2,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 17,
                'video_id' => 3,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 17,
                'video_id' => 4,
                'course_id' => 8,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 1,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 2,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 3,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 4,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 5,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 6,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 7,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 18,
                'video_id' => 8,
                'course_id' => 7,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 1,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 2,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 3,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 4,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 5,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 19,
                'video_id' => 6,
                'course_id' => 6,
                'watched' => 1,
            ],
            [
                'user_id' => 20,
                'video_id' => 1,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 20,
                'video_id' => 2,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 20,
                'video_id' => 3,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 20,
                'video_id' => 4,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 20,
                'video_id' => 5,
                'course_id' => 9,
                'watched' => 1,
            ],
            [
                'user_id' => 21,
                'video_id' => 1,
                'course_id' => 10,
                'watched' => 1,
            ],

        ];



        foreach ($videoTeachers as $videoTeacher){
            User_video_pivot::create($videoTeacher);
        }

        foreach ($videoStudents as $videoStudent){
            User_video_pivot::create($videoStudent);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 12,
                'video_id' => $i,
                'course_id' => 1,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 13,
                'video_id' => $i,
                'course_id' => 2,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 13,
                'video_id' => $i,
                'course_id' => 7,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 14,
                'video_id' => $i,
                'course_id' => 3,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 15,
                'video_id' => $i,
                'course_id' => 9,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 15,
                'video_id' => $i,
                'course_id' => 4,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 15,
                'video_id' => $i,
                'course_id' => 1,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 16,
                'video_id' => $i,
                'course_id' => 3,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 16,
                'video_id' => $i,
                'course_id' => 6,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 17,
                'video_id' => $i,
                'course_id' => 1,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 17,
                'video_id' => $i,
                'course_id' => 6,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 20,
                'video_id' => $i,
                'course_id' => 1,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 20,
                'video_id' => $i,
                'course_id' => 2,
                'watched' => 1,
            ]);
        }

        for ($i = 1 ; $i <= 10 ; $i++){
            User_video_pivot::create([
                'user_id' => 20,
                'video_id' => $i,
                'course_id' => 10,
                'watched' => 1,
            ]);
        }
    }
}
