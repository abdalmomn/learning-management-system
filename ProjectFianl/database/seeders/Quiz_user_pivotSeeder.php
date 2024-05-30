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
        Quiz_user_pivot::factory(5)->create();
    }
}
