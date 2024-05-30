<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $Ninth_subject=['maths','english','general_sciences','arabic'];
            $Bachelor_subject=['physics','chemistry','maths','english','science','arabic'];
            $collage_subject=['programming','analysis','electrical_circuits','linear_algebra','national_culture','arabic'];
            for ($i=0;$i<4;$i++) {
                Subject::query()->create([
                    'category_id' => 1,
                    'name' => $Ninth_subject[$i],
                ]);
            }

        for ($j=0; $j<3 ;$j++){
            for ($i=0;$i<6;$i++) {
                Subject::query()->create([
                    'category_id' => 2+$j,
                    'name' => $Bachelor_subject[$i],
                ]);
            }
        }

        for ($j=0; $j<5 ;$j++){
            for ($i=0;$i<6;$i++) {
                Subject::query()->create([
                    'category_id' => 3+$j,
                    'name' => $collage_subject[$i],
                ]);
            }
        }

    }
}
