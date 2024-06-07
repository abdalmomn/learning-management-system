<?php

namespace Database\Seeders;

use App\Models\Answer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Answer::factory(40)->create();

        for ($j = 1 ; $j <= 100 ; $j++){
            for ($i = 1 ; $i <= 4 ; $i++){
                if($i == 4){
                    $role = 1;
                }else{
                    $role = 0;
                }
                Answer::create([
                    'question_id' => $j,
                    'choice' => 'Choice'.$i,
                    'role' => $role ,
                ]);
            }
        }
    }
}
