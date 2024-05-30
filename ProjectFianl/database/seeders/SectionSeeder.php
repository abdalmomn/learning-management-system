<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section=['Ninth','Bachelor','Collage'];
        for($i=0;$i<3;$i++){
        Section::query()->create([
          'name'=>$section[$i],
        ]);
        }
    }
}
