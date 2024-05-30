<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Psy\Output\ProcOutputPager;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            SectionSeeder::class,
            CategorySeeder::class,
            SubjectSeeder::class,
            Role_PermissionSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            VideoSeeder::class,
            CommentSeeder::class,
            QuizSeeder::class,
            Quiz_user_pivotSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
            Course_user_pivotSeeder::class,
            User_video_pivotSeeder::class,
                    ]);
    }
}
