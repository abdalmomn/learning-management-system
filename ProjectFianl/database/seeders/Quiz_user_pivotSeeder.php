<?php

namespace Database\Seeders;

use App\Models\Quiz_user_pivot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Quiz_user_pivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = [
            [
                'user_id' => 12,
                'quiz_id' => 1,
                'mark' => 20
            ],
            [
                'user_id' => 12,
                'quiz_id' => 5,
                'mark' => 20
            ],
            [
                'user_id' => 13,
                'quiz_id' => 2,
                'mark' => 20
            ],
            [
                'user_id' => 13,
                'quiz_id' => 1,
                'mark' => 20
            ],
            [
                'user_id' => 14,
                'quiz_id' => 3,
                'mark' => 20
            ],
            [
                'user_id' => 15,
                'quiz_id' => 4,
                'mark' => 20
            ],
            [
                'user_id' => 15,
                'quiz_id' => 1,
                'mark' => 20
            ],
            [
                'user_id' => 15,
                'quiz_id' => 9,
                'mark' => 20
            ],
            [
                'user_id' => 16,
                'quiz_id' => 3,
                'mark' => 20
            ],
            [
                'user_id' => 16,
                'quiz_id' => 6,
                'mark' => 20
            ],
            [
                'user_id' => 17,
                'quiz_id' => 6,
                'mark' => 20
            ],
            [
                'user_id' => 17,
                'quiz_id' => 8,
                'mark' => 20
            ],
            [
                'user_id' => 18,
                'quiz_id' => 7,
                'mark' => 20
            ],
            [
                'user_id' => 19,
                'quiz_id' => 6,
                'mark' => 20
            ],
            [
                'user_id' => 20,
                'quiz_id' => 10,
                'mark' => 20
            ],
            [
                'user_id' => 20,
                'quiz_id' => 2,
                'mark' => 20
            ],
            [
                'user_id' => 20,
                'quiz_id' => 9,
                'mark' => 20
            ],
            [
                'user_id' => 21,
                'quiz_id' => 10,
                'mark' => 20
            ],
        ];
       // Quiz_user_pivot::factory(5)->create();
        for ($i = 1 ; $i <= 10 ; $i++){
            Quiz_user_pivot::create([
                'user_id' => $i+1,
                'quiz_id' => $i,
                'type' => 'teacher',
            ]);
        }

        foreach ($quizzes as $quiz) {
            Quiz_user_pivot::create($quiz);
        }
    }
}
