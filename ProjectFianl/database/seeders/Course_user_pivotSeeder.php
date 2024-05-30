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
        Course_user_pivot::factory(10)->create();
    }
}
