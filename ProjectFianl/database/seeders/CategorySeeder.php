<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Bachelor = ['Scientific', 'Literary', 'Commercial'];
        $Collage=['Informatics_engineering','civil_engineering','architecture_engineering','electrical_engineering','Biomedical_engineering'];

            Category::query()->create([
                'section_id' => 1,
                'name' => 'Scientific',
            ]);


        for ($i = 0; $i < 3; $i++) {
            Category::query()->create([
                'section_id' => 2,
                'name' => $Bachelor[$i],
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            Category::query()->create([
                'section_id' => 3,
                'name' => $Collage[$i],
            ]);
        }
    }
}
