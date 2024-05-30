<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Section;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function show_sections()
    {
        return Section::all();
    }

    public function show_categories($section_id)
    {
        return Category::query()->where('section_id' , $section_id)->get();
    }

    public function show_subjects($category_id)
    {
        return Subject::query()->where('category_id' , $category_id)->get();
    }
}
