<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserOperationController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\SubjectController;
use App\Http\Controllers\Course\VideoController;
use App\Http\Controllers\Operation\CommentsAndRepliesController;
use App\Http\Controllers\Operation\NotificationOperationController;
use App\Http\Controllers\Operation\StudentAndTeacherOperationController;
use App\Http\Controllers\Operation\SearchController;
use App\Http\Controllers\Operation\WalletOperationController;
use App\Http\Controllers\Quiz\AnswerController;
use App\Http\Controllers\Quiz\QuestionController;
use App\Http\Controllers\Quiz\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});






//Routes for course operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('course')->controller(CourseController::class)->group(function (){
        Route::get('show_course/{course_id}','show_course')->name('course.show')->middleware('can:show.course');        Route::get('show_courses/{subject_id}','show_courses')->name('courses.show')->middleware('can:show.course');
        Route::get('teacher_courses/{teacher_id}' , 'teacher_show_courses')->name('teacher.course');
        Route::get('myspace_courses' , 'myspace_course')->name('myspace.course');
        Route::post('create_course','create_course')->name('create.course')->middleware('can:create.course');
        Route::post('update_course/{id}','update_course')->name('update.course')->middleware('can:update.course');
        Route::delete('delete_course/{id}','delete_course')->name('delete.course')->middleware('can:delete.course');
        Route::post('paid_course/{id}','paid_for_course')->name('paid.course');
        Route::post('add_to_favorite/{course_id}' , 'add_to_favorite')->name('add_to_favorite.course');
        Route::post('remove_from_favorite/{course_id}' , 'remove_from_favorite')->name('remove_from_favorite.course');
        Route::get('show_favorite' , 'show_favorite')->name('show_favorite.course');
        Route::post('add_rate_for_course/{course_id}' , 'add_rate_for_course')->name('add_rate_for_course.course');

    });

});

//Routes for course operation by bashir
Route::middleware('auth:sanctum')->group(function (){
    Route::controller(StudentAndTeacherOperationController::class)->group(function (){
        Route::get('best_seller/{subject_id}' , 'best_seller')->name('best_seller.course');
        Route::post('send_report_student/{student_id}' , 'send_report_student');
        Route::post('send_report_teacher/{teacher_id}' , 'send_report_teacher');
        Route::get('get_reports' , 'get_reports');



    });

});

//Routes for course operation by bashir
Route::middleware('auth:sanctum')->group(function (){
    Route::controller(CommentsAndRepliesController::class)->group(function (){
        Route::get('get_comments/{video_id}' , 'get_comments');
        Route::get('get_replies/{comment_id}' , 'get_replies');
        Route::post('add_comment/{video_id}' , 'add_comment');
        Route::post('add_reply/{comment_id}' , 'add_reply');
        Route::get('delete_comment/{comment_id}' , 'delete_comment');


    });

});

//Routes for wallet operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('wallet')->controller(WalletOperationController::class)->group(function (){
        Route::post('asked_deposit',  'asked_deposit')->name('asked_deposit.deposit');
        Route::get('deposit', 'deposit')->name('wallet.deposit')->middleware('signed');
        Route::post('withdraw',  'withdraw')->name('wallet.withdraw');
        Route::get('balance',  'balance')->name('wallet.balance');
        Route::get('transactions',  'transactions')->name('wallet.transactions');



    });

});

//Routes for video operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('video')->controller(VideoController::class)->group(function (){
        Route::get('show_all_videos/{course_id}','show_all_videos')->name('show.video')->middleware('can:show.video');
        Route::get('show_video/{course_id}/{video_id}','show_video')->name('show.video')->middleware('can:show.video');
        Route::post('create_video/{course_id}','create_video')->name('create.video')->middleware('can:create.video');
        Route::post('update_video/{course_id}/{video_id}','update_video')->name('update.video')->middleware('can:update.video');
        Route::delete('delete_video/{course_id}/{video_id}','delete_video')->name('delete.video')->middleware('can:delete.video');
        Route::get('add_like/{video_id}' , 'add_like')->name('video.add_like');
        Route::get('remove_like/{video_id}' , 'remove_like')->name('video.remove_like');
        Route::get('add_dislike/{video_id}' , 'add_dislike')->name('video.add_dislike');
        Route::get('remove_dislike/{video_id}' , 'remove_dislike')->name('video.remove_dislike');
    });

});

//show : category section subjects
Route::middleware('auth:sanctum')->group(function(){
    Route::controller(SubjectController::class)->group(function(){
        Route::get('section' , 'show_sections');
        Route::get('category/{section_id}' , 'show_categories');
        Route::get('subject/{category_id}' , 'show_subjects');
    });
});

//Routes for register and login and logout
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('user.register');
    Route::post('login', 'login')->name('user.login');
    Route::post('user_forgot_password', 'user_forgot_password')->name('user.user_forgot_password');
    Route::post('user_check_code', 'user_check_code')->name('user.user_check_code');
    Route::post('user_reset_password', 'user_reset_password')->name('user.user_reset_password');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('logout', 'logout')->name('user.logout')->name('user.logout');
        Route::post('Accept_teacher_coming_by_email/{id}', 'Accept_teacher_coming_by_email')->name('Accept.teacher')->middleware('can:add.teacher');
        Route::post('admin_adding_new_teacher', 'admin_adding_new_teacher')->name('admin.adding.new.teacher')->middleware('can:add.teacher');
    });
});

//Routes for users operation (admin,teachers,students)
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserOperationController::class)->group(function () {
        Route::get('show_students', 'show_students')->name('students.show')->middleware('can:show.students');//بدي اكد على ه
        Route::get('show_student_special_course/{course_id}', 'show_student_special_course')->name('students.show')->middleware('can:show.students');//بدي اكد على ه
        Route::get('show_teachers', 'show_teachers')->name('teachers.show')->middleware('can:show.teachers');
        Route::get('show_teachers_by_subject/{subject_id}', 'show_teachers_by_subject')->name('teachers.show')->middleware('can:show.teachers');
        Route::get('delete_student/{id}', 'delete_student')->name('delete.student')->middleware('can:delete.student');
        Route::get('delete_teacher/{id}', 'delete_teacher')->name('delete.teacher')->middleware('can:delete.teacher');
        Route::post('update_profile', 'update_profile')->name('update.profile');
        Route::get('show_profile', 'show_profile')->name('show.profile');

    });
});


//Routes for quizzes and questions and answers
Route::middleware('auth:sanctum')->group(function () {
    //Routes for quizzes
    Route::controller(QuizController::class)->group(function () {
        Route::get('create_quiz/{course_id}', 'create_quiz')->name('create.quiz')->middleware('can:create.quiz');
        Route::get('delete_quiz/{quiz_id}', 'delete_quiz')->name('delete.quiz')->middleware('can:delete.quiz');
        Route::get('show_quizzes', 'show_quizzes')->name('show.quiz')->middleware('can:show.quiz');
        Route::get('show_quizzes_with_question_and_answer', 'show_quizzes_with_question_and_answer')->name('show.quiz')->middleware('can:show.quiz');
        Route::get('go_to_quiz/{course_id}', 'go_to_quiz')->name('show.quiz')->middleware('can:show.quiz');
        Route::post('submit_quiz/{quiz_id}', 'submit_quiz')->name('submit.quiz');
        Route::get('sh','sh');
    });

    //Routes for questions
    Route::controller(QuestionController::class)->group(function () {
        Route::post('create_question/{quiz_id}', 'create_question')->name('create.question')->middleware('can:create.quiz');
        Route::post('update_question/{question_id}', 'update_question')->name('update.question')->middleware('can:update.quiz');
        Route::get('delete_question/{question_id}', 'delete_question')->name('delete.question')->middleware('can:delete.quiz');
        Route::get('show_question/{quiz_id}', 'show_question')->name('show.question')->middleware('can:show.quiz');

    });
    //Routes for answers
    Route::controller(AnswerController::class)->group(function (){
        Route::post('create_answer/{question_id}','create_answer')->name('create.answer')->middleware('can:create.quiz');
        Route::get('delete_answer/{answer_id}','delete_answer')->name('delete.answer')->middleware('can:delete.quiz');
        Route::post('update_answer/{answer_id}','update_answer')->name('update.answer')->middleware('can:update.quiz');
        Route::get('show_answer/{question_id}', 'show_answer')->name('show.answer')->middleware('can:show.quiz');

    });

    //Routes for operations
    Route::controller(SearchController::class)->group(function (){
        Route::post('search_course/{subject_id}','search_course')->name('search.course');
        Route::post('search_video/{course_id}','search_video')->name('search.course');

    });

    //Routes for notifications operations
    Route::controller(NotificationOperationController::class)->group(function (){
        Route::get('get_all_notifications_read','get_all_notifications_read')->name('get.notification.read');
        Route::get('get_all_notifications_not_read','get_all_notifications_not_read')->name('get.notification.notRead');
        Route::post('markAsRead/{notification_id}','markAsRead')->name('get.notification.notRead');


    });

});
