<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      //  Question::factory(10)->create();
        for ($j = 1 ; $j <= 10 ; $j++){
            for ($i = 1 ; $i <= 10 ; $i++){
            Question::create([
                'quiz_id' => $j,
                'question'=> 'q'.$i,
            ]);
        }
        }
    }
}
