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
        User_video_pivot::factory(10)->create();
    }
}
