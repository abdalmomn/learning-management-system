<?php

use App\Http\Controllers\Course\VideoController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Dashboard APIs
Route::prefix('dashboard')->controller(DashboardController::class)->group(function (){
    Route::get('/all_students' , 'show_all_students');
    Route::get('/all_teachers' , 'show_all_teachers');
    Route::get('/all_courses_by_subject/{subject_id}' , 'show_all_courses_by_subject');
    Route::get('/all_courses' , 'show_all_courses');
    Route::get('/all_videos_by_course/{course_id}' , 'show_videos_by_course');
    Route::get('/all_videos' , 'show_all_videos');
    Route::get('/all_quizzes' , 'show_all_quizzes');
    Route::get('/all_quizzes_by_course/{course_id}' , 'show_quizzes_by_course');

    Route::get('/student_info/{user_id}' , 'show_profile');
    Route::post('update_profile/{user_id}' , 'update_profile');
    Route::get('delete_profile/{user_id}' , 'delete_profile');
    Route::get('show_course/{course_id}' , 'show_course');
    Route::post('create_course' , 'create_course');
    Route::post('update_course/{course_id}' , 'update_course');
    Route::get('delete_course/{course_id}','delete_course');

    Route::get('show_video/{course_id}/{video_id}','show_video');
    Route::post('create_video/{course_id}','create_video');
    Route::post('update_video/{course_id}/{video_id}','update_video');
    Route::get('delete_video/{course_id}/{video_id}','delete_video');
    Route::get('add_like/{video_id}' , 'add_like');
    Route::get('remove_like/{video_id}' , 'remove_like');
    Route::get('add_dislike/{video_id}' , 'add_dislike');
    Route::get('remove_dislike/{video_id}' , 'remove_dislike');


    Route::get('create_quiz/{course_id}', 'create_quiz');
    Route::get('delete_quiz/{quiz_id}', 'delete_quiz');
    Route::get('show_quizzes_with_question_and_answer', 'show_quizzes_with_question_and_answer');
    Route::get('go_to_quiz/{course_id}', 'go_to_quiz');
    Route::post('submit_quiz/{quiz_id}', 'submit_quiz');


    Route::post('create_answer/{question_id}','create_answer');
    Route::get('delete_answer/{answer_id}','delete_answer');
    Route::post('update_answer/{answer_id}','update_answer');
    Route::get('show_answer/{question_id}', 'show_answer');


    Route::post('create_question/{quiz_id}', 'create_question');
    Route::post('update_question/{question_id}', 'update_question');
    Route::get('delete_question/{question_id}', 'delete_question');
    Route::get('show_question/{quiz_id}', 'show_question');


    Route::get('get_comments/{video_id}' , 'get_comments');
    Route::get('get_replies/{comment_id}' , 'get_replies');
    Route::post('add_comment/{video_id}' , 'add_comment');
    Route::post('add_reply/{comment_id}' , 'add_reply');
    Route::get('delete_comment/{comment_id}' , 'delete_comment');



    Route::get('get_reports' , 'get_reports');



        Route::post('search_course/{subject_id}','search_course');
        Route::post('search_video/{course_id}','search_video');

    Route::get('all_transactions' , 'all_transactions');


});


